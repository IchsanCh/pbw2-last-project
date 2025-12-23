<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class BarangController extends BaseController
{
    protected $barangModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $search = $this->request->getGet('search');

        $builder = $this->barangModel
            ->select('barangs.*, kategoris.nama_kategori')
            ->join('kategoris', 'kategoris.id = barangs.id_kategori', 'left')
            ->orderBy('barangs.created_at', 'DESC');

        if ($search) {
            $builder->groupStart()
                ->like('barangs.id', $search)
                ->orLike('barangs.nama_brg', $search)
                ->groupEnd();
        }

        $data = [
            'title' => 'Barang',
            'barangs' => $builder->paginate(10, 'default'),
            'pager' => $this->barangModel->pager,
            'search' => $search,
            'kategoris' => $this->kategoriModel->where('status', 'aktif')->findAll()
        ];

        return view('admin/barang', $data);
    }

    public function store()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'id'          => 'required|string|min_length[3]|max_length[20]|is_unique[barangs.id]',
            'id_kategori' => 'required|is_not_unique[kategoris.id]',
            'nama_brg'    => 'required|min_length[3]|max_length[255]',
            'satuan'      => 'required|min_length[2]|max_length[100]',
            'harga'       => 'required|numeric|greater_than[0]',
            'stok'        => 'required|decimal|greater_than_equal_to[0]',
            'min_stok'    => 'required|decimal|greater_than_equal_to[0]',
            'status'      => 'required|in_list[aktif,tidak aktif]'
        ];

        $messages = [
            'id' => [
                'required' => 'ID Barang harus diisi.',
                'string' => 'ID Barang harus berupa teks.',
                'min_length' => 'ID Barang minimal 3 karakter.',
                'max_length' => 'ID Barang maksimal 20 karakter.',
                'is_unique' => 'ID Barang sudah terdaftar. Gunakan ID lain.',
            ],
            'id_kategori' => [
                'required' => 'Kategori harus dipilih.',
                'is_not_unique' => 'Kategori tidak valid.',
            ],
            'nama_brg' => [
                'required' => 'Nama barang harus diisi.',
                'min_length' => 'Nama barang minimal 3 karakter.',
                'max_length' => 'Nama barang maksimal 255 karakter.',
            ],
            'satuan' => [
                'required' => 'Satuan harus diisi.',
                'min_length' => 'Satuan minimal 2 karakter.',
                'max_length' => 'Satuan maksimal 100 karakter.',
            ],
            'harga' => [
                'required' => 'Harga harus diisi.',
                'numeric' => 'Harga harus berupa angka.',
                'greater_than' => 'Harga harus lebih dari 0.',
            ],
            'stok' => [
                'required' => 'Stok harus diisi.',
                'decimal' => 'Stok harus berupa angka.',
                'greater_than_equal_to' => 'Stok tidak boleh negatif.',
            ],
            'min_stok' => [
                'required' => 'Minimal stok harus diisi.',
                'decimal' => 'Minimal stok harus berupa angka.',
                'greater_than_equal_to' => 'Minimal stok tidak boleh negatif.',
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

        $data = [
            'id'          => strtoupper($this->request->getPost('id')),
            'id_kategori' => $this->request->getPost('id_kategori'),
            'nama_brg'    => $this->request->getPost('nama_brg'),
            'satuan'      => $this->request->getPost('satuan'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok'),
            'min_stok'    => $this->request->getPost('min_stok'),
            'status'      => $this->request->getPost('status'),
        ];

        if ($this->barangModel->insert($data)) {
            return redirect()->to('/barang')->with('success', 'Barang berhasil ditambahkan!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan barang. Silakan coba lagi.');
        }
    }

    public function update()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $id = $this->request->getPost('id');

        $barang = $this->barangModel->find($id);
        if (!$barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan.');
        }

        $validation = \Config\Services::validation();

        $rules = [
            'id_kategori' => 'required|is_not_unique[kategoris.id]',
            'nama_brg'    => 'required|min_length[3]|max_length[255]',
            'satuan'      => 'required|min_length[2]|max_length[100]',
            'harga'       => 'required|numeric|greater_than[0]',
            'stok'        => 'required|decimal|greater_than_equal_to[0]',
            'min_stok'    => 'required|decimal|greater_than_equal_to[0]',
            'status'      => 'required|in_list[aktif,tidak aktif]'
        ];

        $messages = [
            'id_kategori' => [
                'required' => 'Kategori harus dipilih.',
                'is_not_unique' => 'Kategori tidak valid.',
            ],
            'nama_brg' => [
                'required' => 'Nama barang harus diisi.',
                'min_length' => 'Nama barang minimal 3 karakter.',
                'max_length' => 'Nama barang maksimal 255 karakter.',
            ],
            'satuan' => [
                'required' => 'Satuan harus diisi.',
                'min_length' => 'Satuan minimal 2 karakter.',
                'max_length' => 'Satuan maksimal 100 karakter.',
            ],
            'harga' => [
                'required' => 'Harga harus diisi.',
                'numeric' => 'Harga harus berupa angka.',
                'greater_than' => 'Harga harus lebih dari 0.',
            ],
            'stok' => [
                'required' => 'Stok harus diisi.',
                'decimal' => 'Stok harus berupa angka.',
                'greater_than_equal_to' => 'Stok tidak boleh negatif.',
            ],
            'min_stok' => [
                'required' => 'Minimal stok harus diisi.',
                'decimal' => 'Minimal stok harus berupa angka.',
                'greater_than_equal_to' => 'Minimal stok tidak boleh negatif.',
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

        $data = [
            'id_kategori' => $this->request->getPost('id_kategori'),
            'nama_brg'    => $this->request->getPost('nama_brg'),
            'satuan'      => $this->request->getPost('satuan'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok'),
            'min_stok'    => $this->request->getPost('min_stok'),
            'status'      => $this->request->getPost('status'),
        ];

        if ($this->barangModel->update($id, $data)) {
            return redirect()->to('/barang')->with('success', 'Barang berhasil diperbarui!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui barang. Silakan coba lagi.');
        }
    }
}
