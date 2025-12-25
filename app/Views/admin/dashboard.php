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
                    <div class="stat-value text-secondary"><?= $totalTransaksi ?></div>
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
            <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-200">
                <div class="card-body">
                    <h2 class="card-title">Penjualan 7 Hari Terakhir</h2>
                    <canvas id="chart"></canvas>
                </div>
            </div>
            <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-200">
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
                                <?php if (!empty($produkTerlaris)): ?>
                                    <?php foreach ($produkTerlaris as $item): ?>
                                        <tr>
                                            <td><?= esc($item['produk']) ?></td>

                                            <td>
                                                <span class="badge badge-primary">
                                                    <?= number_format($item['terjual']) ?>
                                                </span>
                                            </td>

                                            <td class="font-semibold">
                                                Rp <?= number_format($item['pendapatan'], 0, ',', '.') ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-gray-400">
                                            Belum ada penjualan bulan ini
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-200">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="card-title">Stok Menipis</h2>
                        <span class="badge badge-warning"><?= $stokMenipis ?> Barang</span>
                    </div>
                    <div class="space-y-3">
                        <?php if (count($barangHabis) === 0): ?>
                            <p class="text-center text-base-content/60 font-semibold">
                                Tidak ada barang dengan stok menipis.
                            </p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($barangHabis as $barang): ?>
                                <div class="alert alert-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>

                                    <div class="flex-1">
                                        <div class="font-bold"><?= $barang['nama_brg']; ?></div>
                                        <div class="text-sm">
                                            Stok: <?= $barang['stok']; ?> <?= $barang['satuan']; ?>
                                            (Min: <?= $barang['min_stok']; ?>)
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                       
                    </div>
                        <div class="card-actions justify-end mt-4">
                            <a href="<?= base_url('barang') ?>" class="btn btn-sm btn-primary">Lihat Semua</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-200">
                <div class="card-body">
                    <h2 class="card-title">Aktivitas Terakhir</h2>
                    <div class="space-y-4">
                        <?php if (!empty($logKasir)): ?>
                            <?php foreach ($logKasir as $log): ?>
                                <div>
                                    <div class="flex gap-3">
                                        <div class="avatar placeholder hidden md:block">
                                            <div class="bg-primary text-primary-content rounded-full w-10">
                                                <span class="text-xs">
                                                    <?= esc(
                                                        initial_nama(
                                                            $log['nama'] ?: $log['username']
                                                        )
                                                    ) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold">
                                                <?= esc($log['nama']) ?> (<?= esc($log['username']) ?>)
                                            </p>
                                            <p class="text-sm text-base-content/80 font-semibold">
                                                Melakukan transaksi senilai Rp <?= number_format($log['nominal'], 0, ',', '.') ?>
                                            </p>
                                            <p class="text-xs text-base-content/70 font-semibold">
                                                <?= date('j F Y, H:i', strtotime($log['created_at'])) ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="divider my-0"></div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center text-base-content/60 font-semibold">
                                Belum ada aktivitas kasir.
                            </p>
                        <?php endif; ?>
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
                    <div class="stat-value text-primary"><?= $totalTransaksiDilakukan ?></div>
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
                    <div class="stat-value text-secondary">Rp <?= number_format($hasilPendapatan, 0, "", "."); ?></div>
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
                    <div class="stat-value text-accent"><?= number_format($rataRataTransaksi, 0, "", "."); ?></div>
                    <div class="stat-desc text-black font-semibold">Per transaksi</div>
                </div>
            </div>
        </div>
        <div class="card bg-base-100 shadow-xl mb-6 hover:shadow-2xl transition-all duration-200">
            <div class="card-body">
                <h2 class="card-title mb-4">Menu Cepat</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="<?= base_url('penjualan/create') ?>" class="btn btn-primary btn-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden md:block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Transaksi Baru
                    </a>
                    <a href="<?= base_url('riwayat') ?>" class="btn btn-secondary btn-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden md:block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                <th class="min-w-[160px]">No Penjualan</th>
                                <th class="min-w-[160px]">Waktu</th>
                                <th class="min-w-[150px]">Total</th>
                                <th class="min-w-[140px]">Status</th>
                            </tr>
                        </thead>
                        
                        <tbody>
    <?php if (!empty($transaksiTerakhir)) : ?>
        <?php foreach ($transaksiTerakhir as $tr) : ?>
            <tr>
                <td class="font-mono"><?= $tr['no_penjualan']; ?></td>
                <td><?= date($tr['waktu']); ?></td>
                <td class="font-semibold">
                    Rp <?= number_format($tr['total_harga'], 0, ',', '.'); ?>
                </td>
                <td>
                    <?php
            $badgeClass = 'badge-secondary';
            $statusLabel = ucfirst($tr['status']);

            if ($tr['status'] === 'lunas') {
                $badgeClass = 'badge-success';
            } elseif ($tr['status'] === 'dibatalkan') {
                $badgeClass = 'badge-error';
            } elseif ($tr['status'] === 'belum lunas') {
                $badgeClass = 'badge-warning';
            }
            ?>
                                        <span class="badge font-semibold <?= $badgeClass; ?>"><?= $statusLabel; ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center">Belum ada transaksi hari ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php if ($role === 'pemilik'): ?>
<script>
    const labels = <?= $labels ?? '[]' ?>;
    const dataPenjualan = <?= $data ?? '[]' ?>;

    const ctx = document.getElementById('chart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Penjualan',
                    data: dataPenjualan,
                    tension: 0.2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
</script>
<?php endif; ?>
<?= $this->endSection() ?>