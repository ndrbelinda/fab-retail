<?php

// app/Http/Controllers/KapasitasVerifyController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Capacity;
use App\Models\RiwayatKapasitas;

class KapasitasVerifyController extends Controller
{
    /**
     * Menampilkan daftar kapasitas yang perlu diverifikasi.
     */
    public function index()
    {
        $kapasitas = Capacity::where('is_verified_kapasitas', 'diajukan')->get();
        return view('kapasitas.verify.index', [
            'title' => 'Menunggu Verifikasi',
            'kapasitas' => $kapasitas,
        ]);
    }

    /**
     * Menerima kapasitas (ubah status menjadi diverifikasi).
     */
    public function terima($id)
    {
        $kapasitas = Capacity::findOrFail($id);
        $kapasitas->update([
            'is_verified_kapasitas' => 'diverifikasi',
        ]);

        // Simpan riwayat
        RiwayatKapasitas::create([
            'kapasitas_id' => $kapasitas->id,
            'status' => 'diverifikasi',
            'waktu' => now(),
        ]);

        return redirect()->route('kapasitas.verify.index')->with('success', 'Kapasitas berhasil diverifikasi!');
    }

    /**
     * Menolak kapasitas (ubah status menjadi ditolak).
     */
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string',
        ]);

        $kapasitas = Capacity::findOrFail($id);
        $kapasitas->update([
            'is_verified_kapasitas' => 'ditolak',
        ]);

        // Simpan riwayat
        RiwayatKapasitas::create([
            'kapasitas_id' => $kapasitas->id,
            'status' => 'ditolak',
            'waktu' => now(),
            'keterangan' => $request->alasan_penolakan,
        ]);

        return redirect()->route('kapasitas.verify.index')->with('success', 'Kapasitas berhasil ditolak!');
    }
}