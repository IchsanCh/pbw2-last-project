<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>
<?php
$role = session('role');
$username = session('username');
?>
    <div class="p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Dashboard</h1>
        <p class="text-base-content/80 mt-1 font-semibold">Selamat datang, <?= esc(ucwords($username)) ?>!</p>
    </div>

    <?php if ($role === 'pemilik'): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <div class="stats shadow-md hover:shadow-xl transition-all duration-200">
                <div class="stat">
                    <div class="stat-figure text-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="stat-title text-black font-semibold">Total Transaksi</div>
                    <div class="stat-value text-secondary">47</div>
                    <div class="stat-desc text-black font-semibold">Transaksi hari ini</div>
                </div>
            </div>
            <div class="stats shadow-md hover:shadow-xl transition-all duration-200">
                <div class="stat">
                    <div class="stat-figure text-accent">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div class="stat-title text-black font-semibold">Total Barang</div>
                    <div class="stat-value text-accent"><?= $totalBarang ?></div>
                    <div class="stat-desc text-black font-semibold"><?= $stokMenipis ?> barang stok menipis</div>
                </div>
            </div>
            <div class="stats shadow-md hover:shadow-xl transition-all duration-200">
                <div class="stat">
                    <div class="stat-figure text-info">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="stat-title font-semibold text-black">Total Supplier</div>
                    <div class="stat-value text-info"><?= $totalSupplier ?></div>
                    <div class="stat-desc text-black font-semibold">Supplier aktif</div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Penjualan 7 Hari Terakhir</h2>
                    <canvas id="chart"></canvas>
                </div>
            </div>
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Produk Terlaris Bulan Ini</h2>
                    <div class="overflow-x-auto">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Terjual</th>
                                    <th>Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Beras Premium 5kg</td>
                                    <td><span class="badge badge-primary">234</span></td>
                                    <td class="font-semibold">Rp 14.8jt</td>
                                </tr>
                                <tr>
                                    <td>Minyak Goreng 2L</td>
                                    <td><span class="badge badge-primary">189</span></td>
                                    <td class="font-semibold">Rp 6.8jt</td>
                                </tr>
                                <tr>
                                    <td>Gula Pasir 1kg</td>
                                    <td><span class="badge badge-primary">167</span></td>
                                    <td class="font-semibold">Rp 3.2jt</td>
                                </tr>
                                <tr>
                                    <td>Telur Ayam 1kg</td>
                                    <td><span class="badge badge-primary">142</span></td>
                                    <td class="font-semibold">Rp 4.1jt</td>
                                </tr>
                                <tr>
                                    <td>Tepung Terigu 1kg</td>
                                    <td><span class="badge badge-primary">128</span></td>
                                    <td class="font-semibold">Rp 1.9jt</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="card-title">Stok Menipis</h2>
                        <span class="badge badge-warning">8 Produk</span>
                    </div>
                    <div class="space-y-3">
                        <div class="alert alert-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="flex-1">
                                <div class="font-bold">Kecap Manis 600ml</div>
                                <div class="text-sm">Stok: 3 botol (Min: 10)</div>
                            </div>
                        </div>
                        <div class="alert alert-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="flex-1">
                                <div class="font-bold">Sabun Cuci Piring</div>
                                <div class="text-sm">Stok: 5 pcs (Min: 15)</div>
                            </div>
                        </div>
                        <div class="alert alert-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="flex-1">
                                <div class="font-bold">Susu Kental Manis</div>
                                <div class="text-sm">Stok: 7 kaleng (Min: 20)</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-actions justify-end mt-4">
                        <a href="<?= base_url('produk') ?>" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                </div>
            </div>
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Aktivitas Terakhir</h2>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="avatar placeholder">
                                <div class="bg-primary text-primary-content rounded-full w-10">
                                    <span class="text-xs">JD</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold">John (Kasir)</p>
                                <p class="text-sm text-base-content/60">Melakukan transaksi senilai Rp 127.500</p>
                                <p class="text-xs text-base-content/40">5 menit yang lalu</p>
                            </div>
                        </div>
                        <div class="divider my-0"></div>
                        <div class="flex gap-3">
                            <div class="avatar placeholder">
                                <div class="bg-secondary text-secondary-content rounded-full w-10">
                                    <span class="text-xs">AS</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold">Admin</p>
                                <p class="text-sm text-base-content/60">Menambah stok Beras Premium 5kg (50 pcs)</p>
                                <p class="text-xs text-base-content/40">1 jam yang lalu</p>
                            </div>
                        </div>
                        <div class="divider my-0"></div>
                        <div class="flex gap-3">
                            <div class="avatar placeholder">
                                <div class="bg-accent text-accent-content rounded-full w-10">
                                    <span class="text-xs">SR</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold">Sarah (Kasir)</p>
                                <p class="text-sm text-base-content/60">Melakukan transaksi senilai Rp 85.000</p>
                                <p class="text-xs text-base-content/40">2 jam yang lalu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif ($role === 'kasir'): ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="stats shadow-md hover:shadow-xl transition-all duration-200">
                <div class="stat">
                    <div class="stat-figure text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="stat-title font-semibold text-black">Transaksi Saya</div>
                    <div class="stat-value text-primary">23</div>
                    <div class="stat-desc text-black font-semibold">Hari ini <?= date('d-m-Y') ?></div>
                </div>
            </div>
            <div class="stats shadow-md hover:shadow-xl transition-all duration-200">
                <div class="stat">
                    <div class="stat-figure text-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="stat-title text-black font-semibold">Total Penjualan</div>
                    <div class="stat-value text-secondary">Rp 1.2jt</div>
                    <div class="stat-desc text-black font-semibold">Hari ini <?= date('d-m-Y') ?></div>
                </div>
            </div>
            <div class="stats shadow-md hover:shadow-xl transition-all duration-200">
                <div class="stat">
                    <div class="stat-figure text-accent">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div class="stat-title text-black font-semibold">Rata-rata</div>
                    <div class="stat-value text-accent">52k</div>
                    <div class="stat-desc text-black font-semibold">Per transaksi</div>
                </div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-xl mb-6">
            <div class="card-body">
                <h2 class="card-title mb-4">Menu Cepat</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="<?= base_url('transaksi') ?>" class="btn btn-primary btn-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Transaksi Baru
                    </a>
                    <a href="<?= base_url('riwayat') ?>" class="btn btn-secondary btn-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Riwayat Transaksi
                    </a>
                </div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">Transaksi Terakhir Saya</h2>
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Invoice</th>
                                <th>Waktu</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-mono">INV-20241221-047</td>
                                <td>14:32</td>
                                <td class="font-semibold">Rp 127.500</td>
                                <td><span class="badge badge-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td class="font-mono">INV-20241221-046</td>
                                <td>14:15</td>
                                <td class="font-semibold">Rp 85.000</td>
                                <td><span class="badge badge-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td class="font-mono">INV-20241221-045</td>
                                <td>13:58</td>
                                <td class="font-semibold">Rp 234.500</td>
                                <td><span class="badge badge-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td class="font-mono">INV-20241221-044</td>
                                <td>13:42</td>
                                <td class="font-semibold">Rp 67.000</td>
                                <td><span class="badge badge-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td class="font-mono">INV-20241221-043</td>
                                <td>13:28</td>
                                <td class="font-semibold">Rp 156.000</td>
                                <td><span class="badge badge-success">Selesai</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<script>
new Chart(document.getElementById('chart'), {
    type: 'line',
    data: {
        labels: ['11/11', '12/11', '13/11', '14/11', '15/11', '16/11', '17/11'],
        datasets: [{
            label: 'Penjualan',
            data: [80, 90, 75, 100, 95, 110, 130],
            tension: 0.2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

</script>
<?= $this->endSection() ?>