<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePembelianTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
           'id' => [
               'type'           => 'VARCHAR',
               'constraint'     => 15,
           ],
           'id_supplier' => [
               'type'       => 'INT',
               'constraint' => 12,
                'unsigned'   => true,
           ],
           'id_user' => [
                'type'       => 'INT',
                'constraint' => 12,
                'unsigned'   => true,
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
        $this->forge->addForeignKey('id_supplier', 'suppliers', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('id_user', 'users', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('pembelians');
    }

    public function down()
    {
        $this->forge->dropTable('pembelians');
    }
}
