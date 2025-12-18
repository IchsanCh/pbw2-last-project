<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBarangTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
           'id' => [
               'type'           => 'VARCHAR',
               'constraint'     => 20,
           ],
           'id_kategori' => [
               'type'       => 'INT',
               'constraint' => 12,
                'unsigned'   => true,
           ],
           'nama_brg' => [
               'type'       => 'VARCHAR',
               'constraint' => 255,
           ],
           'satuan' => [
               'type'       => 'VARCHAR',
               'constraint' => 100,
           ],
           'harga' => [
               'type'       => 'INT',
               'constraint' => 12,
           ],
           'stok' => [
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
        $this->forge->addForeignKey('id_kategori', 'kategoris', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('barangs');
    }

    public function down()
    {
        $this->forge->dropTable('barangs');
    }
}
