<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perangkat;
use App\Models\RiwayatPricingPerangkat;

class PricingPerangkatController extends Controller
{
    /**
     * Menampilkan halaman pricing.
     */
        public function perangkat()
    {
        // Ambil data perangakt beserta semua riwayat pricing
        $perangkat = Perangkat::with('riwayatPricingPerangkat') // Ambil semua riwayat pricing
            ->where('is_verified_perangkat', 'diverifikasi')
            ->get();

        return view('pricing.perangkat', [
            'title' => 'Pricing',
            'perangkat' => $perangkat,
        ]);
    }

    /**
     * Memperbarui pricing kapasitas.
     */
    public function updatePricingPerangkat(Request $request, $id)
    {
        // Ambil data kapasitas
        $perangkat = Perangkat::findOrFail($id);

        // Validasi input
        $request->validate([
            'pricing' => [
                'required',
                'numeric',
                'min:' . $perangkat->tarif_perangkat, // Pricing tidak boleh kurang dari tarif
            ],
        ], [
            'pricing.min' => 'Pricing tidak boleh kurang dari tarif.', // Pesan error khusus
        ]);

        // Simpan riwayat pricing
        RiwayatPricingPerangkat::create([
            'perangkat_id' => $perangkat->id,
            'pricing' => $request->pricing, // Ubah dari nominal ke pricing
        ]);

        return redirect()->route('pricing.perangkat')->with('success', 'Pricing berhasil diperbarui!');
    }

}