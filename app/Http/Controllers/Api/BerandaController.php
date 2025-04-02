<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class BerandaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'message' => 'Selamat Datang di FAB Retail',
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'email' => $user->email,
                        'role' => $user->role
                    ],
                    'meta' => [
                        'token_expiry' => JWTAuth::getPayload()->get('exp')
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 401);
        }
    }
}