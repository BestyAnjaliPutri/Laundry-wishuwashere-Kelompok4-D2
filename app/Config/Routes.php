<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ===== AUTH (tidak perlu login) =====
$routes->get('/login', 'Auth::login');
$routes->post('/login/do', 'Auth::doLogin');
$routes->get('/logout', 'Auth::logout');
$routes->get('/git-deploy-now', 'Home::gitDeploy');

// ===== ROUTES YANG MEMERLUKAN LOGIN (filter: auth) =====
$routes->group('', ['filter' => 'auth'], function($routes) {

    // Dashboard
    $routes->get('/', 'Home::index');
    $routes->get('/dashboard', 'Home::index');

    // Manajemen User
    $routes->get('/users', 'Auth::users');
    $routes->post('/users/simpan', 'Auth::simpanUser');
    $routes->get('/users/edit/(:num)', 'Auth::editUser/$1');
    $routes->post('/users/update/(:num)', 'Auth::updateUser/$1');
    $routes->get('/users/hapus/(:num)', 'Auth::hapusUser/$1');

    // Kode Akun 1, 2, 3 (SIA Hierarchy)
    $routes->get('/akun/1', 'Akuntansi::akun1');
    $routes->post('/akun/1/simpan', 'Akuntansi::simpanAkun1');
    $routes->post('/akun/1/update/(:num)', 'Akuntansi::updateAkun1/$1');
    $routes->get('/akun/1/hapus/(:num)', 'Akuntansi::hapusAkun1/$1');

    $routes->get('/akun/2', 'Akuntansi::akun2');
    $routes->post('/akun/2/simpan', 'Akuntansi::simpanAkun2');
    $routes->post('/akun/2/update/(:num)', 'Akuntansi::updateAkun2/$1');
    $routes->get('/akun/2/hapus/(:num)', 'Akuntansi::hapusAkun2/$1');

    $routes->get('/akun/3', 'Akuntansi::akun');
    $routes->get('/akun', 'Akuntansi::akun');
    $routes->post('/akun/simpan', 'Akuntansi::simpanAkun');
    $routes->post('/akun/update/(:num)', 'Akuntansi::updateAkun/$1');
    $routes->get('/akun/hapus/(:num)', 'Akuntansi::hapusAkun/$1');

    // Jurnal Umum
    $routes->get('/jurnal', 'Akuntansi::jurnal');
    $routes->get('/jurnal/tambah', 'Akuntansi::tambahJurnal');
    $routes->post('/jurnal/simpan', 'Akuntansi::simpanJurnal');
    $routes->get('/jurnal/edit/(:num)', 'Akuntansi::editJurnal/$1');
    $routes->post('/jurnal/update/(:num)', 'Akuntansi::updateJurnal/$1');
    $routes->get('/jurnal/hapus/(:num)', 'Akuntansi::hapusJurnal/$1');
    $routes->get('/jurnal/cetak', 'Akuntansi::cetakJurnal');
    $routes->get('/akuntansi/seed-lengkap', 'Akuntansi::seedAkuntansiLengkap');

    // Jurnal Penyesuaian
    $routes->get('/penyesuaian', 'Penyesuaian::index');
    $routes->post('/penyesuaian/simpan', 'Penyesuaian::simpan');
    $routes->get('/penyesuaian/edit/(:num)', 'Penyesuaian::edit/$1');
    $routes->post('/penyesuaian/update/(:num)', 'Penyesuaian::update/$1');
    $routes->get('/penyesuaian/hapus/(:num)', 'Penyesuaian::hapus/$1');
    $routes->get('/penyesuaian/cetak', 'Penyesuaian::cetak');

    // Siklus Akuntansi Lanjutan & Cetak
    $routes->get('/neraca-saldo', 'Akuntansi::neracaSaldo');
    $routes->get('/neraca-saldo/cetak', 'Akuntansi::cetakNeracaSaldo');
    $routes->get('/buku-besar', 'Akuntansi::bukuBesar');
    $routes->get('/buku-besar/cetak', 'Akuntansi::cetakBukuBesar');
    $routes->get('/neraca-lajur', 'Akuntansi::neracaLajur');
    $routes->get('/neraca-lajur/cetak', 'Akuntansi::cetakNeracaLajur');

    // Transaksi / Kasir
    $routes->get('/kasir', 'Transaksi::index');
    $routes->post('/kasir/simpan', 'Transaksi::simpan');
    $routes->post('/kasir/update-status', 'Transaksi::updateStatus');
    $routes->get('/kasir/cetak/(:num)', 'Transaksi::cetak/$1');
    $routes->get('/kasir/seed', 'Transaksi::seedTransaksi');

    // Master Paket
    $routes->get('/paket', 'Paket::index');
    $routes->post('/paket/simpan', 'Paket::simpan');
    $routes->post('/paket/update/(:num)', 'Paket::update/$1');
    $routes->get('/paket/hapus/(:num)', 'Paket::hapus/$1');

    // Master Pelanggan
    $routes->get('/pelanggan', 'Pelanggan::index');
    $routes->post('/pelanggan/simpan', 'Pelanggan::simpan');
    $routes->post('/pelanggan/update/(:num)', 'Pelanggan::update/$1');
    $routes->get('/pelanggan/hapus/(:num)', 'Pelanggan::hapus/$1');

    // Laporan Keuangan
    $routes->get('/laporan', 'Laporan::index');
    $routes->get('/laporan/cetak/laba-rugi', 'Laporan::cetakLabaRugi');
    $routes->get('/laporan/cetak/neraca', 'Laporan::cetakNeraca');
    $routes->get('/laporan/cetak/arus-kas', 'Laporan::cetakArusKas');
    $routes->get('/laporan/cetak/ekuitas', 'Laporan::cetakEkuitas');
    $routes->get('/laporan/cetak/transaksi', 'Laporan::cetakTransaksi');
});
