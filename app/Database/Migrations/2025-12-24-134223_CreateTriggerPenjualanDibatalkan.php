<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTriggerPenjualanDibatalkan extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE TRIGGER trg_after_update_penjualan
            AFTER UPDATE ON penjualans
            FOR EACH ROW
            BEGIN
                IF OLD.status_bayar IN ('lunas','belum lunas')
                AND NEW.status_bayar = 'dibatalkan' THEN

                    UPDATE barangs b
                    JOIN detil_penjualans dp ON dp.id_barang = b.id
                    SET b.stok = b.stok + dp.qty
                    WHERE dp.id_penjualan = NEW.id;

                ELSEIF OLD.status_bayar = 'dibatalkan'
                AND NEW.status_bayar IN ('lunas','belum lunas') THEN

                    UPDATE barangs b
                    JOIN detil_penjualans dp ON dp.id_barang = b.id
                    SET b.stok = b.stok - dp.qty
                    WHERE dp.id_penjualan = NEW.id;

                END IF;
            END
        ");
    }

    public function down()
    {
        $this->db->query("DROP TRIGGER IF EXISTS trg_after_update_penjualan");
    }
}
