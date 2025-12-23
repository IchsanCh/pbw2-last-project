<?php
$role = session('role');
$username = session('username');
$segment = service('uri')->getSegment(1);
?>

<aside class="w-64 min-h-full bg-base-100 border-r flex flex-col">
    <div class="p-4 font-bold bg-base-300 text-lg border-b">
        Toko Nawir
        <div class="text-xs text-primary"><?= esc(ucwords($username).' | '.ucwords($role)) ?></div>
    </div>
    <ul class="menu p-4 gap-1 flex-1">
        <li>
            <a href="<?= base_url('dashboard') ?>" class="<?= $segment === 'dashboard' ? 'active font-bold text-white' : 'text-black hover:bg-black hover:text-white hover:font-semibold' ?>">Dashboard</a>
        </li>
        <?php if ($role === 'pemilik'): ?>
            <li class="menu-title text-black mt-2">Manajemen</li>
            <li>
                <a href="<?= base_url('kategori') ?>" class="<?= $segment === 'kategori' ? 'active font-bold text-white' : 'text-black hover:bg-black hover:text-white hover:font-semibold' ?>">Kategori</a>
            </li>
            <li>
                <a href="<?= base_url('barang') ?>" class="<?= $segment === 'barang' ? 'active font-bold text-white' : 'text-black hover:bg-black hover:text-white hover:font-semibold' ?>">Barang</a>
            </li>
            <li>
                <a href="<?= base_url('supplier') ?>" class="<?= $segment === 'supplier' ? 'active font-bold text-white' : 'text-black hover:bg-black hover:text-white hover:font-semibold' ?>">Supplier</a>
            </li>
            <li>
                <a href="<?= base_url('users') ?>" class="<?= $segment === 'users' ? 'active font-bold text-white' : 'text-black hover:bg-black hover:text-white hover:font-semibold' ?>">User</a>
            </li>
            <li class="menu-title mt-3 text-black">Laporan</li>
            <li>
                <a href="<?= base_url('laporan/penjualan') ?>" class="<?= $segment === 'laporan/penjualan' ? 'active font-bold text-white' : 'text-black hover:bg-black hover:text-white hover:font-semibold' ?>">Penjualan</a>
            </li>
             <li>
                <a href="<?= base_url('laporan/pembelian') ?>" class="<?= $segment === 'laporan/pembelian' ? 'active font-bold text-white' : 'text-black hover:bg-black hover:text-white hover:font-semibold' ?>">Pembelian</a>
            </li>
        <?php endif; ?>
        <?php if ($role === 'kasir'): ?>
            <li class="menu-title mt-2 text-black">Kasir</li>
            <li>
                <a href="<?= base_url('transaksi') ?>">Transaksi</a>
            </li>
            <li>
                <a href="<?= base_url('riwayat') ?>">Riwayat Transaksi</a>
            </li>
        <?php endif; ?>
    </ul>
    <div class="p-4 border-t bg-base-300 hidden lg:block">
        <div class="dropdown dropdown-top w-full">
            <div tabindex="0" role="button" class="btn btn-primary w-full">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>    
                <?= esc(ucwords($username)) ?>
            </div>
            <ul tabindex="0" class="dropdown-content menu bg-gray-800 rounded-box z-[1] w-full p-2 shadow mb-2">
                <li>
                    <a href="<?= base_url('profile') ?>" class="text-info hover:bg-info hover:text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        Profile
                    </a>
                </li>
                <div class="divider my-0 divider-warning"></div>
                <li>
                    <a href="<?= base_url('logout') ?>" class="text-error hover:bg-error hover:text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>    
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
