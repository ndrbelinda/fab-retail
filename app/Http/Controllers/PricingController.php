<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Capacity;
use App\Models\RiwayatPricingKapasitas;

class PricingController extends Controller
{
    /**
     * Menampilkan halaman pricing.
     */
        public function kapasitas()
    {
        // Ambil data kapasitas beserta semua riwayat pricing
        $kapasitas = Capacity::with('riwayatPricing') // Ambil semua riwayat pricing
            ->where('is_verified_kapasitas', 'diverifikasi')
            ->get();

        return view('pricing.kapasitas', [
            'title' => 'Pricing',
            'kapasitas' => $kapasitas,
        ]);
    }

    /**
     * Memperbarui pricing kapasitas.
     */
    public function updatePricingKapasitas(Request $request, $id)
    {
        // Ambil data kapasitas
        $kapasitas = Capacity::findOrFail($id);

        // Validasi input
        $request->validate([
            'pricing' => [
                'required',
                'numeric',
                'min:' . $kapasitas->tarif_kapasitas, // Pricing tidak boleh kurang dari tarif
            ],
        ], [
            'pricing.min' => 'Pricing tidak boleh kurang dari tarif.', // Pesan error khusus
        ]);

        // Simpan riwayat pricing
        RiwayatPricingKapasitas::create([
            'kapasitas_id' => $kapasitas->id,
            'pricing' => $request->pricing, // Ubah dari nominal ke pricing
        ]);

        return redirect()->route('pricing.index')->with('success', 'Pricing berhasil diperbarui!');
    }

    public function perangkat(){
        
    }
}