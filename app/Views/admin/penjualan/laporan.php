<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>
<?php
$hasFilter =
    !empty($start_date) ||
    !empty($end_date) ||
    !empty($status_bayar);
?>
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold">Laporan Penjualan</h1>
            <p class="text-sm text-gray-600 mt-1">Ringkasan dan riwayat transaksi penjualan</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('success', 'Berhasil', '<?= addslashes(session()->getFlashdata('success')) ?>');
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    showToast('error', 'Validasi Gagal', '<?= addslashes($error) ?>');
                <?php endforeach; ?>
            });
        </script>
    <?php endif; ?>

    <form action="<?= base_url('laporan/penjualan') ?>" method="get" class="mb-6">
        <div class="flex flex-col md:flex-row gap-4 md:items-end">
            <div class="flex-1">
                <label class="label">
                    <span class="label-text">Tanggal Mulai</span>
                </label>
                <input type="date" name="start_date" class="input input-bordered input-primary w-full" value="<?= esc($start_date ?? '') ?>">
            </div>
            <div class="flex-1">
                <label class="label">
                    <span class="label-text">Tanggal Selesai</span>
                </label>
                <input type="date" name="end_date" class="input input-bordered input-primary w-full" value="<?= esc($end_date ?? '') ?>">
            </div>
            <div class="flex-1">
                <label class="label">
                    <span class="label-text">Status Bayar</span>
                </label>
                <select name="status_bayar" class="select select-bordered select-primary w-full">
                    <option value="">Semua</option>
                    <option value="lunas" <?= (isset($status_bayar) && $status_bayar === 'lunas') ? 'selected' : '' ?>>Lunas</option>
                    <option value="belum lunas" <?= (isset($status_bayar) && $status_bayar === 'belum lunas') ? 'selected' : '' ?>>Belum Lunas</option>
                    <option value="dibatalkan" <?= (isset($status_bayar) && $status_bayar === 'dibatalkan') ? 'selected' : '' ?>>Dibatalkan</option>
                </select>
            </div>
            <div class="flex-none flex flex-col-reverse md:flex-row gap-2 w-full md:w-auto">
                <?php if ($hasFilter): ?>
                    <a href="<?= base_url('laporan/penjualan') ?>" class="btn btn-warning w-full md:w-auto">
                        Reset
                    </a>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary w-full md:w-auto">
                    Tampilkan
                </button>
            </div>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="card bg-base-100 shadow p-4">
            <h3 class="font-semibold text-gray-500 text-sm">Total Transaksi</h3>
            <p class="text-xl font-bold"><?= number_format($stats['total_transaksi'] ?? 0) ?></p>
        </div>
        <div class="card bg-base-100 shadow p-4">
            <h3 class="font-semibold text-gray-500 text-sm">Total Pendapatan</h3>
            <p class="text-xl font-bold text-success">Rp <?= number_format($stats['total_pendapatan'] ?? 0, 0, ',', '.') ?></p>
        </div>
        <div class="card bg-base-100 shadow p-4">
            <h3 class="font-semibold text-gray-500 text-sm">Total Item Terjual</h3>
            <p class="text-xl font-bold"><?= number_format($stats['total_item'] ?? 0) ?> item</p>
        </div>
        <div class="card bg-base-100 shadow p-4">
            <h3 class="font-semibold text-gray-500 text-sm">Transaksi Lunas</h3>
            <p class="text-xl font-bold"><?= number_format($stats['transaksi_lunas'] ?? 0) ?></p>
        </div>
    </div>

    <div class="card bg-base-100 shadow-xl">
        <div class="card-body overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th class="min-w-[40px]">No</th>
                        <th class="min-w-[180px]">ID Penjualan</th>
                        <th class="min-w-[130px]">Tanggal</th>
                        <th class="min-w-[150px]">Status Bayar</th>
                        <th class="min-w-[150px]">Nama Pembeli</th>
                        <th class="min-w-[130px]">Total Item</th>
                        <th class="min-w-[160px]">Total Harga</th>
                        <th class="min-w-[50px]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($penjualans)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500 italic">
                                Belum ada data transaksi
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1 + (10 * ($pager->getCurrentPage() - 1)); ?>
                        <?php foreach ($penjualans as $penjualan): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="font-mono font-semibold"><?= esc($penjualan['id']) ?></td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="font-medium"><?= date('d M Y', strtotime($penjualan['created_at'])) ?></span>
                                        <span class="text-xs text-gray-500"><?= date('H:i', strtotime($penjualan['created_at'])) ?> WIB</span>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($penjualan['status_bayar'] === 'lunas'): ?>
                                        <span class="badge badge-success">Lunas</span>
                                    <?php elseif ($penjualan['status_bayar'] === 'belum lunas'): ?>
                                        <span class="badge badge-warning">Belum Lunas</span>
                                    <?php else: ?>
                                        <span class="badge badge-error">Dibatalkan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= !empty($penjualan['nama_pembeli']) ? esc($penjualan['nama_pembeli']) : '<span class="italic text-gray-400">Pembeli Umum</span>' ?>
                                </td>
                                <td><?= $penjualan['total_item'] ?> item</td>
                                <td class="font-semibold text-primary">Rp <?= number_format($penjualan['total_harga'], 0, ',', '.') ?></td>
                                <td>
                                    <a href="<?= base_url('riwayat/detail/' . $penjualan['id']) ?>" class="btn btn-sm btn-info gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if (!empty($penjualans)): ?>
                <div class="flex justify-center mt-6">
                    <?= $pager->links('default', 'pagination') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
