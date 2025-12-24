<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTriggerPembelianInsert extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE TRIGGER trg_after_insert_detil_pembelian
            AFTER INSERT ON detil_pembelians
            FOR EACH ROW
            BEGIN
                UPDATE barangs
                SET stok = stok + NEW.qty
                WHERE id = NEW.id_barang;
            END
        ");
    }

    public function down()
    {
        $this->db->query("DROP TRIGGER IF EXISTS trg_after_insert_detil_pembelian");
    }
}
