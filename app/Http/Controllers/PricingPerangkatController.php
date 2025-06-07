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
        $perangkat = Perangkat::findOrFail($id);

        $request->validate([
            'pricing' => [
                'required',
                'numeric',
                'min:' . $perangkat->tarif_perangkat,
            ],
            'dokumen' => 'nullable|file|mimes:pdf|max:2048', // Max 2MB, hanya PDF
        ], [
            'pricing.min' => 'Pricing tidak boleh kurang dari tarif.',
            'dokumen.mimes' => 'Dokumen harus berupa file PDF.',
            'dokumen.max' => 'Ukuran dokumen tidak boleh lebih dari 2MB.',
        ]);

        $data = [
            'perangkat_id' => $perangkat->id,
            'pricing' => $request->pricing,
        ];

        // Handle dokumen upload
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('dokumen_pricing', $fileName, 'public');
            $data['dokumen'] = $filePath;
        }

        RiwayatPricingPerangkat::create($data);

        return redirect()->route('pricing.perangkat')->with('success', 'Pricing berhasil diperbarui!');
    }

}