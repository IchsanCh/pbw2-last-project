<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Transaksi Pembelian</h1>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('success', 'Berhasil', '<?= addslashes(session()->getFlashdata('success')) ?>');
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('error', 'Gagal', '<?= addslashes(session()->getFlashdata('error')) ?>');
            });
        </script>
    <?php endif; ?>

    <div class="flex flex-col md:flex-row gap-4 mb-4">
        <div class="flex-1">
            <form action="<?= base_url('pembelian') ?>" method="get" class="join w-full">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari ID pembelian atau supplier..." 
                    class="input input-bordered input-primary join-item flex-1" 
                    value="<?= esc($search ?? '') ?>"
                />
                <button type="submit" class="btn btn-primary join-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                <?php if (!empty($search)): ?>
                    <a href="<?= base_url('pembelian') ?>" class="btn btn-warning join-item">Reset</a>
                <?php endif; ?>
            </form>
        </div>
        <a href="<?= base_url('pembelian/create') ?>" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Buat Transaksi Pembelian
        </a>
    </div>

    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th class="min-w-[30px]">No</th>
                            <th class="min-w-[180px]">ID Pembelian</th>
                            <th class="min-w-[150px]">Supplier</th>
                            <th class="min-w-[180px]">Pegawai</th>
                            <th class="min-w-[150px]">Total Item</th>
                            <th class="min-w-[180px]">Total Harga</th>
                            <th class="min-w-[180px]">Tanggal</th>
                            <th class="min-w-[130px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pembelians) && is_array($pembelians)): ?>
                            <?php
                            $no = 1 + (($pager->getCurrentPage() - 1) * $pager->getPerPage());
                            foreach ($pembelians as $pembelian): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <span class="font-mono font-semibold text-primary"><?= esc($pembelian['id']) ?></span>
                                    </td>
                                    <td><?= esc($pembelian['nama_suppl']) ?></td>
                                    <td><?= esc($pembelian['nama_user']) ?></td>
                                    <td>
                                        <span class="badge badge-info"><?= $pembelian['total_item'] ?> item</span>
                                    </td>
                                    <td>
                                        <span class="font-semibold">Rp <?= number_format($pembelian['total_harga'], 0, ',', '.') ?></span>
                                    </td>
                                    <td><?= date('d-m-Y H:i', strtotime($pembelian['created_at'])) ?></td>
                                    <td>
                                        <div class="flex gap-2">
                                            <a href="<?= base_url('pembelian/detail/' . $pembelian['id']) ?>" class="btn btn-sm btn-info" title="Lihat Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                            <button class="btn btn-sm btn-error" onclick="confirmDelete('<?= esc($pembelian['id']) ?>', '<?= esc($pembelian['nama_suppl']) ?>')" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-8">
                                    <?php if (!empty($search)): ?>
                                        <div class="text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Tidak ada hasil untuk "<?= esc($search) ?>"
                                        </div>
                                    <?php else: ?>
                                        <div class="text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Belum ada transaksi pembelian
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
                <div class="flex mt-6">
                    <?= $pager->links('default', 'pagination') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<dialog id="modal_delete" class="modal">
    <div class="modal-box">
        <h3 class="text-lg font-bold mb-4">Konfirmasi Hapus</h3>
        <div class="alert alert-warning mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>Tindakan ini tidak dapat dibatalkan!</span>
        </div>
        <p class="mb-4">Apakah Anda yakin ingin menghapus transaksi pembelian ini?</p>
        <div class="bg-base-200 p-4 rounded-lg mb-4">
            <p class="text-sm"><strong>ID:</strong> <span id="delete_id" class="font-mono"></span></p>
            <p class="text-sm"><strong>Supplier:</strong> <span id="delete_supplier"></span></p>
        </div>
        <form action="<?= base_url('pembelian/delete') ?>" method="post" id="form_delete">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="delete_id_input">
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="modal_delete.close()">Batal</button>
                <button type="submit" class="btn btn-error">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Ya, Hapus
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
function confirmDelete(id, supplier) {
    document.getElementById('delete_id').textContent = id;
    document.getElementById('delete_supplier').textContent = supplier;
    document.getElementById('delete_id_input').value = id;
    modal_delete.showModal();
}
</script>

<?= $this->endSection() ?>