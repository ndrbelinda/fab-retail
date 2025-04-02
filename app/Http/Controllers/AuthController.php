<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{

    //login
    public function showLoginForm()
    {
        try {
            // Hanya tampilkan view tanpa logika tambahan
            return view('auth.login');
            
        } catch (\Exception $e) {
            // Log error sederhana
            error_log('Error rendering login page: ' . $e->getMessage());
            
            // Fallback response
            return response('Login page is unavailable', 500)
                ->header('Content-Type', 'text/plain');
        }
    }

    //logout
    public function logoutWeb(Request $request)
    {
        $token = $request->cookie('jwt_token');
        
        if ($token) {
            Http::withToken($token)
                ->post(config('app.url').'/api/auth/logout');
        }

        return redirect('/login')
            ->withCookie(cookie()->forget('jwt_token'));
    }
}