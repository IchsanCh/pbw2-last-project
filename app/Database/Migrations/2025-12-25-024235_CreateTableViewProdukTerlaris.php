<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableViewProdukTerlaris extends Migration
{
    public function up()
    {
        $this->db->query("
            CREATE OR REPLACE VIEW view_produk_terlaris_bulan_ini AS
            SELECT
                b.id          AS barang_id,
                b.nama_brg    AS produk,
                SUM(d.qty)    AS terjual,
                SUM(d.qty * d.harga_jual) AS pendapatan
            FROM penjualans p
            JOIN detil_penjualans d ON d.id_penjualan = p.id
            JOIN barangs b ON b.id = d.id_barang
            WHERE
                p.status_bayar IN ('lunas','belum lunas')
                AND MONTH(p.created_at) = MONTH(CURDATE())
                AND YEAR(p.created_at) = YEAR(CURDATE())
            GROUP BY b.id, b.nama_brg
            ORDER BY terjual DESC;
        ");
    }

    public function down()
    {
        //
    }
}
