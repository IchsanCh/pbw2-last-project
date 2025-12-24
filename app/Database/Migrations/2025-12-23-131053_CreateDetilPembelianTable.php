<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetilPembelianTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
               'type'           => 'INT',
               'constraint'     => 12,
               'unsigned'   => true,
               'auto_increment' => true,
            ],
            'id_pembelian' => [
               'type'       => 'VARCHAR',
               'constraint' => 15,
            ],
            'id_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'qty' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'harga_beli' => [
                'type'       => 'INT',
                'constraint' => 12,
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
        $this->forge->addForeignKey('id_pembelian', 'pembelians', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('id_barang', 'barangs', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addKey('id_pembelian');
        $this->forge->addKey('id_barang');
        $this->forge->createTable('detil_pembelians');
    }

    public function down()
    {
        $this->forge->dropTable('detil_pembelians');
    }
}
