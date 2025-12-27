<?= $this->extend('layout_main') ?>
<?= $this->section('content') ?>

<section class="bg-gradient-to-b from-base-100 to-base-200 py-20">
    <div class="max-w-6xl mx-auto px-6">

        <div class="text-center mb-20">
            <h1 class="text-5xl font-bold mb-6">Tentang Website</h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Website <span class="font-semibold text-primary">Toko Sembako Nawir</span> adalah
                solusi digital untuk membantu pengelolaan transaksi, stok barang, dan laporan penjualan
                pada toko sembako skala kecil.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-24">
            <div class="group">
                <div class="card bg-base-100 shadow-lg hover:border-primary hover:shadow-xl transition-all duration-300 border border-base-300 h-full">
                    <div class="card-body p-8">
                        <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-primary">
                                <path fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 0 1 8.25-8.25.75.75 0 0 1 .75.75v6.75H18a.75.75 0 0 1 .75.75 8.25 8.25 0 0 1-16.5 0Z" clip-rule="evenodd" />
                                <path fill-rule="evenodd" d="M12.75 3a.75.75 0 0 1 .75-.75 8.25 8.25 0 0 1 8.25 8.25.75.75 0 0 1-.75.75h-7.5a.75.75 0 0 1-.75-.75V3Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold mb-3">Tujuan Pengembangan</h2>
                        <p class="text-gray-600 leading-relaxed">
                            Aplikasi ini dirancang dengan antarmuka yang intuitif dan mudah digunakan
                            oleh pemilik toko, dengan fitur yang fokus pada kebutuhan utama seperti
                            pencatatan penjualan dan pengelolaan stok barang.
                        </p>
                    </div>
                </div>
            </div>

            <div class="group">
                <div class="card bg-base-100 shadow-lg hover:shadow-xl hover:border-primary transition-all duration-300 border border-base-300 h-full">
                    <div class="card-body p-8">
                        <div class="w-12 h-12 bg-success/10 rounded-lg flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-success">
                                <path fill-rule="evenodd" d="M2.25 6a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V6Zm3.97.97a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06l-2.25 2.25a.75.75 0 0 1-1.06-1.06l1.72-1.72-1.72-1.72a.75.75 0 0 1 0-1.06Zm4.28 4.28a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5h-3Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold mb-3">Manfaat Sistem</h2>
                        <p class="text-gray-600 leading-relaxed">
                            Meminimalkan pencatatan manual, mengurangi kesalahan data,
                            mempercepat proses transaksi, serta mempermudah pembuatan laporan
                            penjualan secara otomatis dan akurat.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-24">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-3">Tech Stack</h2>
                <p class="text-gray-600">Teknologi modern yang kami gunakan</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <?php
                $tech = [
                    ['name' => 'PHP', 'color' => 'bg-purple-500'],
                    ['name' => 'CodeIgniter 4', 'color' => 'bg-red-500'],
                    ['name' => 'MySQL', 'color' => 'bg-blue-500'],
                    ['name' => 'Tailwind CSS', 'color' => 'bg-cyan-500'],
                    ['name' => 'DaisyUI 4', 'color' => 'bg-orange-500'],
                    ['name' => 'JavaScript', 'color' => 'bg-yellow-500']
                ];
?>
                <?php foreach ($tech as $t): ?>
                    <div class="group">
                        <div class="card bg-base-100 border border-base-300 hover:border-primary hover:shadow-lg transition-all duration-300">
                            <div class="card-body p-6 items-center">
                                <div class="w-3 h-3 rounded-full <?= $t['color'] ?> mb-3"></div>
                                <span class="font-semibold text-sm text-center"><?= $t['name'] ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
        <div>
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-3">Tim Pengembang</h2>
                <p class="text-gray-600">Orang-orang di balik layar</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php
$team = [
    ['nama' => 'Fauzan Priatmana', 'nim' => '24.240.0027', 'color' => 'bg-blue-500'],
    ['nama' => 'Muhammad Ichsan', 'nim' => '24.240.0028', 'color' => 'bg-green-500'],
    ['nama' => 'Nadhifatunnizza', 'nim' => '24.240.0064', 'color' => 'bg-purple-500'],
    ['nama' => 'Imel Aimanda Bregawati', 'nim' => '24.240.0080', 'color' => 'bg-pink-500'],
];
?>

                <?php foreach ($team as $m): ?>
                    <div class="group">
                        <div class="card bg-base-100 border border-base-300 hover:border-primary hover:shadow-xl transition-all duration-300">
                            <div class="card-body p-6 items-center text-center">
                                <div class="avatar placeholder mb-4">
                                    <div class="<?= $m['color'] ?> text-white rounded-full w-20 h-20 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        <span class="text-2xl font-bold">
                                            <?= initial_nama($m['nama']) ?>
                                        </span>
                                    </div>
                                </div>
                                <h3 class="font-bold text-lg"><?= $m['nama'] ?></h3>
                                <p class="text-sm text-gray-600 mt-1 font-semibold"><?= $m['nim'] ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>

    </div>
</section>

<?= $this->endSection() ?>