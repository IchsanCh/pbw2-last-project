<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Models\DetilPenjualanModel;
use App\Models\PenjualanModel;
use CodeIgniter\HTTP\ResponseInterface;

class PenjualanController extends BaseController
{
    protected $penjualanModel;
    protected $detilPenjualanModel;
    protected $barangModel;

    public function __construct()
    {
        $this->penjualanModel = new PenjualanModel();
        $this->detilPenjualanModel = new DetilPenjualanModel();
        $this->barangModel = new BarangModel();
    }


    // Generate ID penjualan format: PJ-YYYYMMDD-XXXX

    private function generateIdPenjualan()
    {
        $date = date('Ymd');
        $prefix = 'PJ-' . $date . '-';

        $lastId = $this->penjualanModel
            ->like('id', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastId) {
            $lastNumber = (int) substr($lastId['id'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');
        $search = $this->request->getGet('search');

        $builder = $this->penjualanModel->getRiwayatByUser($userId, $search);

        $penjualans = $builder->paginate(10, 'default');

        foreach ($penjualans as &$penjualan) {
            $detils = $this->detilPenjualanModel
                ->where('id_penjualan', $penjualan['id'])
                ->findAll();

            $totalItem = count($detils);
            $totalHarga = 0;

            foreach ($detils as $detil) {
                $totalHarga += ($detil['qty'] * $detil['harga_jual']);
            }

            $penjualan['total_item'] = $totalItem;
            $penjualan['total_harga'] = $totalHarga;
        }

        $data = [
            'title' => 'Riwayat Transaksi',
            'penjualans' => $penjualans,
            'pager' => $this->penjualanModel->pager,
            'search' => $search
        ];

        return view('admin/riwayat/index', $data);
    }

    public function create()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $barangs = $this->barangModel
            ->where('status', 'aktif')
            ->where('stok >', 0)
            ->orderBy('nama_brg', 'ASC')
            ->findAll();

        $data = [
            'title' => 'Checkout Penjualan',
            'generated_id' => $this->generateIdPenjualan(),
            'barangs' => $barangs
        ];

        return view('admin/penjualan/create', $data);
    }

    public function store()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $validation = \Config\Services::validation();
        $rules = [
            'id' => 'required|max_length[20]|is_unique[penjualans.id]',
            'status_bayar' => 'required|in_list[lunas,belum lunas]',
        ];

        $messages = [
            'id' => [
                'required' => 'ID penjualan harus diisi.',
                'is_unique' => 'ID penjualan sudah digunakan.',
            ],
            'status_bayar' => [
                'required' => 'Status pembayaran harus dipilih.',
                'in_list' => 'Status pembayaran tidak valid.',
            ],
        ];

        $statusBayar = $this->request->getPost('status_bayar');
        if ($statusBayar === 'belum lunas') {
            $rules['nama_pembeli'] = 'required|max_length[100]';
            $messages['nama_pembeli'] = [
                'required' => 'Nama pembeli wajib diisi untuk transaksi hutang.',
                'max_length' => 'Nama pembeli maksimal 100 karakter.',
            ];
        }

        if (!$this->validate($rules, $messages)) {
            $errors = $validation->getErrors();
            $errorMessage = implode(' ', $errors);
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        $items = $this->request->getPost('items');

        if (empty($items) || !is_array($items)) {
            return redirect()->back()->withInput()->with('error', 'Minimal harus ada 1 item barang.');
        }

        $mergedItems = [];
        foreach ($items as $index => $item) {
            if (
                !empty($item['id_barang']) &&
                !empty($item['qty']) &&
                !empty($item['harga_jual']) &&
                $item['qty'] > 0 &&
                $item['harga_jual'] > 0
            ) {
                $idBarang = $item['id_barang'];

                if (isset($mergedItems[$idBarang])) {
                    $mergedItems[$idBarang]['qty'] += $item['qty'];
                } else {
                    $mergedItems[$idBarang] = [
                        'id_barang' => $item['id_barang'],
                        'qty' => $item['qty'],
                        'harga_jual' => $item['harga_jual']
                    ];
                }
            } else {
                log_message('warning', "Item tidak valid pada index $index");
            }
        }

        if (empty($mergedItems)) {
            return redirect()->back()->withInput()->with('error', 'Tidak ada item barang yang valid.');
        }

        $validItems = [];
        foreach ($mergedItems as $item) {
            $barang = $this->barangModel->find($item['id_barang']);

            if (!$barang) {
                return redirect()->back()->withInput()->with('error', 'Barang dengan ID "' . $item['id_barang'] . '" tidak ditemukan.');
            }

            if ($item['qty'] > $barang['stok']) {
                return redirect()->back()->withInput()->with(
                    'error',
                    'Stok barang "' . $barang['nama_brg'] . '" tidak mencukupi. Total qty yang diminta: ' . $item['qty'] . ', stok tersedia: ' . $barang['stok'] . ' ' . $barang['satuan']
                );
            }

            $validItems[] = $item;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $dataPenjualan = [
                'id' => $this->request->getPost('id'),
                'id_user' => session()->get('user_id'),
                'status_bayar' => $statusBayar,
                'nama_pembeli' => $statusBayar === 'belum lunas' ? $this->request->getPost('nama_pembeli') : null,
                'alasan_batal' => null
            ];

            $insertResult = $this->penjualanModel->insert($dataPenjualan);

            if ($insertResult === false) {
                $errors = $this->penjualanModel->errors();
                throw new \Exception('Gagal menyimpan data penjualan: ' . json_encode($errors));
            }

            foreach ($validItems as $item) {
                $dataDetil = [
                    'id_penjualan' => $dataPenjualan['id'],
                    'id_barang' => $item['id_barang'],
                    'qty' => $item['qty'],
                    'harga_jual' => $item['harga_jual']
                ];

                $detailResult = $this->detilPenjualanModel->insert($dataDetil);

                if ($detailResult === false) {
                    $errors = $this->detilPenjualanModel->errors();
                    throw new \Exception('Gagal menyimpan detail penjualan: ' . json_encode($errors));
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal.');
            }

            return redirect()->to('/penjualan/create')->with('success', 'Transaksi penjualan berhasil disimpan!');

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

    public function detil($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $penjualan = $this->penjualanModel
            ->select('penjualans.*, users.nama as nama_user')
            ->join('users', 'users.id = penjualans.id_user')
            ->where('penjualans.id', $id)
            ->first();

        if (!$penjualan) {
            return redirect()->to('/penjualan')->with('error', 'Transaksi penjualan tidak ditemukan.');
        }

        $detils = $this->detilPenjualanModel
            ->select('detil_penjualans.*, barangs.nama_brg, barangs.satuan')
            ->join('barangs', 'barangs.id = detil_penjualans.id_barang')
            ->where('id_penjualan', $id)
            ->findAll();

        $totalQty = 0;
        $totalHarga = 0;

        foreach ($detils as &$detil) {
            $subtotal = $detil['qty'] * $detil['harga_jual'];
            $detil['subtotal'] = $subtotal;
            $totalQty += $detil['qty'];
            $totalHarga += $subtotal;
        }

        $data = [
            'title' => 'Detail Transaksi Penjualan',
            'penjualan' => $penjualan,
            'detils' => $detils,
            'total_qty' => $totalQty,
            'total_harga' => $totalHarga
        ];

        return view('admin/riwayat/detil', $data);
    }

    public function showEdit()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        $search = $this->request->getGet('search');

        $builder = $this->penjualanModel->orderBy('created_at', 'DESC');
        if (!empty($search)) {
            $builder->like('id', $search)->orderBy('created_at', 'DESC');
        }

        $penjualans = $builder->paginate(10, 'default');

        foreach ($penjualans as &$penjualan) {
            $detils = $this->detilPenjualanModel
                ->where('id_penjualan', $penjualan['id'])
                ->findAll();

            $totalItem = count($detils);
            $totalHarga = 0;

            foreach ($detils as $detil) {
                $totalHarga += ($detil['qty'] * $detil['harga_jual']);
            }

            $penjualan['total_item'] = $totalItem;
            $penjualan['total_harga'] = $totalHarga;
        }

        $data = [
            'title' => 'Transaksi Penjualan',
            'penjualans' => $penjualans,
            'pager' => $this->penjualanModel->pager,
            'search' => $search
        ];

        return view('admin/penjualan/index', $data);
    }

    public function updateStatus()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $id = $this->request->getPost('id');
        $status_bayar = $this->request->getPost('status_bayar');
        $alasan_batal = $this->request->getPost('alasan_batal');
        $nama_pembeli = $this->request->getPost('nama_pembeli');
        if ($status_bayar === 'belum lunas' && empty($nama_pembeli)) {
            return redirect()->to('penjualan/edit')->with('error', 'Nama pembeli wajib diisi untuk status Belum Lunas');
        }

        if ($status_bayar === 'dibatalkan' && empty($alasan_batal)) {
            return redirect()->to('penjualan/edit')->with('error', 'Alasan pembatalan wajib diisi untuk status Dibatalkan');
        }

        $data = [
            'status_bayar' => $status_bayar,
        ];
        if ($status_bayar === 'belum lunas') {
            $data['nama_pembeli'] = $nama_pembeli;
            $data['alasan_batal'] = null;
        } elseif ($status_bayar === 'dibatalkan') {
            $data['alasan_batal'] = $alasan_batal;
        } else {
            $data['alasan_batal'] = null;
        }

        if ($this->penjualanModel->update($id, $data)) {
            return redirect()->to('penjualan/edit')->with('success', 'Status pembayaran berhasil diupdate');
        } else {
            return redirect()->to('penjualan/edit')->with('error', 'Gagal update status pembayaran');
        }
    }
}
