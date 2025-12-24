<?php

namespace App\Models;

use CodeIgniter\Model;

class DetilPembelianModel extends Model
{
    protected $table            = 'detil_pembelians';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_pembelian',
        'id_barang',
        'qty',
        'harga_beli',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_pembelian' => 'required|string|max_length[15]|is_not_unique[pembelians.id]',
        'id_barang'    => 'required|string|max_length[20]|is_not_unique[barangs.id]',
        'qty'          => 'required|decimal',
        'harga_beli'   => 'required|integer'
    ];
    protected $validationMessages   = [
        'id_pembelian' => [
            'required' => 'ID pembelian harus diisi.',
            'is_not_unique' => 'ID pembelian tidak valid.'
        ],
        'id_barang' => [
            'required' => 'Barang harus dipilih.',
            'is_not_unique' => 'Barang tidak valid.'
        ],
        'qty' => [
            'required' => 'Quantity harus diisi.',
            'decimal' => 'Quantity harus berupa angka.'
        ],
        'harga_beli' => [
            'required' => 'Harga beli harus diisi.',
            'integer' => 'Harga beli harus berupa angka.'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
