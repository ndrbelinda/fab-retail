<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BerandaController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Verifikasi token JWT dari cookie
            $token = $request->cookie('jwt_token');
            
            if (!$token) {
                throw new \Exception('Token not found');
            }

            // Dapatkan data user dari API
            $apiResponse = Http::withToken($token)
                ->get(config('app.url').'/api/auth/me');
            
            if (!$apiResponse->successful()) {
                throw new \Exception('Failed to fetch user data');
            }

            $userData = $apiResponse->json()['user'] ?? null;
            
            if (!$userData) {
                throw new \Exception('User data not found');
            }

            return view('beranda', [
                'user' => (object)$userData,
                'title' => 'Dashboard'
            ]);

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withCookie(cookie()->forget('jwt_token'))
                ->withErrors(['message' => 'Session expired. Please login again.']);
        }
    }
}