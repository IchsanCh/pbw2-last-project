<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTriggerPembelianDelete extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE TRIGGER trg_after_delete_detil_pembelian
            AFTER DELETE ON detil_pembelians
            FOR EACH ROW
            BEGIN
                UPDATE barangs
                SET stok = stok - OLD.qty
                WHERE id = OLD.id_barang;
            END
        ");
    }

    public function down()
    {
        $this->db->query("DROP TRIGGER IF EXISTS trg_after_delete_detil_pembelian");
    }
}
