<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'     => 'Pemilik Toko',
                'username' => 'pemilik',
                'password' => password_hash('pemilik123', PASSWORD_DEFAULT),
                'role'     => 'pemilik',
                'status'   => 'aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'     => 'Kasir Toko',
                'username' => 'kasir',
                'password' => password_hash('kasir123', PASSWORD_DEFAULT),
                'role'     => 'kasir',
                'status'   => 'aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];
        $this->db->table('users')->insertBatch($data);
    }
}
