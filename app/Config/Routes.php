<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/about', 'Home::about');

$routes->get('/login', 'LoginController::index');
$routes->post('/login', 'LoginController::validateLogin');
$routes->get('/logout', 'LoginController::logout');

$routes->get('/dashboard', 'DashboardController::index', [
    'filter' => 'role:pemilik,kasir'
]);

$routes->group('', ['filter' => 'role:kasir'], function ($routes) {
    $routes->group('penjualan', function ($routes) {
        $routes->get('create', 'PenjualanController::create');
        $routes->post('store', 'PenjualanController::store');
    });
    $routes->group('riwayat', function ($routes) {
        $routes->get('/', 'PenjualanController::index');
    });
});
$routes->group('', ['filter' => 'role:pemilik,kasir'], function ($routes) {
    $routes->group('riwayat', function ($routes) {
        $routes->get('detail/(:segment)', 'PenjualanController::detil/$1');
    });
});
$routes->group('', ['filter' => 'role:pemilik'], function ($routes) {

    $routes->group('penjualan', function ($routes) {
        $routes->get('edit', 'PenjualanController::showEdit');
        $routes->post('update', 'PenjualanController::updateStatus');
    });
    $routes->group('laporan', function ($routes) {
        $routes->get('penjualan', 'LaporanController::penjualan');
        $routes->get('pembelian', 'LaporanController::pembelian');
    });
    $routes->group('kategori', function ($routes) {
        $routes->get('/', 'KategoriController::index');
        $routes->post('store', 'KategoriController::store');
        $routes->post('update', 'KategoriController::update');
    });

    $routes->group('users', function ($routes) {
        $routes->get('/', 'UserController::index');
        $routes->post('store', 'UserController::store');
        $routes->post('update', 'UserController::update');
    });

    $routes->group('supplier', function ($routes) {
        $routes->get('/', 'SupplierController::index');
        $routes->post('store', 'SupplierController::store');
        $routes->post('update', 'SupplierController::update');
    });

    $routes->group('barang', function ($routes) {
        $routes->get('/', 'BarangController::index');
        $routes->post('store', 'BarangController::store');
        $routes->post('update', 'BarangController::update');
    });

    $routes->group('pembelian', function ($routes) {
        $routes->get('/', 'PembelianController::index');
        $routes->get('create', 'PembelianController::create');
        $routes->post('store', 'PembelianController::store');
        $routes->get('detail/(:segment)', 'PembelianController::detail/$1');
        $routes->post('delete', 'PembelianController::delete');
    });

    $routes->group('laporan', function ($routes) {
        $routes->get('pembelian', 'LaporanController::pembelian');
        $routes->get('penjualan', 'LaporanController::penjualan');
    });
});
