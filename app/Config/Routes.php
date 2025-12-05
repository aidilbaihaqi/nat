<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', 'Home::index');

// ============================================
// Authentication Routes
// ============================================
$routes->group('', ['filter' => 'noauth'], function($routes) {
    $routes->get('login', 'AuthController::showLogin');
    $routes->post('login', 'AuthController::login');
});

$routes->get('logout', 'AuthController::logout', ['filter' => 'auth']);

// ============================================
// Admin Routes
// ============================================
$routes->group('admin', ['filter' => ['auth', 'role:admin']], function($routes) {
    // Mahasiswa CRUD
    $routes->resource('mahasiswa', [
        'controller' => 'Admin\MahasiswaController',
        'placeholder' => '(:segment)',
        'except' => 'show'
    ]);
    
    // Dosen CRUD
    $routes->resource('dosen', [
        'controller' => 'Admin\DosenController',
        'placeholder' => '(:segment)',
        'except' => 'show'
    ]);
    
    // Mata Kuliah CRUD
    $routes->resource('mata-kuliah', [
        'controller' => 'Admin\MataKuliahController',
        'placeholder' => '(:num)',
        'except' => 'show'
    ]);
    
    // Ruangan CRUD
    $routes->resource('ruangan', [
        'controller' => 'Admin\RuanganController',
        'placeholder' => '(:num)',
        'except' => 'show'
    ]);
    
    // Jadwal CRUD
    $routes->resource('jadwal', [
        'controller' => 'Admin\JadwalController',
        'placeholder' => '(:num)',
        'except' => 'show'
    ]);
});

// ============================================
// Mahasiswa Routes
// ============================================
$routes->group('mhs', ['filter' => ['auth', 'role:mahasiswa']], function($routes) {
    // KRS Management
    $routes->get('krs', 'Mahasiswa\KRSController::index');
    $routes->post('krs', 'Mahasiswa\KRSController::store');
    $routes->delete('krs/(:num)', 'Mahasiswa\KRSController::delete/$1');
    
    // Hasil Studi
    $routes->get('hasil-studi', 'Mahasiswa\HasilStudiController::index');
});

// ============================================
// Dosen Routes
// ============================================
$routes->group('dosen', ['filter' => ['auth', 'role:dosen']], function($routes) {
    // Jadwal
    $routes->get('jadwal', 'Dosen\JadwalController::index');
    
    // Nilai Management
    $routes->get('nilai/(:num)', 'Dosen\NilaiController::show/$1');
    $routes->post('nilai/(:num)', 'Dosen\NilaiController::update/$1');
});
