<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KapasitasController;
use App\Http\Controllers\PerangkatController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\PricingPerangkatController;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== PUBLIC ROUTES ====================
Route::redirect('/', '/login');

// Authentication Routes
Route::middleware('web')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    
    // Login via API (Cookie-based)
    Route::post('/login', function (Request $request) {
        $response = Http::post(config('app.url') . '/api/auth/login', [
            'username' => $request->username,
            'password' => $request->password
        ]);

        $data = $response->json();

        if ($response->successful()) {
            return redirect('/beranda')
                ->withCookie(cookie(
                    'jwt_token',
                    $data['access_token'],
                    60 * 24 * 7, // 7 hari
                    '/',
                    '.fabretail.test',
                    false, // secure
                    true   // httpOnly
                ));
        }

        return back()->withErrors(['message' => 'Login gagal']);
    });
});

// ==================== PROTECTED ROUTES (JWT) ====================
Route::middleware([\App\Http\Middleware\AuthenticateWithJWT::class])->group(function () {
    // Ganti callback dengan controller
    Route::get('/beranda', [BerandaController::class, 'index']);
    
    // Logout yang lebih robust
    Route::post('/logout', [AuthController::class, 'logoutWeb'])->name('logout');

    // Kapasitas Routes
    Route::prefix('kapasitas')->controller(KapasitasController::class)->group(function () {
        Route::get('/', 'index')->name('kapasitas.index');
        Route::get('/tambah', 'create')->name('kapasitas.create');
        Route::post('/simpan', 'store')->name('kapasitas.store');
        Route::get('/ubah/{id}', 'edit')->name('kapasitas.edit');
        Route::put('/perbarui/{id}', 'update')->name('kapasitas.update');
        Route::delete('/hapus/{id}', 'destroy')->name('kapasitas.destroy');
        Route::get('/verifikasi', 'verifyindex')->name('kapasitas.verify');
        Route::put('/verifikasi/{id}', 'updateVerification')->name('kapasitas.updateVerification');
        Route::post('/terima/{id}', 'terima')->name('kapasitas.terima');
        Route::post('/tolak/{id}', 'tolak')->name('kapasitas.tolak');
        Route::post('/kembalikan/{id}', 'kembalikan')->name('kapasitas.kembalikan');
    });

    // Perangkat Routes
    Route::prefix('perangkat')->controller(PerangkatController::class)->group(function () {
        Route::get('/', 'index')->name('perangkat.index');
        Route::get('/tambah', 'create')->name('perangkat.create');
        Route::post('/simpan', 'store')->name('perangkat.store');
        Route::get('/ubah/{id}', 'edit')->name('perangkat.edit');
        Route::put('/perbarui/{id}', 'update')->name('perangkat.update');
        Route::delete('/hapus/{id}', 'destroy')->name('perangkat.destroy');
        Route::get('/verifikasi', 'verifyindex')->name('perangkat.verify');
        Route::put('/verifikasi/{id}', 'updateVerification')->name('perangkat.updateVerification');
        Route::post('/terima/{id}', 'terima')->name('perangkat.terima');
        Route::post('/tolak/{id}', 'tolak')->name('perangkat.tolak');
        Route::post('/kembalikan/{id}', 'kembalikan')->name('perangkat.kembalikan');
    });

    // FAQ Routes
    Route::prefix('faq')->controller(FaqController::class)->group(function () {
        Route::get('/', 'index')->name('faq.index');
        Route::get('/tambah', 'create')->name('faq.create');
        Route::post('/simpan', 'store')->name('faq.store');
        Route::get('/ubah/{id}', 'edit')->name('faq.edit');
        Route::put('/perbarui/{id}', 'update')->name('faq.update');
        Route::delete('/hapus/{id}', 'destroy')->name('faq.destroy');
        Route::get('/verifikasi', 'verifyIndex')->name('faq.verify');
        Route::post('/terima/{id}', 'terima')->name('faq.terima');
        Route::post('/tolak/{id}', 'tolak')->name('faq.tolak');
        Route::post('/kembalikan/{id}', 'kembalikan')->name('faq.kembalikan');
    });

    // Pricing Routes
    Route::prefix('pricing')->group(function () {
        Route::get('/', [PricingController::class, 'kapasitas'])->name('pricing.kapasitas');
        Route::get('/perangkat', [PricingPerangkatController::class, 'perangkat'])->name('pricing.perangkat');
        Route::put('/kapasitas/{id}', [PricingController::class, 'updatePricingKapasitas'])->name('pricing.kapasitas.update');
        Route::put('/perangkat/{id}', [PricingPerangkatController::class, 'updatePricingPerangkat'])->name('pricing.perangkat.update');
    });
});

// ==================== FALLBACK ROUTE ====================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});