<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTriggerPenjualan extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE TRIGGER trg_after_insert_detil_penjualan
            AFTER INSERT ON detil_penjualans
            FOR EACH ROW
            BEGIN
                DECLARE v_status VARCHAR(20);

                SELECT status_bayar INTO v_status
                FROM penjualans
                WHERE id = NEW.id_penjualan;

                IF v_status IN ('lunas','belum lunas') THEN
                    UPDATE barangs
                    SET stok = stok - NEW.qty
                    WHERE id = NEW.id_barang;
                END IF;
            END
        ");
    }

    public function down()
    {
        $this->db->query("DROP TRIGGER IF EXISTS trg_after_insert_detil_penjualan");
    }
}
