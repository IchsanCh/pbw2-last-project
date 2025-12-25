<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableLogAktivitasKasir extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE TABLE log_aktivitas_kasir (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_penjualan VARCHAR(20) NOT NULL,
                id_user INT UNSIGNED NOT NULL,
                aktivitas VARCHAR(255) NOT NULL,
                nominal INT(12) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (id_user) REFERENCES users(id),
                FOREIGN KEY (id_penjualan) REFERENCES penjualans(id)
            )
        ");
    }

    public function down()
    {
        $this->db->query("DROP TABLE IF EXISTS log_aktivitas_kasir");
    }
}
