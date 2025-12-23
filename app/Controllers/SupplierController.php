<?php

namespace App\Controllers;

use App\Models\SupplierModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class SupplierController extends BaseController
{
    protected $supplierModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
    }

    private function normalizePhoneNumber($phone)
    {
        $phone = preg_replace('/[^\d+]/', '', $phone);

        if (substr($phone, 0, 2) === '08') {
            $phone = '+62' . substr($phone, 1);
        }

        if (substr($phone, 0, 2) === '62' && substr($phone, 0, 1) !== '+') {
            $phone = '+' . $phone;
        }

        if (substr($phone, 0, 1) === '8' && strlen($phone) >= 9) {
            $phone = '+62' . $phone;
        }

        return $phone;
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $search = $this->request->getGet('search');

        $builder = $this->supplierModel->orderBy('created_at', 'DESC');

        if ($search) {
            $builder->like('nama_suppl', $search);
        }

        $data = [
            'title' => 'Supplier',
            'suppliers' => $builder->paginate(10, 'default'),
            'pager' => $this->supplierModel->pager,
            'search' => $search
        ];

        return view('admin/supplier', $data);
    }

    public function store()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'nama_suppl' => 'required|min_length[3]|max_length[100]',
            'alamat'     => 'required|min_length[3]',
            'telepon'    => 'required|regex_match[/^(\+62|62|08|8)[0-9]{8,12}$/]',
            'status'     => 'required|in_list[aktif,tidak aktif]',
        ];

        $messages = [
            'nama_suppl' => [
                'required' => 'Nama supplier harus diisi.',
                'min_length' => 'Nama supplier minimal 3 karakter.',
                'max_length' => 'Nama supplier maksimal 100 karakter.',
            ],
            'alamat' => [
                'required' => 'Alamat harus diisi.',
                'min_length' => 'Alamat minimal 3 karakter.',
            ],
            'telepon' => [
                'required' => 'Nomor telepon harus diisi.',
                'regex_match' => 'Format nomor telepon tidak valid. Gunakan format 08xxxxxxxxxx atau +62xxxxxxxxxxx.',
            ],
            'status' => [
                'required' => 'Status harus dipilih.',
                'in_list' => 'Status harus aktif atau tidak aktif.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $errors = $validation->getErrors();
            $errorMessage = implode(' ', $errors);
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        $telepon = $this->normalizePhoneNumber($this->request->getPost('telepon'));

        $data = [
            'nama_suppl' => $this->request->getPost('nama_suppl'),
            'alamat'     => $this->request->getPost('alamat'),
            'telepon'    => $telepon,
            'status'     => $this->request->getPost('status'),
        ];

        if ($this->supplierModel->insert($data)) {
            return redirect()->to('/supplier')->with('success', 'Supplier berhasil ditambahkan!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan supplier. Silakan coba lagi.');
        }
    }

    public function update()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $id = $this->request->getPost('id');

        $supplier = $this->supplierModel->find($id);
        if (!$supplier) {
            return redirect()->back()->with('error', 'Supplier tidak ditemukan.');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'nama_suppl' => 'required|min_length[3]|max_length[100]',
            'alamat'     => 'required|min_length[3]',
            'telepon'    => 'required|regex_match[/^(\+62|62|08|8)[0-9]{8,12}$/]',
            'status'     => 'required|in_list[aktif,tidak aktif]',
        ];

        $messages = [
            'nama_suppl' => [
                'required' => 'Nama supplier harus diisi.',
                'min_length' => 'Nama supplier minimal 3 karakter.',
                'max_length' => 'Nama supplier maksimal 100 karakter.',
            ],
            'alamat' => [
                'required' => 'Alamat harus diisi.',
                'min_length' => 'Alamat minimal 3 karakter.',
            ],
            'telepon' => [
                'required' => 'Nomor telepon harus diisi.',
                'regex_match' => 'Format nomor telepon tidak valid. Gunakan format 08xxxxxxxxxx atau +62xxxxxxxxxxx.',
            ],
            'status' => [
                'required' => 'Status harus dipilih.',
                'in_list' => 'Status harus aktif atau tidak aktif.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $errors = $validation->getErrors();
            $errorMessage = implode(' ', $errors);
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        $telepon = $this->normalizePhoneNumber($this->request->getPost('telepon'));

        $data = [
            'nama_suppl' => $this->request->getPost('nama_suppl'),
            'alamat'     => $this->request->getPost('alamat'),
            'telepon'    => $telepon,
            'status'     => $this->request->getPost('status'),
        ];

        if ($this->supplierModel->update($id, $data)) {
            return redirect()->to('/supplier')->with('success', 'Supplier berhasil diperbarui!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui supplier. Silakan coba lagi.');
        }
    }
}
