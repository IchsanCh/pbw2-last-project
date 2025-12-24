<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\SupplierModel;
use App\Models\PembelianModel;
use App\Controllers\BaseController;
use App\Models\DetilPembelianModel;
use CodeIgniter\HTTP\ResponseInterface;

class PembelianController extends BaseController
{
    protected $pembelianModel;
    protected $detilPembelianModel;
    protected $supplierModel;
    protected $barangModel;

    public function __construct()
    {
        $this->pembelianModel = new PembelianModel();
        $this->detilPembelianModel = new DetilPembelianModel();
        $this->supplierModel = new SupplierModel();
        $this->barangModel = new BarangModel();
    }

    /**
     * Generate ID pembelian format: PB-YYYYMMDD-XXX
    */
    private function generateIdPembelian()
    {
        $date = date('Ymd');
        $prefix = 'PB-' . $date . '-';

        $lastId = $this->pembelianModel
            ->like('id', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastId) {
            $lastNumber = (int) substr($lastId['id'], -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $search = $this->request->getGet('search');

        $builder = $this->pembelianModel
            ->select('pembelians.*, suppliers.nama_suppl, users.nama as nama_user')
            ->join('suppliers', 'suppliers.id = pembelians.id_supplier')
            ->join('users', 'users.id = pembelians.id_user')
            ->orderBy('pembelians.created_at', 'DESC');

        if ($search) {
            $builder->groupStart()
                ->like('pembelians.id', $search)
                ->orLike('suppliers.nama_suppl', $search)
                ->groupEnd();
        }

        $pembelians = $builder->paginate(10, 'default');

        foreach ($pembelians as &$pembelian) {
            $detils = $this->detilPembelianModel
                ->where('id_pembelian', $pembelian['id'])
                ->findAll();

            $totalItem = count($detils);
            $totalHarga = 0;

            foreach ($detils as $detil) {
                $totalHarga += ($detil['qty'] * $detil['harga_beli']);
            }

            $pembelian['total_item'] = $totalItem;
            $pembelian['total_harga'] = $totalHarga;
        }

        $data = [
            'title' => 'Transaksi Pembelian',
            'pembelians' => $pembelians,
            'pager' => $this->pembelianModel->pager,
            'search' => $search
        ];

        return view('admin/pembelian/index', $data);
    }

    public function create()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $suppliers = $this->supplierModel
            ->where('status', 'aktif')
            ->orderBy('nama_suppl', 'ASC')
            ->findAll();

        $barangs = $this->barangModel
            ->where('status', 'aktif')
            ->orderBy('nama_brg', 'ASC')
            ->findAll();

        $data = [
            'title' => 'Buat Transaksi Pembelian',
            'generated_id' => $this->generateIdPembelian(),
            'suppliers' => $suppliers,
            'barangs' => $barangs
        ];

        return view('admin/pembelian/create', $data);
    }
    public function store()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        $validation = \Config\Services::validation();
        $rules = [
            'id' => 'required|max_length[15]|is_unique[pembelians.id]',
            'id_supplier' => 'required|is_not_unique[suppliers.id]',
        ];

        $messages = [
            'id' => [
                'required' => 'ID pembelian harus diisi.',
                'is_unique' => 'ID pembelian sudah digunakan.',
            ],
            'id_supplier' => [
                'required' => 'Supplier harus dipilih.',
                'is_not_unique' => 'Supplier tidak valid.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $errors = $validation->getErrors();
            $errorMessage = implode(' ', $errors);
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        $items = $this->request->getPost('items');

        if (empty($items) || !is_array($items)) {
            return redirect()->back()->withInput()->with('error', 'Minimal harus ada 1 item barang.');
        }

        $validItems = [];
        foreach ($items as $index => $item) {
            if (
                !empty($item['id_barang']) &&
                !empty($item['qty']) &&
                !empty($item['harga_beli']) &&
                $item['qty'] > 0 &&
                $item['harga_beli'] > 0
            ) {
                $barang = $this->barangModel->find($item['id_barang']);
                if (!$barang) {
                    return redirect()->back()->withInput()->with('error', 'Barang tidak valid.');
                }

                $validItems[] = [
                    'id_barang' => $item['id_barang'],
                    'qty' => $item['qty'],
                    'harga_beli' => $item['harga_beli']
                ];
            } else {
                log_message('warning', "Item tidak valid pada index $index");
            }
        }

        if (empty($validItems)) {
            return redirect()->back()->withInput()->with('error', 'Tidak ada item barang yang valid.');
        }
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $dataPembelian = [
                'id' => $this->request->getPost('id'),
                'id_supplier' => $this->request->getPost('id_supplier'),
                'id_user' => session()->get('user_id')
            ];

            $insertResult = $this->pembelianModel->insert($dataPembelian);

            if ($insertResult === false) {
                $errors = $this->pembelianModel->errors();
                throw new \Exception('Gagal menyimpan data pembelian: ' . json_encode($errors));
            }

            foreach ($validItems as $index => $item) {
                $dataDetil = [
                    'id_pembelian' => $dataPembelian['id'],
                    'id_barang' => $item['id_barang'],
                    'qty' => $item['qty'],
                    'harga_beli' => $item['harga_beli']
                ];

                $detailResult = $this->detilPembelianModel->insert($dataDetil);

                if ($detailResult === false) {
                    $errors = $this->detilPembelianModel->errors();
                    throw new \Exception('Gagal menyimpan detail pembelian: ' . json_encode($errors));
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal.');
            }

            return redirect()->to('/pembelian')->with('success', 'Transaksi pembelian berhasil disimpan!');

        } catch (\Exception $e) {
            $db->transRollback();
            $dbError = $db->error();
            if (!empty($dbError['message'])) {
                log_message('error', 'Database Error: ' . $dbError['message']);
            }
            log_message('error', 'Exception: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    public function detail($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $pembelian = $this->pembelianModel
            ->select('pembelians.*, suppliers.nama_suppl, suppliers.alamat, suppliers.telepon, users.nama as nama_user')
            ->join('suppliers', 'suppliers.id = pembelians.id_supplier')
            ->join('users', 'users.id = pembelians.id_user')
            ->where('pembelians.id', $id)
            ->first();

        if (!$pembelian) {
            return redirect()->to('/pembelian')->with('error', 'Transaksi pembelian tidak ditemukan.');
        }

        $detils = $this->detilPembelianModel
            ->select('detil_pembelians.*, barangs.nama_brg, barangs.satuan')
            ->join('barangs', 'barangs.id = detil_pembelians.id_barang')
            ->where('id_pembelian', $id)
            ->findAll();

        $totalQty = 0;
        $totalHarga = 0;

        foreach ($detils as &$detil) {
            $subtotal = $detil['qty'] * $detil['harga_beli'];
            $detil['subtotal'] = $subtotal;
            $totalQty += $detil['qty'];
            $totalHarga += $subtotal;
        }

        $data = [
            'title' => 'Detail Transaksi Pembelian',
            'pembelian' => $pembelian,
            'detils' => $detils,
            'total_qty' => $totalQty,
            'total_harga' => $totalHarga
        ];

        return view('admin/pembelian/detail', $data);
    }

    public function delete()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $id = $this->request->getPost('id');

        $pembelian = $this->pembelianModel->find($id);
        if (!$pembelian) {
            return redirect()->back()->with('error', 'Transaksi pembelian tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $this->detilPembelianModel->where('id_pembelian', $id)->delete();

            if (!$this->pembelianModel->delete($id)) {
                throw new \Exception('Gagal menghapus transaksi pembelian.');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal.');
            }

            return redirect()->to('/pembelian')->with('success', 'Transaksi pembelian berhasil dihapus!');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}
