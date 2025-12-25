<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Supplier</h1>
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
            <form action="<?= base_url('supplier') ?>" method="get" class="join w-full">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari nama supplier..." 
                    class="input input-bordered input-primary join-item flex-1" 
                    value="<?= esc($search ?? '') ?>"
                />
                <button type="submit" class="btn btn-primary join-item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                <?php if (!empty($search)): ?>
                    <a href="<?= base_url('supplier') ?>" class="btn btn-warning join-item">Reset</a>
                <?php endif; ?>
            </form>
        </div>
        <button class="btn btn-primary" onclick="modal_create.showModal()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Tambah Supplier
        </button>
    </div>

    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th class="min-w-[40px]">No</th>
                            <th class="min-w-[150px]">Nama Supplier</th>
                            <th class="min-w-[150px]">Alamat</th>
                            <th class="min-w-[130px]">Telepon</th>
                            <th class="min-w-[130px]">Status</th>
                            <th class="min-w-[150px]">Dibuat</th>
                            <th class="min-w-[150px]">Diperbarui</th>
                            <th class="min-w-[50px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($suppliers) && is_array($suppliers)): ?>
                            <?php
                            $no = 1 + (($pager->getCurrentPage() - 1) * $pager->getPerPage());
                            foreach ($suppliers as $supplier): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= esc($supplier['nama_suppl']) ?></td>
                                    <td><?= esc($supplier['alamat']) ?></td>
                                    <td><?= esc($supplier['telepon']) ?></td>
                                    <td>
                                        <?php if ($supplier['status'] == 'aktif'): ?>
                                            <span class="badge badge-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge badge-error">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d-m-Y H:i', strtotime($supplier['created_at'])) ?></td>
                                    <td><?= date('d-m-Y H:i', strtotime($supplier['updated_at'])) ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" onclick="openEditModal(<?= htmlspecialchars(json_encode($supplier), ENT_QUOTES, 'UTF-8') ?>)">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            Tidak ada data supplier
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

<dialog id="modal_create" class="modal">
    <div class="modal-box max-w-2xl">
        <h3 class="text-lg font-bold mb-4">Tambah Supplier</h3>
        <form action="<?= base_url('supplier/store') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="form-control w-full mb-4">
                <label class="label">
                    <span class="label-text">Nama Supplier</span>
                </label>
                <input type="text" name="nama_suppl" placeholder="Masukkan nama supplier" class="input input-bordered input-primary w-full" required />
            </div>

            <div class="form-control w-full mb-4">
                <label class="label">
                    <span class="label-text">Alamat</span>
                </label>
                <textarea name="alamat" placeholder="Masukkan alamat lengkap" class="textarea textarea-bordered textarea-primary w-full h-24" required></textarea>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label">
                    <span class="label-text">Telepon</span>
                </label>
                <input type="text" name="telepon" placeholder="Contoh: 08123456789 atau +6281234567890" class="input input-bordered input-primary w-full" required />
                <label class="label">
                    <span class="label-text-alt text-gray-500">Format: 08xxxxxxxxxx atau +62xxxxxxxxxxx</span>
                </label>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label">
                    <span class="label-text">Status</span>
                </label>
                <select name="status" class="select select-bordered w-full select-primary" required>
                    <option value="">Pilih Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="tidak aktif">Tidak Aktif</option>
                </select>
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
        <h3 class="text-lg font-bold mb-4">Edit Supplier</h3>
        <form action="<?= base_url('supplier/update') ?>" method="post" id="form_edit">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="edit_id">
            
            <div class="form-control w-full mb-4">
                <label class="label">
                    <span class="label-text">Nama Supplier</span>
                </label>
                <input type="text" name="nama_suppl" id="edit_nama_suppl" placeholder="Masukkan nama supplier" class="input input-bordered input-primary w-full" required />
            </div>

            <div class="form-control w-full mb-4">
                <label class="label">
                    <span class="label-text">Alamat</span>
                </label>
                <textarea name="alamat" id="edit_alamat" placeholder="Masukkan alamat lengkap" class="textarea textarea-bordered textarea-primary w-full h-24" required></textarea>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label">
                    <span class="label-text">Telepon</span>
                </label>
                <input type="text" name="telepon" id="edit_telepon" placeholder="Contoh: 08123456789 atau +6281234567890" class="input input-bordered input-primary w-full" required />
                <label class="label">
                    <span class="label-text-alt text-gray-500">Format: 08xxxxxxxxxx atau +62xxxxxxxxxxx</span>
                </label>
            </div>

            <div class="form-control w-full mb-4">
                <label class="label">
                    <span class="label-text">Status</span>
                </label>
                <select name="status" id="edit_status" class="select select-bordered select-primary w-full" required>
                    <option value="">Pilih Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="tidak aktif">Tidak Aktif</option>
                </select>
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
function openEditModal(supplier) {
    document.getElementById('edit_id').value = supplier.id;
    document.getElementById('edit_nama_suppl').value = supplier.nama_suppl;
    document.getElementById('edit_alamat').value = supplier.alamat;
    document.getElementById('edit_telepon').value = supplier.telepon;
    document.getElementById('edit_status').value = supplier.status;
    modal_edit.showModal();
}
</script>

<?= $this->endSection() ?>