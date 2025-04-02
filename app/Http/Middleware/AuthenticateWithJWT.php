<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Http;

class AuthenticateWithJWT
{
    public function handle(Request $request, Closure $next)
    {
        // Skip middleware untuk route login
        if ($request->is('login') || $request->isMethod('post') && $request->is('login')) {
            return $next($request);
        }

        // Lanjutkan tanpa verifikasi JWT untuk halaman login
        return $next($request);
    }
}