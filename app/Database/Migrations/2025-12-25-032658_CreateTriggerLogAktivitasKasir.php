<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTriggerLogAktivitasKasir extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE TRIGGER trg_log_kasir_after_insert_detil
            AFTER INSERT ON detil_penjualans
            FOR EACH ROW
            BEGIN
                DECLARE total_nominal INT;
                DECLARE v_user INT;
                DECLARE v_status VARCHAR(20);

                SELECT id_user, status_bayar
                INTO v_user, v_status
                FROM penjualans
                WHERE id = NEW.id_penjualan
                LIMIT 1;

                IF v_status IN ('lunas','belum lunas') THEN

                    SELECT IFNULL(SUM(qty * harga_jual), 0)
                    INTO total_nominal
                    FROM detil_penjualans
                    WHERE id_penjualan = NEW.id_penjualan;

                    IF EXISTS (
                        SELECT 1 FROM log_aktivitas_kasir
                        WHERE id_penjualan = NEW.id_penjualan
                    ) THEN
                        UPDATE log_aktivitas_kasir
                        SET
                            nominal = total_nominal,
                            aktivitas = CONCAT(
                                'Melakukan transaksi senilai Rp ',
                                FORMAT(total_nominal, 0)
                            ),
                            created_at = NOW()
                        WHERE id_penjualan = NEW.id_penjualan;
                    ELSE
                        INSERT INTO log_aktivitas_kasir (
                            id_penjualan,
                            id_user,
                            aktivitas,
                            nominal,
                            created_at
                        ) VALUES (
                            NEW.id_penjualan,
                            v_user,
                            CONCAT(
                                'Melakukan transaksi senilai Rp ',
                                FORMAT(total_nominal, 0)
                            ),
                            total_nominal,
                            NOW()
                        );
                    END IF;
                END IF;
            END
            ");
    }

    public function down()
    {
        $this->db->query("DROP TRIGGER IF EXISTS trg_log_kasir_after_insert");
    }
}
