<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table            = 'barangs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'id_kategori',
        'nama_brg',
        'satuan',
        'harga',
        'stok',
        'min_stok',
        'status'
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
        'id'          => 'required|STRING|min_length[3]|max_length[20]|is_unique[barangs.id]',
        'id_kategori' => 'required|is_not_unique[kategoris.id]',
        'nama_brg'    => 'required|min_length[3]|max_length[255]',
        'satuan'      => 'required|min_length[3]|max_length[100]',
        'harga'       => 'required|numeric',
        'stok'        => 'required|decimal',
        'min_stok'    => 'required|decimal',
        'status'      => 'required|in_list[aktif,tidak aktif]',
    ];
    protected $validationMessages   = [];
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
