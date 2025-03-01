<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KapasitasController;
use App\Http\Controllers\PerangkatController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\PricingPerangkatController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () { return redirect()->route('login'); });

Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');


// Route Login & Logout
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Grup Route Kapasitas
Route::prefix('kapasitas')->middleware('auth')->group(function () {
    Route::get('/', [KapasitasController::class, 'index'])->name('kapasitas.index');
    Route::get('/tambah', [KapasitasController::class, 'create'])->name('kapasitas.create');
    Route::post('/simpan', [KapasitasController::class, 'store'])->name('kapasitas.store');
    Route::get('/ubah/{id}', [KapasitasController::class, 'edit'])->name('kapasitas.edit');
    Route::put('/perbarui/{id}', [KapasitasController::class, 'update'])->name('kapasitas.update');
    Route::delete('/hapus/{id}', [KapasitasController::class, 'destroy'])->name('kapasitas.destroy');
    Route::get('/verifikasi', [KapasitasController::class, 'verifyindex'])->name('kapasitas.verify');
    Route::put('/verifikasi/{id}', [KapasitasController::class, 'updateVerification'])->name('kapasitas.updateVerification');
    Route::post('/terima/{id}', [KapasitasController::class, 'terima'])->name('kapasitas.terima');
    Route::post('/tolak/{id}', [KapasitasController::class, 'tolak'])->name('kapasitas.tolak');
    Route::post('/kembalikan/{id}', [KapasitasController::class, 'kembalikan'])->name('kapasitas.kembalikan');
});

// Grup Route Perangkat
Route::prefix('perangkat')->middleware('auth')->group(function () {
    Route::get('/', [PerangkatController::class, 'index'])->name('perangkat.index');
    Route::get('/tambah', [PerangkatController::class, 'create'])->name('perangkat.create');
    Route::post('/simpan', [PerangkatController::class, 'store'])->name('perangkat.store');
    Route::get('/ubah/{id}', [PerangkatController::class, 'edit'])->name('perangkat.edit');
    Route::put('/perbarui/{id}', [PerangkatController::class, 'update'])->name('perangkat.update');
    Route::delete('/hapus/{id}', [PerangkatController::class, 'destroy'])->name('perangkat.destroy');
    Route::get('/verifikasi', [PerangkatController::class, 'verifyindex'])->name('perangkat.verify');
    Route::put('/verifikasi/{id}', [PerangkatController::class, 'updateVerification'])->name('perangkat.updateVerification');
    Route::post('/terima/{id}', [PerangkatController::class, 'terima'])->name('perangkat.terima');
    Route::post('/tolak/{id}', [PerangkatController::class, 'tolak'])->name('perangkat.tolak');
    Route::post('/kembalikan/{id}', [PerangkatController::class, 'kembalikan'])->name('perangkat.kembalikan');
});

// Grup Route FAQ
Route::prefix('faq')->middleware('auth')->group(function () {
    Route::get('/', [FaqController::class, 'index'])->name('faq.index');
    Route::get('/tambah', [FaqController::class, 'create'])->name('faq.create');
    Route::post('/simpan', [FaqController::class, 'store'])->name('faq.store');
    Route::get('/ubah/{id}', [FaqController::class, 'edit'])->name('faq.edit');
    Route::put('/perbarui/{id}', [FaqController::class, 'update'])->name('faq.update');
    Route::delete('/hapus/{id}', [FaqController::class, 'destroy'])->name('faq.destroy');
    Route::get('/verifikasi', [FaqController::class, 'verifyIndex'])->name('faq.verify');
    Route::post('/terima/{id}', [FaqController::class, 'terima'])->name('faq.terima');
    Route::post('/tolak/{id}', [FaqController::class, 'tolak'])->name('faq.tolak');
    Route::post('/kembalikan/{id}', [FaqController::class, 'kembalikan'])->name('faq.kembalikan');
});

// Grup Route Pricing
Route::prefix('pricing')->middleware('auth')->group(function () {
    Route::get('/', [PricingController::class, 'kapasitas'])->name('pricing.kapasitas');
    Route::get('/perangkat', [PricingPerangkatController::class, 'perangkat'])->name('pricing.perangkat');
    Route::put('/kapasitas/{id}', [PricingController::class, 'updatePricingKapasitas'])->name('pricing.kapasitas.update');
    Route::put('/perangkat/{id}', [PricingPerangkatController::class, 'updatePricingPerangkat'])->name('pricing.perangkat.update');
});
