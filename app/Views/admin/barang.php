<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Barang</h1>
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
            <form action="<?= base_url('barang') ?>" method="get" class="join w-full">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari id atau nama barang..." 
                    class="input input-bordered input-primary join-item flex-1" 
                    value="<?= esc($search ?? '') ?>"
                />
                <button type="submit" class="btn btn-primary join-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                <?php if (!empty($search)): ?>
                    <a href="<?= base_url('barang') ?>" class="btn btn-warning join-item">Reset</a>
                <?php endif; ?>
            </form>
        </div>
        <button class="btn btn-primary" onclick="modal_create.showModal()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Tambah Barang
        </button>
    </div>

    <div class="card bg-base-100 shadow-xl">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table table-zebra table-pin-rows">
                    <thead>
                        <tr>
                            <th class="bg-base-200 z-10">No</th>
                            <th class="bg-base-200 min-w-[120px]">ID Barang</th>
                            <th class="bg-base-200 min-w-[200px]">Nama Barang</th>
                            <th class="bg-base-200 min-w-[200px]">Kategori</th>
                            <th class="bg-base-200 min-w-[100px]">Satuan</th>
                            <th class="bg-base-200 min-w-[150px]">Harga</th>
                            <th class="bg-base-200 min-w-[100px]">Stok</th>
                            <th class="bg-base-200 min-w-[100px]">Min Stok</th>
                            <th class="bg-base-200 min-w-[120px]">Status</th>
                            <th class="bg-base-200 min-w-[130px]">Dibuat</th>
                            <th class="bg-base-200 min-w-[130px]">Diperbarui</th>
                            <th class="bg-base-200 sticky right-0 z-10">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($barangs) && is_array($barangs)): ?>
                            <?php
                            $no = 1 + (($pager->getCurrentPage() - 1) * $pager->getPerPage());
                            foreach ($barangs as $barang): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><span class="font-mono font-semibold"><?= esc($barang['id']) ?></span></td>
                                    <td class="font-medium"><?= esc($barang['nama_brg']) ?></td>
                                    <td>
                                        <span class="badge badge-outline badge-primary">
                                            <?= esc($barang['nama_kategori'] ?? '-') ?>
                                        </span>
                                    </td>
                                    <td><span class="badge badge-ghost"><?= esc($barang['satuan']) ?></span></td>
                                    <td class="font-bold text-success">Rp <?= number_format($barang['harga'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php if ($barang['stok'] <= $barang['min_stok']): ?>
                                            <span class="badge badge-error gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                <?= $barang['stok'] ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-success"><?= $barang['stok'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><span class="text-gray-600"><?= $barang['min_stok'] ?></span></td>
                                    <td>
                                        <?php if ($barang['status'] == 'aktif'): ?>
                                            <span class="badge badge-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge badge-error">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-sm text-gray-600"><?= date('d-m-Y', strtotime($barang['created_at'])) ?></td>
                                    <td class="text-sm text-gray-600"><?= date('d-m-Y', strtotime($barang['updated_at'])) ?></td>
                                    <td class="sticky right-0 bg-base-100">
                                        <button class="btn btn-sm btn-warning" onclick="openEditModal(<?= htmlspecialchars(json_encode($barang), ENT_QUOTES, 'UTF-8') ?>)">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="12" class="text-center py-8">
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            Tidak ada data barang
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
           <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
                <div class="flex justify-center p-4">
                    <?= $pager->links('default', 'pagination') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<dialog id="modal_create" class="modal">
    <div class="modal-box max-w-2xl">
        <h3 class="text-lg font-bold mb-4">Tambah Barang</h3>
        <form action="<?= base_url('barang/store') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">ID Barang</span>
                    </label>
                    <input type="text" name="id" placeholder="Masukkan ID barang" class="input input-bordered input-primary w-full" required />
                    <label class="label">
                        <span class="label-text-alt text-gray-500">3-20 karakter</span>
                    </label>
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">Kategori</span>
                    </label>
                    <select name="id_kategori" class="select select-bordered w-full select-primary" required>
                        <option value="">Pilih Kategori</option>
                        <?php if (!empty($kategoris)): ?>
                            <?php foreach ($kategoris as $kategori): ?>
                                <option value="<?= $kategori['id'] ?>"><?= esc($kategori['nama_kategori']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-control w-full md:col-span-2">
                    <label class="label">
                        <span class="label-text">Nama Barang</span>
                    </label>
                    <input type="text" name="nama_brg" placeholder="Masukkan nama barang" class="input input-bordered input-primary w-full" required />
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">Satuan</span>
                    </label>
                    <input type="text" name="satuan" placeholder="Contoh: pcs, box, kg" class="input input-bordered input-primary w-full" required />
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">Harga</span>
                    </label>
                    <input type="number" name="harga" placeholder="Masukkan harga" class="input input-bordered input-primary w-full" required min="0" />
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">Stok</span>
                    </label>
                    <input type="number" name="stok" placeholder="Masukkan stok" class="input input-bordered input-primary w-full" required min="0" step="0.01" />
                    <label class="label">
                        <span class="label-text-alt text-gray-500">Bisa gunakan desimal (misal: 2.5)</span>
                    </label>
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">Minimal Stok</span>
                    </label>
                    <input type="number" name="min_stok" placeholder="Masukkan minimal stok" class="input input-bordered input-primary w-full" required min="0" step="0.01" />
                    <label class="label">
                        <span class="label-text-alt text-gray-500">Bisa gunakan desimal (misal: 1.5)</span>
                    </label>
                </div>

                <div class="form-control w-full md:col-span-2">
                    <label class="label">
                        <span class="label-text">Status</span>
                    </label>
                    <select name="status" class="select select-bordered w-full select-primary" required>
                        <option value="">Pilih Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="tidak aktif">Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-error" onclick="modal_create.close()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<dialog id="modal_edit" class="modal">
    <div class="modal-box max-w-2xl">
        <h3 class="text-lg font-bold mb-4">Edit Barang</h3>
        <form action="<?= base_url('barang/update') ?>" method="post" id="form_edit">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="edit_id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">ID Barang</span>
                    </label>
                    <input type="text" id="edit_id_display" class="input input-bordered input-primary w-full" disabled />
                    <label class="label">
                        <span class="label-text-alt text-gray-500">ID tidak dapat diubah</span>
                    </label>
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">Kategori</span>
                    </label>
                    <select name="id_kategori" id="edit_id_kategori" class="select select-bordered w-full select-primary" required>
                        <option value="">Pilih Kategori</option>
                        <?php if (!empty($kategoris)): ?>
                            <?php foreach ($kategoris as $kategori): ?>
                                <option value="<?= $kategori['id'] ?>"><?= esc($kategori['nama_kategori']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-control w-full md:col-span-2">
                    <label class="label">
                        <span class="label-text">Nama Barang</span>
                    </label>
                    <input type="text" name="nama_brg" id="edit_nama_brg" placeholder="Masukkan nama barang" class="input input-bordered input-primary w-full" required />
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">Satuan</span>
                    </label>
                    <input type="text" name="satuan" id="edit_satuan" placeholder="Contoh: pcs, box, kg" class="input input-bordered input-primary w-full" required />
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">Harga</span>
                    </label>
                    <input type="number" name="harga" id="edit_harga" placeholder="Masukkan harga" class="input input-bordered input-primary w-full" required min="0" />
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">Stok</span>
                    </label>
                    <input type="number" name="stok" id="edit_stok" placeholder="Masukkan stok" class="input input-bordered input-primary w-full" required min="0" step="0.01" />
                    <label class="label">
                        <span class="label-text-alt text-gray-500">Bisa gunakan desimal (misal: 2.5)</span>
                    </label>
                </div>

                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text">Minimal Stok</span>
                    </label>
                    <input type="number" name="min_stok" id="edit_min_stok" placeholder="Masukkan minimal stok" class="input input-bordered input-primary w-full" required min="0" step="0.01" />
                    <label class="label">
                        <span class="label-text-alt text-gray-500">Bisa gunakan desimal (misal: 1.5)</span>
                    </label>
                </div>

                <div class="form-control w-full md:col-span-2">
                    <label class="label">
                        <span class="label-text">Status</span>
                    </label>
                    <select name="status" id="edit_status" class="select select-bordered w-full select-primary" required>
                        <option value="">Pilih Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="tidak aktif">Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-error" onclick="modal_edit.close()">Batal</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
function openEditModal(barang) {
    document.getElementById('edit_id').value = barang.id;
    document.getElementById('edit_id_display').value = barang.id;
    document.getElementById('edit_id_kategori').value = barang.id_kategori;
    document.getElementById('edit_nama_brg').value = barang.nama_brg;
    document.getElementById('edit_satuan').value = barang.satuan;
    document.getElementById('edit_harga').value = barang.harga;
    document.getElementById('edit_stok').value = barang.stok;
    document.getElementById('edit_min_stok').value = barang.min_stok;
    document.getElementById('edit_status').value = barang.status;
    modal_edit.showModal();
}
</script>

<?= $this->endSection() ?>