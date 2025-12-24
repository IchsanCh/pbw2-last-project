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
    private function generateIdPembelian()
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
        //
    }
    public function create()
    {
        //
    }
}
