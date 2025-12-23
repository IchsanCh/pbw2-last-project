<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', 'LoginController::index');
$routes->post('/login', 'LoginController::validateLogin');
$routes->get('/logout', 'LoginController::logout');
$routes->get('/dashboard', 'DashboardController::index', [
    'filter' => 'role:pemilik,kasir'
]);
$routes->get('/kategori', 'KategoriController::index', [
    'filter' => 'role:pemilik'
]);
$routes->post('/kategori/store', 'KategoriController::store', [
    'filter' => 'role:pemilik'
]);
$routes->post('/kategori/update', 'KategoriController::update', [
    'filter' => 'role:pemilik'
]);
$routes->get('/users', 'UserController::index', [
    'filter' => 'role:pemilik'
]);
$routes->post('/users/store', 'UserController::store', [
    'filter' => 'role:pemilik'
]);
$routes->post('/users/update', 'UserController::update', [
    'filter' => 'role:pemilik'
]);
$routes->get('/supplier', 'SupplierController::index', [
    'filter' => 'role:pemilik'
]);
$routes->post('/supplier/store', 'SupplierController::store', [
    'filter' => 'role:pemilik'
]);
$routes->post('/supplier/update', 'SupplierController::update', [
    'filter' => 'role:pemilik'
]);
$routes->get('/barang', 'BarangController::index', [
    'filter' => 'role:pemilik'
]);
$routes->post('/barang/store', 'BarangController::store', [
    'filter' => 'role:pemilik'
]);
$routes->post('/barang/update', 'BarangController::update', [
    'filter' => 'role:pemilik'
]);
