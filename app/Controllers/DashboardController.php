<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\SupplierModel;
use App\Models\PenjualanModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    protected $barangModel;
    protected $supplierModel;
    protected $penjualanModel;

    public function __construct()
    {
        $this->penjualanModel = new PenjualanModel();
        $this->barangModel   = new BarangModel();
        $this->supplierModel = new SupplierModel();
    }
    public function index()
    {
        $db = \Config\Database::connect();
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd   = date('Y-m-d 23:59:59');

        $totalTransaksi = $this->penjualanModel
            ->builder()
            ->where('created_at >=', $todayStart)
            ->where('created_at <=', $todayEnd)
            ->countAllResults();

        $totalBarang = $this->barangModel->where('status', 'aktif')->countAllResults();

        $stokMenipis = $this->barangModel
            ->where('status', 'aktif')
            ->where('stok <=', 'min_stok', false)
            ->countAllResults();

        $barangHabis = $this->barangModel
            ->where('status', 'aktif')
            ->where('stok <=', 'min_stok', false)
            ->orderBy('stok', 'ASC')
            ->findAll(3);

        $totalSupplier = $this->supplierModel
            ->where('status', 'aktif')
            ->countAllResults();

        $rows = $this->penjualanModel
            ->select("DATE(created_at) as tanggal, COUNT(*) as total")
            ->where('created_at >=', date('Y-m-d', strtotime('-6 days')))
            ->groupBy('DATE(created_at)')
            ->orderBy('tanggal', 'ASC')
            ->findAll();

        $range = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $range[$date] = 0;
        }

        foreach ($rows as $row) {
            $range[$row['tanggal']] = (int) $row['total'];
        }

        $labels = [];
        $data   = [];
        foreach ($range as $date => $total) {
            $labels[] = date('d/m', strtotime($date));
            $data[]   = $total;
        }
        $produkTerlaris = $db
            ->table('view_produk_terlaris_bulan_ini')
            ->limit(5)
            ->get()
            ->getResultArray();

        $logKasir = $db
            ->table('log_aktivitas_kasir lak')
            ->join('users u', 'lak.id_user = u.id')
            ->select([
                'lak.id',
                'lak.id_penjualan',
                'lak.aktivitas',
                'lak.nominal',
                'lak.created_at',
                'u.nama',
                'u.username'
            ])
            ->orderBy('lak.created_at', 'DESC')
            ->limit(4)
            ->get()
            ->getResultArray();

        //kasir
        $totalTransaksiDilakukan = $this->penjualanModel
            ->builder()
            ->where('id_user', session()->get('user_id'))
            ->where('created_at >=', $todayStart)
            ->where('created_at <=', $todayEnd)
            ->countAllResults();

        $totalPendapatan = $this->penjualanModel->builder()
            ->select('COALESCE(SUM(detil_penjualans.qty * detil_penjualans.harga_jual), 0) as total', false)
            ->join('detil_penjualans', 'detil_penjualans.id_penjualan = penjualans.id')
            ->where('penjualans.id_user', session()->get('user_id'))
            ->where('DATE(penjualans.created_at)', date('Y-m-d'))
            ->whereIn('penjualans.status_bayar', ['lunas', 'belum lunas'])
            ->get()
            ->getRow();

        $hasilPendapatan = ($totalPendapatan) ? $totalPendapatan->total : 0;

        $jumlahTransaksi = $this->penjualanModel->builder()
            ->select('COUNT(DISTINCT penjualans.id) as jumlah', false)
            ->join('detil_penjualans', 'detil_penjualans.id_penjualan = penjualans.id')
            ->where('penjualans.id_user', session()->get('user_id'))
            ->where('DATE(penjualans.created_at)', date('Y-m-d'))
            ->whereIn('penjualans.status_bayar', ['lunas', 'belum lunas'])
            ->get()
            ->getRow();

        $totalTransaksis = ($jumlahTransaksi) ? $jumlahTransaksi->jumlah : 0;
        $rataRataTransaksi = ($totalTransaksis > 0) ? $hasilPendapatan / $totalTransaksis : 0;
        $transaksiTerakhir = $this->penjualanModel->builder()
            ->select('
        penjualans.id as no_penjualan, 
        penjualans.created_at as waktu, 
        penjualans.status_bayar as status,
        COALESCE(SUM(detil_penjualans.qty * detil_penjualans.harga_jual), 0) as total_harga', false)
            ->join('detil_penjualans', 'detil_penjualans.id_penjualan = penjualans.id', 'left')
            ->where('penjualans.id_user', session()->get('user_id'))
            ->groupBy('penjualans.id')
            ->orderBy('penjualans.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return view('admin/dashboard', [
            'title' => 'Dashboard',
            'totalBarang'    => $totalBarang,
            'stokMenipis'    => $stokMenipis,
            'totalSupplier'  => $totalSupplier,
            'totalTransaksi' => $totalTransaksi,
            'barangHabis'    => $barangHabis,
            'labels' => json_encode($labels),
            'data'   => json_encode($data),
            'produkTerlaris' => $produkTerlaris,
            'logKasir'       => $logKasir,
            'totalTransaksiDilakukan' => $totalTransaksiDilakukan,
            'hasilPendapatan' => $hasilPendapatan,
            'rataRataTransaksi' => $rataRataTransaksi,
            'transaksiTerakhir' => $transaksiTerakhir
        ]);
    }
}
