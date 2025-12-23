<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        $loginUserId = session()->get('user_id');
        $search = $this->request->getGet('search');

        $builder = $this->userModel->where('id !=', $loginUserId)->orderBy('created_at', 'DESC');

        if ($search) {
            $builder->groupStart()
                    ->like('nama', $search)
                    ->orLike('username', $search)
                    ->groupEnd();
        }

        $data = [
            'title' => 'Users Management',
            'users' => $builder->paginate(10, 'default'),
            'pager' => $this->userModel->pager,
            'search' => $search
        ];

        return view('admin/users', $data);
    }

    public function store()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'nama'      => 'required|min_length[3]|max_length[100]',
            'username'  => 'required|alpha_numeric|min_length[3]|max_length[255]|is_unique[users.username]',
            'password'  => 'required|min_length[6]|max_length[255]',
            'role'      => 'required|in_list[pemilik,kasir]',
            'status'    => 'required|in_list[aktif,tidak aktif]',
        ];

        if (!$this->validate($rules)) {
            $errors = $validation->getErrors();
            $errorMessage = implode(', ', $errors);
            return redirect()->back()->withInput()->with('error', 'Data tidak valid: ' . $errorMessage);
        }

        $data = [
            'nama'      => $this->request->getPost('nama'),
            'username'  => $this->request->getPost('username'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'      => $this->request->getPost('role'),
            'status'    => $this->request->getPost('status'),
        ];

        if ($this->userModel->insert($data)) {
            return redirect()->to('/users')->with('success', 'Pengguna berhasil ditambahkan!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan pengguna. Silakan coba lagi.');
        }
    }

    public function update()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $id = $this->request->getPost('id');

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'nama'      => 'required|min_length[3]|max_length[100]',
            'username'  => "required|alpha_numeric|min_length[3]|max_length[255]|is_unique[users.username,id,{$id}]",
            'role'      => 'required|in_list[pemilik,kasir]',
            'status'    => 'required|in_list[aktif,tidak aktif]',
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]|max_length[255]';
        }

        if (!$this->validate($rules)) {
            $errors = $validation->getErrors();
            $errorMessage = implode(', ', $errors);
            return redirect()->back()->withInput()->with('error', 'Data tidak valid: ' . $errorMessage);
        }

        $data = [
            'nama'      => $this->request->getPost('nama'),
            'username'  => $this->request->getPost('username'),
            'role'      => $this->request->getPost('role'),
            'status'    => $this->request->getPost('status'),
        ];

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($this->userModel->update($id, $data)) {
            return redirect()->to('/users')->with('success', 'Pengguna berhasil diperbarui!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pengguna. Silakan coba lagi.');
        }
    }
}
