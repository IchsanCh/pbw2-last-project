<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\SupplierModel;
use App\Models\PembelianModel;
use App\Models\PenjualanModel;
use App\Controllers\BaseController;
use App\Models\DetilPembelianModel;
use App\Models\DetilPenjualanModel;
use CodeIgniter\HTTP\ResponseInterface;

class LaporanController extends BaseController
{
    protected $penjualanModel;
    protected $detilPenjualanModel;
    protected $pembelianModel;
    protected $detilPembelianModel;
    protected $supplierModel;

    public function __construct()
    {
        $this->penjualanModel = new PenjualanModel();
        $this->pembelianModel = new PembelianModel();
        $this->supplierModel = new SupplierModel();
        $this->detilPembelianModel = new DetilPembelianModel();
        $this->detilPenjualanModel = new DetilPenjualanModel();
    }
    public function penjualan()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $startDate   = $this->request->getGet('start_date');
        $endDate     = $this->request->getGet('end_date');
        $statusBayar = $this->request->getGet('status_bayar');

        $today  = date('Y-m-d');
        $errors = [];

        if ($startDate && !\DateTime::createFromFormat('Y-m-d', $startDate)) {
            $errors[] = 'Format tanggal mulai tidak valid.';
        }

        if ($endDate && !\DateTime::createFromFormat('Y-m-d', $endDate)) {
            $errors[] = 'Format tanggal akhir tidak valid.';
        }


        if ($startDate && $startDate > $today) {
            $errors[] = 'Tanggal mulai tidak boleh melebihi hari ini.';
        }

        if ($endDate && $endDate > $today) {
            $errors[] = 'Tanggal akhir tidak boleh melebihi hari ini.';
        }

        if ($startDate && $endDate && $startDate > $endDate) {
            $errors[] = 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.';
        }

        if ($errors) {
            return redirect()
                ->back()
                ->with('errors', $errors)
                ->withInput();
        }


        $dataBuilder = $this->penjualanModel
            ->select('penjualans.*')
            ->orderBy('created_at', 'DESC');

        $filtered = false;

        if ($startDate) {
            $dataBuilder->where('created_at >=', $startDate . ' 00:00:00');
            $filtered = true;
        }

        if ($endDate) {
            $dataBuilder->where('created_at <=', $endDate . ' 23:59:59');
            $filtered = true;
        }

        if ($statusBayar) {
            $dataBuilder->where('status_bayar', $statusBayar);
            $filtered = true;
        }

        $penjualans = $dataBuilder->paginate(10);
        if ($filtered) {
            session()->setFlashdata('success', 'Berhasil memfilter laporan penjualan.');
        }

        foreach ($penjualans as &$p) {
            $detils = $this->detilPenjualanModel
                ->where('id_penjualan', $p['id'])
                ->findAll();

            $p['total_item']  = 0;
            $p['total_harga'] = 0;

            foreach ($detils as $d) {
                $p['total_item']++;
                $p['total_harga'] += $d['qty'] * $d['harga_jual'];
            }
        }


        $stats = [
            'total_transaksi'  => count($penjualans),
            'transaksi_lunas'  => count(array_filter($penjualans, fn ($p) => $p['status_bayar'] === 'lunas')),
            'total_pendapatan' => array_sum(array_column($penjualans, 'total_harga')),
            'total_item'       => array_sum(array_column($penjualans, 'total_item')),
        ];

        return view('admin/penjualan/laporan', [
            'title'        => 'Laporan Penjualan',
            'penjualans'   => $penjualans,
            'pager'        => $this->penjualanModel->pager,
            'start_date'   => $startDate,
            'end_date'     => $endDate,
            'status_bayar' => $statusBayar,
            'stats'        => $stats,
        ]);
    }

    public function pembelian()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $idSuppl = $this->request->getGet('id_suppl');

        $today = date('Y-m-d');
        $errors = [];

        if ($startDate && !\DateTime::createFromFormat('Y-m-d', $startDate)) {
            $errors[] = 'Format tanggal mulai tidak valid.';
        }

        if ($endDate && !\DateTime::createFromFormat('Y-m-d', $endDate)) {
            $errors[] = 'Format tanggal akhir tidak valid.';
        }

        if ($startDate && $startDate > $today) {
            $errors[] = 'Tanggal mulai tidak boleh melebihi hari ini.';
        }

        if ($endDate && $endDate > $today) {
            $errors[] = 'Tanggal akhir tidak boleh melebihi hari ini.';
        }

        if ($startDate && $endDate && $startDate > $endDate) {
            $errors[] = 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.';
        }

        if ($errors) {
            return redirect()
                ->back()
                ->with('errors', $errors)
                ->withInput();
        }

        $dataBuilder = $this->pembelianModel
            ->select('pembelians.*, suppliers.nama_suppl, users.nama as nama_user')
            ->join('suppliers', 'suppliers.id = pembelians.id_supplier')
            ->join('users', 'users.id = pembelians.id_user')
            ->orderBy('pembelians.created_at', 'DESC');

        $filtered = false;

        if ($startDate) {
            $dataBuilder->where('pembelians.created_at >=', $startDate . ' 00:00:00');
            $filtered = true;
        }

        if ($endDate) {
            $dataBuilder->where('pembelians.created_at <=', $endDate . ' 23:59:59');
            $filtered = true;
        }

        if ($idSuppl) {
            $dataBuilder->where('pembelians.id_supplier', $idSuppl);
            $filtered = true;
        }

        $pembelians = $dataBuilder->paginate(10);

        if ($filtered) {
            session()->setFlashdata('success', 'Berhasil memfilter laporan pembelian.');
        }

        foreach ($pembelians as &$pembelian) {
            $detils = $this->detilPembelianModel
                ->where('id_pembelian', $pembelian['id'])
                ->findAll();

            $pembelian['total_item'] = 0;
            $pembelian['total_harga'] = 0;

            foreach ($detils as $detil) {
                $pembelian['total_item']++;
                $pembelian['total_harga'] += $detil['qty'] * $detil['harga_beli'];
            }
        }

        $stats = [
            'total_transaksi' => count($pembelians),
            'total_pengeluaran' => array_sum(array_column($pembelians, 'total_harga')),
            'total_item' => array_sum(array_column($pembelians, 'total_item')),
            'jumlah_supplier' => count(array_unique(array_column($pembelians, 'id_supplier')))
        ];

        $suppliers = $this->supplierModel
            ->orderBy('nama_suppl', 'ASC')
            ->findAll();

        return view('admin/pembelian/laporan', [
            'title' => 'Laporan Pembelian',
            'pembelians' => $pembelians,
            'pager' => $this->pembelianModel->pager,
            'stats' => $stats,
            'suppliers' => $suppliers,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'id_suppl' => $idSuppl
        ]);
    }

}
