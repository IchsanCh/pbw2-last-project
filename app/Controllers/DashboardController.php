<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Models\SupplierModel;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    protected $barangModel;
    protected $supplierModel;

    public function __construct()
    {
        $this->barangModel   = new BarangModel();
        $this->supplierModel = new SupplierModel();
    }
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        $totalBarang = $this->barangModel->countAllResults();
        $stokMenipis = $this->barangModel
            ->where('stok <=', 'min_stok', false)
            ->countAllResults();
        $totalSupplier = $this->supplierModel
            ->where('status', 'aktif')
            ->countAllResults();
        return view('admin/dashboard', [
            'title' => 'Dashboard',
            'totalBarang'    => $totalBarang,
            'stokMenipis'    => $stokMenipis,
            'totalSupplier'  => $totalSupplier,
        ]);
    }
}
