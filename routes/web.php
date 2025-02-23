<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KapasitasController;
use App\Http\Controllers\PerangkatController;
use App\Http\Controllers\FaqController;
// use App\Http\Controllers\PricingController;

// Beranda
Route::get('/', [BerandaController::class, 'index'])->name('beranda');

// Grup Route Kapasitas
Route::prefix('kapasitas')->group(function () {
    // Route untuk menampilkan daftar kapasitas
    Route::get('/', [KapasitasController::class, 'index'])->name('kapasitas.index');

    // Route untuk menampilkan form tambah kapasitas
    Route::get('/tambah', [KapasitasController::class, 'create'])->name('kapasitas.create');

    // Route untuk menyimpan data kapasitas baru
    Route::post('/simpan', [KapasitasController::class, 'store'])->name('kapasitas.store');

    // Route untuk menampilkan form edit kapasitas
    Route::get('/ubah/{id}', [KapasitasController::class, 'edit'])->name('kapasitas.edit');

    // Route untuk memperbarui data kapasitas
    Route::put('/perbarui/{id}', [KapasitasController::class, 'update'])->name('kapasitas.update');

    // Route untuk menghapus data kapasitas
    Route::delete('/hapus/{id}', [KapasitasController::class, 'destroy'])->name('kapasitas.destroy');

    // Route untuk menampilkan halaman verifikasi kapasitas
    Route::get('/verifikasi', [KapasitasController::class, 'verifyindex'])->name('kapasitas.verify');

    // Route untuk memperbarui status verifikasi kapasitas
    Route::put('/verifikasi/{id}', [KapasitasController::class, 'updateVerification'])->name('kapasitas.updateVerification');

    // Route untuk menerima kapasitas (ubah status menjadi diverifikasi)
    Route::post('/terima/{id}', [KapasitasController::class, 'terima'])->name('kapasitas.terima');

    // Route untuk menolak kapasitas (ubah status menjadi ditolak)
    Route::post('/tolak/{id}', [KapasitasController::class, 'tolak'])->name('kapasitas.tolak');

    // Route untuk mengembalikan kapasitas ke status draft
    Route::post('/kembalikan/{id}', [KapasitasController::class, 'kembalikan'])->name('kapasitas.kembalikan');
});

// Grup Route Perangkat
Route::prefix('perangkat')->group(function () {
    // Route untuk menampilkan daftar kapasitas
    Route::get('/', [PerangkatController::class, 'index'])->name('perangkat.index');

    // Route untuk menampilkan form tambah kapasitas
    Route::get('/tambah', [PerangkatController::class, 'create'])->name('perangkat.create');

    // Route untuk menyimpan data kapasitas baru
    Route::post('/simpan', [PerangkatController::class, 'store'])->name('perangkat.store');

    // Route untuk menampilkan form edit kapasitas
    Route::get('/ubah/{id}', [PerangkatController::class, 'edit'])->name('perangkat.edit');

    // Route untuk memperbarui data kapasitas
    Route::put('/perbarui/{id}', [PerangkatController::class, 'update'])->name('perangkat.update');

    // Route untuk menghapus data kapasitas
    Route::delete('/hapus/{id}', [PerangkatController::class, 'destroy'])->name('perangkat.destroy');

    // Route untuk menampilkan halaman verifikasi kapasitas
    Route::get('/verifikasi', [PerangkatController::class, 'verifyindex'])->name('perangkat.verify');

    // Route untuk memperbarui status verifikasi kapasitas
    Route::put('/verifikasi/{id}', [PerangkatController::class, 'updateVerification'])->name('perangkat.updateVerification');

    // Route untuk menerima kapasitas (ubah status menjadi diverifikasi)
    Route::post('/terima/{id}', [PerangkatController::class, 'terima'])->name('perangkat.terima');

    // Route untuk menolak kapasitas (ubah status menjadi ditolak)
    Route::post('/tolak/{id}', [PerangkatController::class, 'tolak'])->name('perangkat.tolak');

    // Route untuk mengembalikan kapasitas ke status draft
    Route::post('/kembalikan/{id}', [PerangkatController::class, 'kembalikan'])->name('perangkat.kembalikan');
});

// Grup Route FAQ
Route::prefix('faq')->group(function () {
    // Route untuk menampilkan daftar FAQ
    Route::get('/', [FaqController::class, 'index'])->name('faq.index');

    // Route untuk menampilkan form tambah FAQ
    Route::get('/tambah', [FaqController::class, 'create'])->name('faq.create');

    // Route untuk menyimpan data FAQ baru
    Route::post('/simpan', [FaqController::class, 'store'])->name('faq.store');

    // Route untuk menampilkan form edit FAQ
    Route::get('/ubah/{id}', [FaqController::class, 'edit'])->name('faq.edit');

    // Route untuk memperbarui data FAQ
    Route::put('/perbarui/{id}', [FaqController::class, 'update'])->name('faq.update');

    // Route untuk menghapus data FAQ
    Route::delete('/hapus/{id}', [FaqController::class, 'destroy'])->name('faq.destroy');

    // Route untuk menampilkan halaman verifikasi FAQ
    Route::get('/verifikasi', [FaqController::class, 'verifyIndex'])->name('faq.verify');

    // Route untuk menerima FAQ (ubah status menjadi diverifikasi)
    Route::post('/terima/{id}', [FaqController::class, 'terima'])->name('faq.terima');

    // Route untuk menolak FAQ (ubah status menjadi ditolak)
    Route::post('/tolak/{id}', [FaqController::class, 'tolak'])->name('faq.tolak');

    // Route untuk mengembalikan FAQ ke status draft
    Route::post('/kembalikan/{id}', [FaqController::class, 'kembalikan'])->name('faq.kembalikan');
});


// // Grup Route Pricing
// Route::prefix('pricing')->group(function () {
//     Route::get('/kapasitasinternet', [PricingController::class, 'kapasitasInternet'])->name('pricing.kapasitas');
//     Route::get('/perangkat', [PricingController::class, 'perangkat'])->name('pricing.perangkat');
// });