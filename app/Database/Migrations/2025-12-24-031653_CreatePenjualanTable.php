<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenjualanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'VARCHAR',
                'constraint'     => 20,
            ],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 12,
                'unsigned'   => true,
            ],
            'status_bayar' => [
                'type'       => 'ENUM',
                'constraint' => ['lunas', 'belum lunas', 'dibatalkan'],
            ],
            'nama_pembeli' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'alasan_batal' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_user', 'users', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('penjualans');
    }

    public function down()
    {
        $this->forge->dropTable('penjualans');
    }
}
