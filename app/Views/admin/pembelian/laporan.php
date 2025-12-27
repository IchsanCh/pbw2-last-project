<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>
<?php
$hasFilter =
    !empty($start_date) ||
    !empty($end_date) ||
    !empty($id_suppl);
?>
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold">Laporan Pembelian</h1>
            <p class="text-sm text-gray-600 mt-1">Ringkasan dan riwayat transaksi pembelian dari supplier</p>
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

    <form action="<?= base_url('laporan/pembelian') ?>" method="get" class="mb-6">
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
                    <span class="label-text">Supplier</span>
                </label>
                <select name="id_suppl" class="select select-bordered select-primary w-full">
                    <option value="">Semua Supplier</option>
                    <?php if (!empty($suppliers)): ?>
                        <?php foreach ($suppliers as $supplier): ?>
                            <option value="<?= esc($supplier['id']) ?>" <?= (isset($id_suppl) && $id_suppl == $supplier['id']) ? 'selected' : '' ?>>
                                <?= esc($supplier['nama_suppl']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="flex-none flex flex-col-reverse md:flex-row gap-2 w-full md:w-auto">
                <?php if ($hasFilter): ?>
                    <a href="<?= base_url('laporan/pembelian') ?>" class="btn btn-warning w-full md:w-auto">
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
            <h3 class="font-semibold text-gray-500 text-sm">Total Pengeluaran</h3>
            <p class="text-xl font-bold text-error">Rp <?= number_format($stats['total_pengeluaran'] ?? 0, 0, ',', '.') ?></p>
        </div>
        <div class="card bg-base-100 shadow p-4">
            <h3 class="font-semibold text-gray-500 text-sm">Total Item Dibeli</h3>
            <p class="text-xl font-bold"><?= number_format($stats['total_item'] ?? 0) ?> item</p>
        </div>
        <div class="card bg-base-100 shadow p-4">
            <h3 class="font-semibold text-gray-500 text-sm">Jumlah Supplier</h3>
            <p class="text-xl font-bold"><?= number_format($stats['jumlah_supplier'] ?? 0) ?></p>
        </div>
    </div>

    <div class="card bg-base-100 shadow-xl">
        <div class="card-body overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th class="min-w-[40px]">No</th>
                        <th class="min-w-[180px]">ID Pembelian</th>
                        <th class="min-w-[130px]">Tanggal</th>
                        <th class="min-w-[150px]">Supplier</th>
                        <th class="min-w-[150px]">Pegawai</th>
                        <th class="min-w-[130px]">Total Item</th>
                        <th class="min-w-[160px]">Total Harga</th>
                        <th class="min-w-[50px]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pembelians)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500 italic">
                                Belum ada data transaksi pembelian
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1 + (10 * ($pager->getCurrentPage() - 1)); ?>
                        <?php foreach ($pembelians as $pembelian): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="font-mono font-semibold"><?= esc($pembelian['id']) ?></td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="font-medium"><?= date('d M Y', strtotime($pembelian['created_at'])) ?></span>
                                        <span class="text-xs text-gray-500"><?= date('H:i', strtotime($pembelian['created_at'])) ?> WIB</span>
                                    </div>
                                </td>
                                <td><?= esc($pembelian['nama_suppl']) ?></td>
                                <td><?= esc($pembelian['nama_user']) ?></td>
                                <td>
                                    <span class="badge badge-info"><?= $pembelian['total_item'] ?> item</span>
                                </td>
                                <td class="font-semibold text-error">Rp <?= number_format($pembelian['total_harga'], 0, ',', '.') ?></td>
                                <td>
                                    <a href="<?= base_url('pembelian/detail/' . $pembelian['id']) ?>" class="btn btn-sm btn-info gap-2">
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

            <?php if (!empty($pembelians)): ?>
                <div class="flex justify-center mt-6">
                    <?= $pager->links('default', 'pagination') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>