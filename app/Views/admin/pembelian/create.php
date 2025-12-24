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
        <h1 class="text-3xl font-bold">Buat Transaksi Pembelian</h1>
    </div>

    <form action="<?= base_url('pembelian/store') ?>" method="post" id="form_pembelian">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <!-- Info Transaksi -->
            <div class="lg:col-span-2">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-lg mb-4">Informasi Transaksi</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text">ID Pembelian</span>
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
                                    <span class="label-text">Supplier <span class="text-error">*</span></span>
                                </label>
                                <select name="id_supplier" class="select select-bordered select-primary w-full" required>
                                    <option value="">Pilih Supplier</option>
                                    <?php foreach ($suppliers as $supplier): ?>
                                        <option value="<?= $supplier['id'] ?>"><?= esc($supplier['nama_suppl']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="divider">Daftar Barang</div>

                        <div id="items_container">
                            <!-- Item pertama -->
                            <div class="item-row bg-base-200 p-4 rounded-lg mb-3" data-index="0">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="font-semibold">Item #1</span>
                                    <button type="button" class="btn btn-sm btn-ghost btn-circle" onclick="removeItem(0)" style="display: none;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div class="form-control w-full">
                                        <label class="label py-1">
                                            <span class="label-text text-xs">Barang</span>
                                        </label>
                                        <select name="items[0][id_barang]" class="select select-bordered select-sm select-primary w-full barang-select" onchange="updateHargaBeli(0)" required>
                                            <option value="">Pilih Barang</option>
                                            <?php foreach ($barangs as $barang): ?>
                                                <option value="<?= $barang['id'] ?>" data-harga="<?= $barang['harga'] ?>" data-satuan="<?= $barang['satuan'] ?>">
                                                    <?= esc($barang['nama_brg']) ?> (<?= esc($barang['satuan']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-control w-full">
                                        <label class="label py-1">
                                            <span class="label-text text-xs">Qty</span>
                                        </label>
                                        <input 
                                            type="number" 
                                            name="items[0][qty]" 
                                            placeholder="0" 
                                            class="input input-bordered input-sm input-primary w-full qty-input" 
                                            min="0.01" 
                                            step="0.01"
                                            onchange="calculateSubtotal(0)"
                                            required 
                                        />
                                    </div>
                                    <div class="form-control w-full">
                                        <label class="label py-1">
                                            <span class="label-text text-xs">Harga Beli (Rp)</span>
                                        </label>
                                        <input 
                                            type="number" 
                                            name="items[0][harga_beli]" 
                                            placeholder="0" 
                                            class="input input-bordered input-sm input-primary w-full harga-input" 
                                            min="0"
                                            onchange="calculateSubtotal(0)"
                                            required 
                                        />
                                    </div>
                                </div>
                                <div class="mt-2 text-right">
                                    <span class="text-sm">Subtotal: <strong class="subtotal-display">Rp 0</strong></span>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline btn-primary btn-sm" onclick="addItem()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Tambah Item
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="lg:col-span-1">
                <div class="card bg-base-100 shadow-xl sticky top-4">
                    <div class="card-body">
                        <h2 class="card-title text-lg mb-4">Ringkasan</h2>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span>Total Item:</span>
                                <strong id="total_items">0</strong>
                            </div>
                            <div class="divider my-2"></div>
                            <div class="flex justify-between text-lg">
                                <span class="font-semibold">Total Harga:</span>
                                <strong class="text-primary" id="total_harga">Rp 0</strong>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="flex flex-col gap-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Simpan Transaksi
                            </button>
                            <a href="<?= base_url('pembelian') ?>" class="btn btn-ghost btn-block">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let itemIndex = 1;
const barangData = <?= json_encode($barangs) ?>;

function addItem() {
    const container = document.getElementById('items_container');
    const newIndex = itemIndex++;
    
    const itemHTML = `
        <div class="item-row bg-base-200 p-4 rounded-lg mb-3" data-index="${newIndex}">
            <div class="flex justify-between items-center mb-3">
                <span class="font-semibold">Item #${newIndex + 1}</span>
                <button type="button" class="btn btn-sm btn-ghost btn-circle" onclick="removeItem(${newIndex})">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="form-control w-full">
                    <label class="label py-1">
                        <span class="label-text text-xs">Barang</span>
                    </label>
                    <select name="items[${newIndex}][id_barang]" class="select select-bordered select-sm select-primary w-full barang-select" onchange="updateHargaBeli(${newIndex})" required>
                        <option value="">Pilih Barang</option>
                        ${barangData.map(b => `<option value="${b.id}" data-harga="${b.harga}" data-satuan="${b.satuan}">${b.nama_brg} (${b.satuan})</option>`).join('')}
                    </select>
                </div>
                <div class="form-control w-full">
                    <label class="label py-1">
                        <span class="label-text text-xs">Qty</span>
                    </label>
                    <input 
                        type="number" 
                        name="items[${newIndex}][qty]" 
                        placeholder="0" 
                        class="input input-bordered input-sm input-primary w-full qty-input" 
                        min="0.01" 
                        step="0.01"
                        onchange="calculateSubtotal(${newIndex})"
                        required 
                    />
                </div>
                <div class="form-control w-full">
                    <label class="label py-1">
                        <span class="label-text text-xs">Harga Beli (Rp)</span>
                    </label>
                    <input 
                        type="number" 
                        name="items[${newIndex}][harga_beli]" 
                        placeholder="0" 
                        class="input input-bordered input-sm input-primary w-full harga-input" 
                        min="0"
                        onchange="calculateSubtotal(${newIndex})"
                        required 
                    />
                </div>
            </div>
            <div class="mt-2 text-right">
                <span class="text-sm">Subtotal: <strong class="subtotal-display">Rp 0</strong></span>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', itemHTML);
    updateSummary();
}

function removeItem(index) {
    const item = document.querySelector(`.item-row[data-index="${index}"]`);
    if (item) {
        item.remove();
        updateSummary();
        renumberItems();
    }
}

function renumberItems() {
    const items = document.querySelectorAll('.item-row');
    items.forEach((item, idx) => {
        const label = item.querySelector('.font-semibold');
        if (label) label.textContent = `Item #${idx + 1}`;
    });
}

function updateHargaBeli(index) {
    const row = document.querySelector(`.item-row[data-index="${index}"]`);
    const select = row.querySelector('.barang-select');
    const hargaInput = row.querySelector('.harga-input');
    
    const selectedOption = select.options[select.selectedIndex];
    const harga = selectedOption.getAttribute('data-harga');
    
    if (harga) {
        hargaInput.value = harga;
        calculateSubtotal(index);
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
    let totalHarga = 0;
    
    items.forEach(item => {
        const qty = parseFloat(item.querySelector('.qty-input').value) || 0;
        const harga = parseFloat(item.querySelector('.harga-input').value) || 0;
        
        if (qty > 0 && harga > 0) {
            totalItems++;
            totalHarga += (qty * harga);
        }
    });
    
    document.getElementById('total_items').textContent = totalItems;
    document.getElementById('total_harga').textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
}

// Form validation
document.getElementById('form_pembelian').addEventListener('submit', function(e) {
    const items = document.querySelectorAll('.item-row');
    let hasValidItem = false;
    
    items.forEach(item => {
        const barang = item.querySelector('.barang-select').value;
        const qty = parseFloat(item.querySelector('.qty-input').value) || 0;
        const harga = parseFloat(item.querySelector('.harga-input').value) || 0;
        
        if (barang && qty > 0 && harga > 0) {
            hasValidItem = true;
        }
    });
    
    if (!hasValidItem) {
        e.preventDefault();
        alert('Minimal harus ada 1 item barang yang valid!');
        return false;
    }
});
</script>

<?= $this->endSection() ?>