<?php

// app/Http/Controllers/BerandaController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BerandaController extends Controller
{
    /**
     * Menampilkan halaman beranda.
     */
    public function index()
    {
        // Kirim hanya judul ke view beranda
        return view('beranda', [
            'title' => 'Selamat Datang di FAB Retail',
        ]);
    }
}