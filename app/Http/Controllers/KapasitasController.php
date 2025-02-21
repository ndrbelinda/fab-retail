<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Capacity;
use App\Models\Produk;
use App\Models\RiwayatKapasitas;

class KapasitasController extends Controller
{
    /**
     * Menampilkan daftar kapasitas.
     */
    public function index()
    {
        $kapasitas = Capacity::with(['produk', 'riwayat'])->get();
        return view('kapasitas.index', [
            'title' => 'Daftar Kapasitas',
            'kapasitas' => $kapasitas,
        ]);
    }

    /**
     * Menampilkan form tambah kapasitas.
     */
    public function create()
    {
        $produk = Produk::all();
        return view('kapasitas.create', [
            'title' => 'Tambah Kapasitas Internet',
            'produk' => $produk,
        ]);
    }

    /**
     * Menyimpan data kapasitas baru.
     */
        public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id',
            'besar_kapasitas' => 'required|string',
            'tarif_kapasitas' => 'required|numeric',
            'deskripsi_kapasitas' => 'nullable|string',
            'tampil_ekatalog' => 'required|boolean',
        ]);

        // Tentukan status berdasarkan action
        $status = $request->action === 'ajukan' ? 'diajukan' : 'draft';

        // Simpan kapasitas baru
        $kapasitas = Capacity::create([
            'id_produk' => $request->id_produk,
            'besar_kapasitas' => $request->besar_kapasitas,
            'tarif_kapasitas' => $request->tarif_kapasitas,
            'deskripsi_kapasitas' => $request->deskripsi_kapasitas,
            'is_verified_kapasitas' => $status, // Set status berdasarkan action
            'tampil_ekatalog' => $request->tampil_ekatalog,
        ]);

        // Simpan riwayat status
        RiwayatKapasitas::create([
            'kapasitas_id' => $kapasitas->id,
            'status' => $status,
            'waktu' => now(),
        ]);

        return redirect()->route('kapasitas.index')->with('success', 'Kapasitas berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit kapasitas.
     */
        public function edit($id)
    {
        // Ambil data kapasitas yang akan diedit
        $kapasitas = Capacity::findOrFail($id);

        // Ambil semua data produk untuk dropdown
        $produk = Produk::all();

        return view('kapasitas.edit', [
            'title' => 'Ubah Kapasitas Internet',
            'kapasitas' => $kapasitas,
            'produk' => $produk,
        ]);
    }

    /**
     * Memperbarui data kapasitas.
     */
        public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'id_produk' => 'required|exists:produk,id',
            'besar_kapasitas' => 'required|string',
            'tarif_kapasitas' => 'required|numeric',
            'deskripsi_kapasitas' => 'nullable|string',
            'tampil_ekatalog' => 'required|boolean',
        ]);

        // Tentukan status berdasarkan action
        $status = $request->action === 'ajukan' ? 'diajukan' : 'draft';

        // Ambil data kapasitas yang akan diperbarui
        $kapasitas = Capacity::findOrFail($id);

        // Perbarui data kapasitas
        $kapasitas->update([
            'id_produk' => $request->id_produk,
            'besar_kapasitas' => $request->besar_kapasitas,
            'tarif_kapasitas' => $request->tarif_kapasitas,
            'deskripsi_kapasitas' => $request->deskripsi_kapasitas,
            'is_verified_kapasitas' => $status, // Set status berdasarkan action
            'tampil_ekatalog' => $request->tampil_ekatalog,
        ]);

        // Simpan riwayat status baru
        RiwayatKapasitas::create([
            'kapasitas_id' => $kapasitas->id,
            'status' => $status,
            'waktu' => now(),
        ]);

        return redirect()->route('kapasitas.index')->with('success', 'Kapasitas berhasil diperbarui!');
    }

    /**
     * Menghapus data kapasitas.
     */
    public function destroy($id)
    {
        $kapasitas = Capacity::findOrFail($id);
        $kapasitas->delete();
        return redirect()->route('kapasitas.index')->with('success', 'Kapasitas berhasil dihapus!');
    }

    /**
     * Menampilkan halaman verifikasi kapasitas.
     */
        public function verifyIndex()
    {
        // Ambil data kapasitas yang statusnya 'diajukan'
        $kapasitas = Capacity::with(['produk', 'riwayat'])
            ->where('is_verified_kapasitas', 'diajukan')
            ->get();

        return view('kapasitas.verify', [
            'title' => 'Menunggu Verifikasi',
            'kapasitas' => $kapasitas,
        ]);
    }

    /**
     * Menerima kapasitas (ubah status menjadi diverifikasi).
     */
        public function terima($id)
    {
        // Ambil data kapasitas yang akan diverifikasi
        $kapasitas = Capacity::findOrFail($id);

        // Ubah status menjadi diverifikasi
        $kapasitas->update([
            'is_verified_kapasitas' => 'diverifikasi',
        ]);

        // Simpan riwayat status diverifikasi
        RiwayatKapasitas::create([
            'kapasitas_id' => $kapasitas->id,
            'status' => 'diverifikasi',
            'waktu' => now(),
        ]);

        return redirect()->route('kapasitas.verify')->with('success', 'Kapasitas berhasil diverifikasi!');
    }

    /**
     * Menolak kapasitas (ubah status menjadi ditolak).
     */
        public function tolak(Request $request, $id)
    {
        // Validasi input alasan penolakan
        $request->validate([
            'alasan_penolakan' => 'required|string',
        ]);

        // Ambil data kapasitas yang akan ditolak
        $kapasitas = Capacity::findOrFail($id);

        // Ubah status menjadi draft
        $kapasitas->update([
            'is_verified_kapasitas' => 'draft',
        ]);

        // Simpan riwayat status ditolak
        RiwayatKapasitas::create([
            'kapasitas_id' => $kapasitas->id,
            'status' => 'ditolak',
            'waktu' => now(),
            'keterangan' => $request->alasan_penolakan,
        ]);

        return redirect()->route('kapasitas.verify')->with('success', 'Kapasitas berhasil ditolak!');
    }

    /**
     * Mengubah status kapasitas menjadi draft.
     */
    public function kembalikan($id)
    {
        // Ambil data kapasitas yang akan dikembalikan
        $kapasitas = Capacity::findOrFail($id);

        // Ubah status menjadi draft
        $kapasitas->update([
            'is_verified_kapasitas' => 'draft',
        ]);

        // Simpan riwayat status draft dengan keterangan
        RiwayatKapasitas::create([
            'kapasitas_id' => $kapasitas->id,
            'status' => 'draft', // Status tetap 'draft'
            'waktu' => now(),
            'keterangan' => 'Dikembalikan dari diverifikasi', // Keterangan menjelaskan alasan perubahan
        ]);

        return redirect()->route('kapasitas.index')->with('success', 'Kapasitas berhasil dikembalikan ke draft!');
    }

    /**
     * Mengubah pricing kapasitas.
     */
    public function updatePricing(Request $request, $id)
    {
        $request->validate([
            'pricing' => 'required|numeric|min:' . $request->tarif_kapasitas,
        ]);

        $kapasitas = Capacity::findOrFail($id);
        $kapasitas->update(['pricing' => $request->pricing]);

        return redirect()->route('pricing.kapasitas')->with('success', 'Pricing berhasil diperbarui!');
    }
}