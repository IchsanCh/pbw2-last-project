<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\KategoriModel;

class KategoriController extends BaseController
{
    protected $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $search = $this->request->getGet('search');

        $builder = $this->kategoriModel->orderBy('created_at', 'DESC');

        if ($search) {
            $builder->like('nama_kategori', $search);
        }

        $data = [
            'title' => 'Kategori',
            'kategoris' => $builder->paginate(10, 'default'),
            'pager' => $this->kategoriModel->pager,
            'search' => $search
        ];

        return view('admin/kategori', $data);
    }

    public function store()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'nama_kategori' => 'required|min_length[3]|max_length[100]',
            'status'        => 'required|in_list[aktif,tidak aktif]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid. Periksa kembali inputan Anda.');
        }

        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'status'        => $this->request->getPost('status'),
        ];

        if ($this->kategoriModel->insert($data)) {
            return redirect()->to('/kategori')->with('success', 'Kategori berhasil ditambahkan!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan kategori. Silakan coba lagi.');
        }
    }

    public function update()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $id = $this->request->getPost('id');

        $kategori = $this->kategoriModel->find($id);
        if (!$kategori) {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan.');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'nama_kategori' => 'required|min_length[3]|max_length[100]',
            'status'        => 'required|in_list[aktif,tidak aktif]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid. Periksa kembali inputan Anda.');
        }

        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'status'        => $this->request->getPost('status'),
        ];

        if ($this->kategoriModel->update($id, $data)) {
            return redirect()->to('/kategori')->with('success', 'Kategori berhasil diperbarui!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kategori. Silakan coba lagi.');
        }
    }
}
