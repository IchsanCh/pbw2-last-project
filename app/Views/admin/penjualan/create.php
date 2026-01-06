<?= $this->extend('admin/layout_admin') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center gap-4 mb-6">
        <h1 class="text-3xl font-bold">Checkout Penjualan</h1>
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
    <form action="<?= base_url('penjualan/store') ?>" method="post" id="form_checkout">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <div class="lg:col-span-2">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-lg mb-4">Informasi Transaksi</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text">ID Penjualan</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="id" 
                                    value="<?= esc($generated_id) ?>" 
                                    class="input input-bordered input-primary font-mono" 
                                    readonly 
                                />
                            </div>

                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text">Status Pembayaran <span class="text-error">*</span></span>
                                </label>
                                <select name="status_bayar" id="status_bayar" class="select select-bordered select-primary w-full" onchange="toggleNamaPembeli()" required>
                                    <option value="lunas" selected>Lunas (Cash)</option>
                                    <option value="belum lunas">Belum Lunas (Hutang)</option>
                                </select>
                            </div>
                        </div>

                        <div id="nama_pembeli_container" class="form-control w-full mb-4" style="display: none;">
                            <label class="label">
                                <span class="label-text">Nama Pembeli <span class="text-error">*</span></span>
                            </label>
                            <input 
                                type="text" 
                                name="nama_pembeli"
                                id="nama_pembeli"
                                placeholder="Masukkan nama pembeli untuk transaksi hutang"
                                class="input input-bordered input-primary w-full" 
                            />
                            <label class="label">
                                <span class="label-text-alt text-warning">Wajib diisi untuk transaksi hutang</span>
                            </label>
                        </div>

                        <div class="divider">Daftar Barang</div>

                        <div id="items_container">
                            <div class="item-row bg-base-200 p-4 rounded-lg mb-3" data-index="0">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="font-semibold text-primary">Item #1</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div class="form-control w-full">
                                        <label class="label py-1">
                                            <span class="label-text text-xs font-semibold">Barang <span class="text-error">*</span></span>
                                        </label>
                                        <select name="items[0][id_barang]" class="select select-bordered select-sm select-primary w-full barang-select" onchange="updateItemInfo(0)" required>
                                            <option value="">Pilih Barang</option>
                                            <?php foreach ($barangs as $barang): ?>
                                                <option value="<?= $barang['id'] ?>" 
                                                        data-harga="<?= $barang['harga'] ?>" 
                                                        data-satuan="<?= $barang['satuan'] ?>"
                                                        data-stok="<?= $barang['stok'] ?>">
                                                    <?= esc($barang['nama_brg']) ?> - Stok: <?= number_format($barang['stok'], 2) ?> <?= esc($barang['satuan']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <label class="label">
                                            <span class="label-text-alt text-info stok-info-0">Pilih barang terlebih dahulu</span>
                                        </label>
                                    </div>
                                    <div class="form-control w-full">
                                        <label class="label py-1">
                                            <span class="label-text text-xs font-semibold">Jumlah <span class="text-error">*</span></span>
                                        </label>
                                        <input 
                                            type="number" 
                                            name="items[0][qty]" 
                                            placeholder="0" 
                                            class="input input-bordered input-sm input-primary w-full qty-input" 
                                            min="0.01" 
                                            step="0.01"
                                            onchange="calculateSubtotal(0)"
                                            oninput="validateStock(0)"
                                            required 
                                        />
                                    </div>
                                    <div class="form-control w-full">
                                        <label class="label py-1">
                                            <span class="label-text text-xs font-semibold">Harga Jual (Rp) <span class="text-error">*</span></span>
                                        </label>
                                        <input 
                                            type="number" 
                                            name="items[0][harga_jual]" 
                                            placeholder="0" 
                                            class="input input-bordered input-sm input-primary w-full harga-input" 
                                            min="0"
                                            readonly
                                            onchange="calculateSubtotal(0)"
                                            required 
                                        />
                                    </div>
                                </div>
                                <div class="mt-2 text-right">
                                    <span class="text-sm">Subtotal: <strong class="subtotal-display text-lg text-primary">Rp 0</strong></span>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline btn-primary btn-sm gap-2" onclick="addItem()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Tambah Item
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="card bg-base-100 shadow-xl sticky top-4">
                    <div class="card-body">
                        <h2 class="card-title text-lg mb-4">Ringkasan Belanja</h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span>Total Item:</span>
                                <strong class="text-lg" id="total_items">0</strong>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Total Barang:</span>
                                <strong class="text-lg" id="total_qty">0</strong>
                            </div>
                            <div class="divider my-2"></div>
                            <div class="flex justify-between text-xl">
                                <span class="font-bold">Total Bayar:</span>
                                <strong class="text-primary text-2xl" id="total_harga">Rp 0</strong>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="flex flex-col gap-2">
                            <button type="button" onclick="confirmCheckout()" class="btn btn-primary btn-block gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                </svg>
                                Proses Checkout
                            </button>
                            <a href="<?= base_url('penjualan/create') ?>" class="btn btn-ghost btn-block">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<dialog id="modal_konfirmasi" class="modal">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-xl mb-4">Konfirmasi Transaksi</h3>
        <p class="mb-4">Pastikan data transaksi sudah benar sebelum melanjutkan:</p>
        
        <div class="bg-base-200 p-4 rounded-lg mb-4">
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <p class="text-sm text-gray-600">ID Penjualan</p>
                    <p class="font-semibold" id="confirm_id">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status Pembayaran</p>
                    <p class="font-semibold" id="confirm_status">-</p>
                </div>
            </div>
            <div id="confirm_nama_container" style="display: none;">
                <p class="text-sm text-gray-600">Nama Pembeli</p>
                <p class="font-semibold" id="confirm_nama">-</p>
            </div>
            
            <div class="divider my-2"></div>
            
            <div class="mb-3">
                <p class="text-sm text-gray-600 mb-2">Daftar Barang:</p>
                <div id="confirm_items" class="space-y-2"></div>
            </div>
            
            <div class="divider my-2"></div>
            
            <div class="flex justify-between items-center">
                <span class="font-bold text-lg">Total Bayar:</span>
                <span class="font-bold text-2xl text-primary" id="confirm_total">Rp 0</span>
            </div>
        </div>
        
        <div class="modal-action">
            <button type="button" class="btn btn-ghost" onclick="document.getElementById('modal_konfirmasi').close()">Periksa Kembali</button>
            <button type="button" class="btn btn-primary" onclick="submitCheckout()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Ya, Proses Transaksi
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
let itemIndex = 1;
const barangData = <?= json_encode($barangs) ?>;

function toggleNamaPembeli() {
    const statusBayar = document.getElementById('status_bayar').value;
    const namaContainer = document.getElementById('nama_pembeli_container');
    const namaInput = document.getElementById('nama_pembeli');
    
    if (statusBayar === 'belum lunas') {
        namaContainer.style.display = 'block';
        namaInput.required = true;
    } else {
        namaContainer.style.display = 'none';
        namaInput.required = false;
        namaInput.value = '';
    }
}

function addItem() {
    const container = document.getElementById('items_container');
    const newIndex = itemIndex++;
    
    const itemHTML = `
        <div class="item-row bg-base-200 p-4 rounded-lg mb-3" data-index="${newIndex}">
            <div class="flex justify-between items-center mb-3">
                <span class="font-semibold text-primary">Item #${newIndex + 1}</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="form-control w-full">
                    <label class="label py-1">
                        <span class="label-text text-xs font-semibold">Barang <span class="text-error">*</span></span>
                    </label>
                    <select name="items[${newIndex}][id_barang]" class="select select-bordered select-sm select-primary w-full barang-select" onchange="updateItemInfo(${newIndex})" required>
                        <option value="">Pilih Barang</option>
                        ${barangData.map(b => `<option value="${b.id}" data-harga="${b.harga}" data-satuan="${b.satuan}" data-stok="${b.stok}">${b.nama_brg} - Stok: ${parseFloat(b.stok).toFixed(2)} ${b.satuan}</option>`).join('')}
                    </select>
                    <label class="label">
                        <span class="label-text-alt text-info stok-info-${newIndex}">Pilih barang terlebih dahulu</span>
                    </label>
                </div>
                <div class="form-control w-full">
                    <label class="label py-1">
                        <span class="label-text text-xs font-semibold">Jumlah <span class="text-error">*</span></span>
                    </label>
                    <input 
                        type="number" 
                        name="items[${newIndex}][qty]" 
                        placeholder="0" 
                        class="input input-bordered input-sm input-primary w-full qty-input" 
                        min="0.01" 
                        step="0.01"
                        onchange="calculateSubtotal(${newIndex})"
                        oninput="validateStock(${newIndex})"
                        required 
                    />
                </div>
                <div class="form-control w-full">
                    <label class="label py-1">
                        <span class="label-text text-xs font-semibold">Harga Jual (Rp) <span class="text-error">*</span></span>
                    </label>
                    <input 
                        type="number" 
                        name="items[${newIndex}][harga_jual]" 
                        placeholder="0" 
                        readonly
                        class="input input-bordered input-sm input-primary w-full harga-input" 
                        min="0"
                        onchange="calculateSubtotal(${newIndex})"
                        required 
                    />
                </div>
            </div>
            <div class="mt-2 text-right">
                <span class="text-sm">Subtotal: <strong class="subtotal-display text-lg text-primary">Rp 0</strong></span>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', itemHTML);
    updateSummary();
}

function updateItemInfo(index) {
    const row = document.querySelector(`.item-row[data-index="${index}"]`);
    const select = row.querySelector('.barang-select');
    const hargaInput = row.querySelector('.harga-input');
    const stokInfo = document.querySelector(`.stok-info-${index}`);
    
    const selectedOption = select.options[select.selectedIndex];
    const harga = selectedOption.getAttribute('data-harga');
    const stok = selectedOption.getAttribute('data-stok');
    const satuan = selectedOption.getAttribute('data-satuan');
    
    if (harga && stok) {
        hargaInput.value = harga;
        stokInfo.textContent = `Stok tersedia: ${parseFloat(stok).toFixed(2)} ${satuan}`;
        stokInfo.classList.remove('text-error');
        stokInfo.classList.add('text-info');
        calculateSubtotal(index);
    } else {
        stokInfo.textContent = 'Pilih barang terlebih dahulu';
        stokInfo.classList.remove('text-error');
        stokInfo.classList.add('text-info');
    }
}

function validateStock(index) {
    const row = document.querySelector(`.item-row[data-index="${index}"]`);
    const select = row.querySelector('.barang-select');
    const qtyInput = row.querySelector('.qty-input');
    const stokInfo = document.querySelector(`.stok-info-${index}`);
    
    const selectedOption = select.options[select.selectedIndex];
    const stok = parseFloat(selectedOption.getAttribute('data-stok')) || 0;
    const qty = parseFloat(qtyInput.value) || 0;
    const satuan = selectedOption.getAttribute('data-satuan');
    
    if (qty > stok) {
        stokInfo.textContent = `⚠️ Jumlah melebihi stok! Stok tersedia: ${stok.toFixed(2)} ${satuan}`;
        stokInfo.classList.remove('text-info');
        stokInfo.classList.add('text-error');
        qtyInput.classList.add('input-error');
    } else if (select.value) {
        stokInfo.textContent = `Stok tersedia: ${stok.toFixed(2)} ${satuan}`;
        stokInfo.classList.remove('text-error');
        stokInfo.classList.add('text-info');
        qtyInput.classList.remove('input-error');
    }
}

function calculateSubtotal(index) {
    const row = document.querySelector(`.item-row[data-index="${index}"]`);
    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
    const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
    const subtotal = qty * harga;
    
    row.querySelector('.subtotal-display').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    updateSummary();
}

function updateSummary() {
    const items = document.querySelectorAll('.item-row');
    let totalItems = 0;
    let totalQty = 0;
    let totalHarga = 0;
    
    items.forEach(item => {
        const qty = parseFloat(item.querySelector('.qty-input').value) || 0;
        const harga = parseFloat(item.querySelector('.harga-input').value) || 0;
        
        if (qty > 0 && harga > 0) {
            totalItems++;
            totalQty += qty;
            totalHarga += (qty * harga);
        }
    });
    
    document.getElementById('total_items').textContent = totalItems;
    document.getElementById('total_qty').textContent = totalQty.toFixed(2);
    document.getElementById('total_harga').textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
}

function confirmCheckout() {
    const items = document.querySelectorAll('.item-row');
    const statusBayar = document.getElementById('status_bayar').value;
    const namaPembeli = document.getElementById('nama_pembeli').value.trim();
    
    let hasValidItem = false;
    let hasStockError = false;
    
    items.forEach(item => {
        const select = item.querySelector('.barang-select');
        const qtyInput = item.querySelector('.qty-input');
        const harga = parseFloat(item.querySelector('.harga-input').value) || 0;
        
        const selectedOption = select.options[select.selectedIndex];
        const stok = parseFloat(selectedOption.getAttribute('data-stok')) || 0;
        const qty = parseFloat(qtyInput.value) || 0;
        
        if (select.value && qty > 0 && harga > 0) {
            hasValidItem = true;
            if (qty > stok) {
                hasStockError = true;
            }
        }
    });
    
    if (!hasValidItem) {
        alert('Minimal harus ada 1 item barang yang valid!');
        return false;
    }
    
    if (hasStockError) {
        alert('Ada barang dengan jumlah yang melebihi stok! Silakan periksa kembali.');
        return false;
    }
    
    if (statusBayar === 'belum lunas' && !namaPembeli) {
        alert('Nama pembeli wajib diisi untuk transaksi hutang!');
        document.getElementById('nama_pembeli').focus();
        return false;
    }
    
    const idPenjualan = document.querySelector('input[name="id"]').value;
    const statusText = statusBayar === 'lunas' ? 'Lunas (Cash)' : 'Belum Lunas (Hutang)';
    
    document.getElementById('confirm_id').textContent = idPenjualan;
    document.getElementById('confirm_status').textContent = statusText;
    
    const namaContainer = document.getElementById('confirm_nama_container');
    if (statusBayar === 'belum lunas') {
        namaContainer.style.display = 'block';
        document.getElementById('confirm_nama').textContent = namaPembeli;
    } else {
        namaContainer.style.display = 'none';
    }
    
    const confirmItems = document.getElementById('confirm_items');
    confirmItems.innerHTML = '';
    
    let totalHarga = 0;
    items.forEach(item => {
        const select = item.querySelector('.barang-select');
        const qty = parseFloat(item.querySelector('.qty-input').value) || 0;
        const harga = parseFloat(item.querySelector('.harga-input').value) || 0;
        
        if (select.value && qty > 0 && harga > 0) {
            const selectedOption = select.options[select.selectedIndex];
            const namaBarang = selectedOption.textContent.split(' - Stok:')[0];
            const subtotal = qty * harga;
            totalHarga += subtotal;
            
            const itemDiv = document.createElement('div');
            itemDiv.className = 'flex justify-between text-sm bg-base-100 p-2 rounded';
            itemDiv.innerHTML = `
                <span>${namaBarang}</span>
                <span>${qty.toFixed(2)} x Rp ${harga.toLocaleString('id-ID')} = <strong>Rp ${subtotal.toLocaleString('id-ID')}</strong></span>
            `;
            confirmItems.appendChild(itemDiv);
        }
    });
    
    document.getElementById('confirm_total').textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
    
    document.getElementById('modal_konfirmasi').showModal();
}

function submitCheckout() {
    document.getElementById('form_checkout').submit();
}
</script>

<?= $this->endSection() ?>