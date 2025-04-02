<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\BerandaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Pure JWT API Routes
| All endpoints are prefixed with '/api' by default
|
*/

/*
 * Public Routes (No Authentication Required)
 */
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']); // Jika ada fitur registrasi
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']); // Jika ada fitur lupa password
});

/*
 * Authenticated Routes (JWT Required)
 */
Route::middleware(['auth:api'])->group(function () {
    /*
     * Authentication Management
     */
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    /*
     * Application Routes
     */
    Route::middleware('auth:api')->get('/beranda', [BerandaController::class, 'index']);

    /*
     * Profile Management
     */
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/', [ProfileController::class, 'update']);
        Route::post('/change-password', [ProfileController::class, 'changePassword']);
        Route::delete('/', [ProfileController::class, 'destroy']);
    });

    /*
     * Example Resource Routes
     */
    // Route::apiResource('users', UserController::class);
    // Route::apiResource('products', ProductController::class);
});

/*
 * Fallback for Undefined API Endpoints
 */
Route::fallback(function () {
    return response()->json([
        'status' => 'error',
        'message' => 'Endpoint not found',
        'available_endpoints' => [
            'auth' => [
                'POST /api/auth/login',
                'POST /api/auth/register',
                'POST /api/auth/forgot-password',
                'POST /api/auth/logout (authenticated)',
                'POST /api/auth/refresh (authenticated)',
                'GET /api/auth/me (authenticated)'
            ],
            'profile' => [
                'GET /api/profile',
                'PUT /api/profile',
                'POST /api/profile/change-password',
                'DELETE /api/profile'
            ]
        ]
    ], 404);
});