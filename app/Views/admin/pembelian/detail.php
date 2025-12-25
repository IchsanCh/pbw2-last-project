<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="<?= base_url('pembelian') ?>" class="btn btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali
        </a>
        <h1 class="text-3xl font-bold">Detail Transaksi Pembelian</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 space-y-4">
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-lg mb-4">Informasi Transaksi</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">ID Pembelian</p>
                            <p class="font-mono font-semibold text-primary text-lg"><?= esc($pembelian['id']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Transaksi</p>
                            <p class="font-semibold"><?= date('d F Y, H:i', strtotime($pembelian['created_at'])) ?> WIB</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pegawai</p>
                            <p class="font-semibold"><?= esc($pembelian['nama_user']) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-lg mb-4">Informasi Supplier</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Nama Supplier</p>
                            <p class="font-semibold text-lg"><?= esc($pembelian['nama_suppl']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Alamat</p>
                            <p><?= esc($pembelian['alamat']) ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Telepon</p>
                            <p class="font-mono"><?= esc($pembelian['telepon']) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-lg mb-4">Detail Barang</h2>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th class="text-right">Qty</th>
                                    <th class="text-right">Harga Beli</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
foreach ($detils as $detil): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <div class="font-semibold"><?= esc($detil['nama_brg']) ?></div>
                                            <div class="text-sm text-gray-500">Satuan: <?= esc($detil['satuan']) ?></div>
                                        </td>
                                        <td class="text-right font-mono"><?= number_format($detil['qty'], 2, ',', '.') ?></td>
                                        <td class="text-right font-mono">Rp <?= number_format($detil['harga_beli'], 0, ',', '.') ?></td>
                                        <td class="text-right font-semibold font-mono">Rp <?= number_format($detil['subtotal'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="font-bold">
                                    <td colspan="2" class="text-right">TOTAL</td>
                                    <td class="text-right font-mono"><?= number_format($total_qty, 2, ',', '.') ?></td>
                                    <td></td>
                                    <td class="text-right text-primary text-lg font-mono">Rp <?= number_format($total_harga, 0, ',', '.') ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 ringkasan-print">
            <div class="card bg-base-100 shadow-xl sticky top-4">
                <div class="card-body">
                    <h2 class="card-title text-lg mb-4">Ringkasan</h2>
                    
                    <div class="stats stats-vertical shadow mb-4">
                        <div class="stat">
                            <div class="stat-title">Total Item</div>
                            <div class="stat-value text-primary"><?= count($detils) ?></div>
                            <div class="stat-desc">Jenis barang</div>
                        </div>
                        
                        <div class="stat">
                            <div class="stat-title">Total Quantity</div>
                            <div class="stat-value text-secondary"><?= number_format($total_qty, 2, ',', '.') ?></div>
                            <div class="stat-desc">Total barang dibeli</div>
                        </div>
                        
                        <div class="stat">
                            <div class="stat-title">Total Harga</div>
                            <div class="stat-value text-success text-xl">Rp <?= number_format($total_harga, 0, ',', '.') ?></div>
                            <div class="stat-desc">Total pembelian</div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="space-y-2">
                        <button class="btn btn-primary btn-block" onclick="window.print()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                            </svg>
                            Cetak
                        </button>
                        
                        <button class="btn btn-error btn-block btn-outline" onclick="confirmDelete('<?= esc($pembelian['id']) ?>', '<?= esc($pembelian['nama_suppl']) ?>')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Hapus Transaksi
                        </button>

                        <a href="<?= base_url('pembelian') ?>" class="btn btn-ghost btn-block">Kembali ke List</a>
                    </div>
                </div>
            </div>
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

window.addEventListener('beforeprint', function() {
    document.body.classList.add('printing');
});

window.addEventListener('afterprint', function() {
    document.body.classList.remove('printing');
});
</script>

<style>
@media print {
    .btn, .modal, nav, aside, .no-print, .ringkasan-print {
        display: none !important;
    }
    .footer p {
        color: black !important;
    }
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd;
    }
    
    body {
        background: white !important;
    }
}
</style>

<?= $this->endSection() ?>