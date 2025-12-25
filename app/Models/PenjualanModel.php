<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table            = 'penjualans';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'id_user',
        'status_bayar',
        'nama_pembeli',
        'alasan_batal',
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
        'id'            => 'required|max_length[20]',
        'id_user'       => 'required|integer',
        'status_bayar'  => 'required|in_list[lunas,belum lunas,dibatalkan]',
        'nama_pembeli'  => 'permit_empty|max_length[100]',
        'alasan_batal'  => 'permit_empty',
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
    public function getRiwayatByUser($userId, $search = null)
    {
        $builder = $this->select('penjualans.*, users.nama as nama_user')
            ->join('users', 'users.id = penjualans.id_user')
            ->where('penjualans.id_user', $userId)
            ->orderBy('penjualans.created_at', 'DESC');

        if ($search) {
            $builder->like('penjualans.id', $search);
        }

        return $builder;
    }
}
