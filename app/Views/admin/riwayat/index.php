<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold">Riwayat Transaksi</h1>
            <p class="text-sm text-gray-600 mt-1">Riwayat transaksi penjualan yang Anda lakukan</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <div class="card bg-base-100 shadow-xl">
        <div class="card-body overflow-x-auto">
            <form action="<?= base_url('riwayat') ?>" method="get" class="mb-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <div class="join w-full">
                            <input 
                                type="text"
                                name="search"
                                placeholder="Cari berdasarkan ID Penjualan..."
                                class="input input-bordered input-primary join-item flex-1"
                                value="<?= esc($search ?? '') ?>"
                            />

                            <button type="submit" class="btn btn-primary join-item">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <?php if (!empty($search)): ?>
                                <a href="<?= base_url('riwayat') ?>" class="btn btn-warning join-item">
                                    Reset
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th class="min-w-[30px]">No</th>
                            <th class="min-w-[200px]">ID Penjualan</th>
                            <th class="min-w-[130px]">Tanggal</th>
                            <th class="min-w-[150px]">Status Bayar</th>
                            <th class="min-w-[150px]">Nama Pembeli</th>
                            <th class="min-w-[130px]">Total Item</th>
                            <th class="min-w-[150px]">Total Harga</th>
                            <th class="text-center min-w-[130px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($penjualans)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-8">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <span class="text-gray-500">Belum ada transaksi</span>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php
                            $no = 1 + (10 * ($pager->getCurrentPage() - 1));
                            foreach ($penjualans as $penjualan):
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <span class="font-mono font-semibold"><?= esc($penjualan['id']) ?></span>
                                    </td>
                                    <td>
                                        <div class="flex flex-col">
                                            <span class="font-medium"><?= date('d M Y', strtotime($penjualan['created_at'])) ?></span>
                                            <span class="text-xs text-gray-500"><?= date('H:i', strtotime($penjualan['created_at'])) ?> WIB</span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($penjualan['status_bayar'] === 'lunas'): ?>
                                            <span class="badge badge-success gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                                Lunas
                                            </span>
                                        <?php elseif ($penjualan['status_bayar'] === 'belum lunas'): ?>
                                            <span class="badge badge-warning gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                </svg>
                                                Belum Lunas
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-error gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                                Dibatalkan
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($penjualan['nama_pembeli'])): ?>
                                            <span class="font-medium"><?= esc($penjualan['nama_pembeli']) ?></span>
                                        <?php else: ?>
                                            <span class="text-gray-400 italic">Pembeli Umum</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-outline"><?= $penjualan['total_item'] ?> item</span>
                                    </td>
                                    <td>
                                        <span class="font-semibold text-primary">Rp <?= number_format($penjualan['total_harga'], 0, ',', '.') ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('riwayat/detail/' . $penjualan['id']) ?>" class="btn btn-sm btn-info gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                            </svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($penjualans)): ?>
                <div class="flex justify-center mt-6">
                    <?= $pager->links('default', 'pagination') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>