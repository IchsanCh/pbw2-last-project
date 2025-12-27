<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center gap-4 mb-6">
        <button onclick="window.location.href = document.referrer || '<?= base_url('riwayat') ?>'" class="btn btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali
        </button>
        <h1 class="text-3xl font-bold">Detail Transaksi Penjualan</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-xl mb-4">Informasi Transaksi</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="text-sm text-gray-600">ID Penjualan</label>
                            <p class="font-mono font-bold text-lg"><?= esc($penjualan['id']) ?></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Tanggal Transaksi</label>
                            <p class="font-semibold"><?= date('d F Y, H:i', strtotime($penjualan['created_at'])) ?> WIB</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Kasir</label>
                            <p class="font-semibold"><?= esc($penjualan['nama_user']) ?></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Status Pembayaran</label>
                            <div class="mt-1">
                                <?php if ($penjualan['status_bayar'] === 'lunas'): ?>
                                    <span class="badge badge-success badge-lg gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Lunas
                                    </span>
                                <?php elseif ($penjualan['status_bayar'] === 'belum lunas'): ?>
                                    <span class="badge badge-warning badge-lg gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        Belum Lunas
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-error badge-lg gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                        Dibatalkan
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (!empty($penjualan['nama_pembeli'])): ?>
                            <div>
                                <label class="text-sm text-gray-600">Nama Pembeli</label>
                                <p class="font-semibold"><?= esc($penjualan['nama_pembeli']) ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if ($penjualan['status_bayar'] === 'dibatalkan' && !empty($penjualan['alasan_batal'])): ?>
                            <div class="md:col-span-2">
                                <label class="text-sm text-gray-600">Alasan Pembatalan</label>
                                <div class="alert alert-error mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-5 w-5" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span><?= esc($penjualan['alasan_batal']) ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="divider"></div>

                    <h3 class="font-bold text-lg mb-3">Detail Barang</h3>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th class="w-12">No</th>
                                    <th class="min-w-[150px]">Nama Barang</th>
                                    <th class="min-w-[100px]">Satuan</th>
                                    <th class="text-right min-w-[100px]">Qty</th>
                                    <th class="text-right min-w-[130px]">Harga Jual</th>
                                    <th class="text-right min-w-[130px]">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
foreach ($detils as $detil):
    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td class="font-semibold"><?= esc($detil['nama_brg']) ?></td>
                                        <td>
                                            <span class="badge badge-outline"><?= esc($detil['satuan']) ?></span>
                                        </td>
                                        <td class="text-right font-mono"><?= number_format($detil['qty'], 2, ',', '.') ?></td>
                                        <td class="text-right font-mono">Rp <?= number_format($detil['harga_jual'], 0, ',', '.') ?></td>
                                        <td class="text-right font-mono font-semibold text-primary">
                                            Rp <?= number_format($detil['subtotal'], 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="card bg-base-100 shadow-xl sticky top-4">
                <div class="card-body">
                    <h2 class="card-title text-lg mb-4">Ringkasan Transaksi</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Item:</span>
                            <span class="badge badge-lg badge-outline"><?= count($detils) ?> item</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Qty:</span>
                            <span class="font-semibold"><?= number_format($total_qty, 2, ',', '.') ?></span>
                        </div>

                        <div class="divider my-2"></div>

                        <div class="flex justify-between items-center text-xl">
                            <span class="font-bold">Total Harga:</span>
                            <span class="font-bold text-primary text-2xl">
                                Rp <?= number_format($total_harga, 0, ',', '.') ?>
                            </span>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="space-y-2">
                        <button onclick="window.print()" class="btn btn-outline btn-primary btn-block gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                            </svg>
                            Cetak Struk
                        </button>
                        <button onclick="window.location.href = document.referrer || '<?= base_url('riwayat') ?>'" class="btn btn-ghost btn-block">
                            Kembali ke Riwayat
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style media="print">
    @page {
        size: 80mm auto;
        margin: 5mm;
    }
    
    body * {
        visibility: hidden;
    }
    
    .print-area, .print-area * {
        visibility: visible;
    }
    
    .print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 80mm;
        font-size: 10pt;
    }
    
    .no-print {
        display: none !important;
    }
</style>

<div class="print-area hidden print:block">
    <div class="text-center mb-4">
        <h2 class="text-xl font-bold">STRUK PENJUALAN</h2>
        <p class="text-sm">Toko Nawir</p>
        <div class="border-b-2 border-dashed my-2"></div>
    </div>
    
    <div class="text-sm mb-3">
        <p><strong>ID:</strong> <?= esc($penjualan['id']) ?></p>
        <p><strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($penjualan['created_at'])) ?></p>
        <p><strong>Kasir:</strong> <?= esc($penjualan['nama_user']) ?></p>
        <?php if (!empty($penjualan['nama_pembeli'])): ?>
            <p><strong>Pembeli:</strong> <?= esc($penjualan['nama_pembeli']) ?></p>
        <?php endif; ?>
        <p><strong>Status:</strong> <?= ucwords($penjualan['status_bayar']) ?></p>
    </div>
    
    <div class="border-b-2 border-dashed my-2"></div>
    
    <table class="w-full text-sm mb-3">
        <thead>
            <tr class="border-b">
                <th class="text-left py-1">Item</th>
                <th class="text-right py-1">Qty</th>
                <th class="text-right py-1">Harga</th>
                <th class="text-right py-1">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detils as $detil): ?>
                <tr>
                    <td class="py-1"><?= esc($detil['nama_brg']) ?></td>
                    <td class="text-right"><?= number_format($detil['qty'], 2) ?></td>
                    <td class="text-right"><?= number_format($detil['harga_jual'], 0) ?></td>
                    <td class="text-right"><?= number_format($detil['subtotal'], 0) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="border-b-2 border-dashed my-2"></div>
    
    <div class="text-sm mb-3">
        <div class="flex justify-between py-1">
            <span>Total Item:</span>
            <strong><?= count($detils) ?> item</strong>
        </div>
        <div class="flex justify-between py-1">
            <span>Total Qty:</span>
            <strong><?= number_format($total_qty, 2) ?></strong>
        </div>
        <div class="flex justify-between text-lg font-bold py-1">
            <span>TOTAL:</span>
            <span>Rp <?= number_format($total_harga, 0, ',', '.') ?></span>
        </div>
    </div>
    
    <div class="border-b-2 border-dashed my-2"></div>
    
    <div class="text-center text-sm mt-3">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p class="text-xs">Barang yang sudah dibeli tidak dapat ditukar</p>
    </div>
</div>

<?= $this->endSection() ?>