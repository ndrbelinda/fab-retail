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
        $kapasitas = Capacity::with('riwayatPricingKapasitas') // Ambil semua riwayat pricing
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
        $kapasitas = Capacity::findOrFail($id);

        $request->validate([
            'pricing' => [
                'required',
                'integer',
                'min:' . $kapasitas->tarif_kapasitas,
            ],
            'dokumen' => 'nullable|file|mimes:pdf|max:2048', // Max 2MB, hanya PDF
        ], [
            'pricing.min' => 'Pricing tidak boleh kurang dari tarif.',
            'dokumen.mimes' => 'Dokumen harus berupa file PDF.',
            'dokumen.max' => 'Ukuran dokumen tidak boleh lebih dari 2MB.',
        ]);

        $data = [
            'kapasitas_id' => $kapasitas->id,
            'pricing' => (int) $request->pricing,
        ];

        // Handle dokumen upload
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('dokumen_pricing', $fileName, 'public');
            $data['dokumen'] = $filePath;
        }

        RiwayatPricingKapasitas::create($data);

        return redirect()->route('pricing.kapasitas')->with('success', 'Pricing berhasil diperbarui!');
    }

}