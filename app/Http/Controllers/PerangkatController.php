<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perangkat;
use App\Models\Produk;
use App\Models\RiwayatPerangkat;
use Illuminate\Support\Facades\Storage;

class PerangkatController extends Controller
{
    /**
     * Menampilkan daftar perangkat.
     */
    public function index()
    {
        $perangkat = Perangkat::with(['produk', 'riwayat'])->get();
        return view('perangkat.index', [
            'title' => 'Daftar Perangkat',
            'perangkat' => $perangkat,
        ]);
    }

    /**
     * Menampilkan form tambah perangkat.
     */
    public function create()
    {
        $produk = Produk::all();
        return view('perangkat.create', [
            'title' => 'Tambah Perangkat',
            'produk' => $produk,
        ]);
    }

    /**
     * Menyimpan data perangkat baru.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_produk' => 'required|exists:produk,id',
            'jenis_perangkat' => 'required|string',
            'gambar_perangkat' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
            'tarif_perangkat' => 'required|numeric',
            'deskripsi_perangkat' => 'nullable|string',
            'tampil_ekatalog' => 'required|boolean',
        ]);

        // Simpan gambar ke storage
        $gambarPath = $request->file('gambar_perangkat')->store('public/perangkat');
        $gambarUrl = Storage::url($gambarPath); // Dapatkan URL gambar

        // Tentukan status berdasarkan action
        $status = $request->action === 'ajukan' ? 'diajukan' : 'draft';

        // Simpan perangkat baru
        $perangkat = Perangkat::create([
            'id_produk' => $request->id_produk,
            'jenis_perangkat' => $request->jenis_perangkat,
            'gambar_perangkat' => $gambarUrl, // Simpan URL gambar
            'tarif_perangkat' => $request->tarif_perangkat,
            'deskripsi_perangkat' => $request->deskripsi_perangkat,
            'is_verified_perangkat' => $status, // Set status berdasarkan action
            'tampil_ekatalog' => $request->tampil_ekatalog,
        ]);

        // Simpan riwayat status
        RiwayatPerangkat::create([
            'perangkat_id' => $perangkat->id,
            'status' => $status,
            'waktu' => now(),
        ]);

        return redirect()->route('perangkat.index')->with('success', 'Perangkat berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit perangkat.
     */
    public function edit($id)
    {
        $perangkat = Perangkat::findOrFail($id);
        $produk = Produk::all();

        return view('perangkat.edit', [
            'title' => 'Ubah Perangkat',
            'perangkat' => $perangkat,
            'produk' => $produk,
        ]);
    }

    /**
     * Memperbarui data perangkat.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'id_produk' => 'required|exists:produk,id',
            'jenis_perangkat' => 'required|string',
            'gambar_perangkat' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Gambar opsional
            'tarif_perangkat' => 'required|numeric',
            'deskripsi_perangkat' => 'nullable|string',
            'tampil_ekatalog' => 'required|boolean',
        ]);

        // Ambil data perangkat yang akan diperbarui
        $perangkat = Perangkat::findOrFail($id);

        // Jika ada gambar baru, simpan gambar dan hapus gambar lama
        if ($request->hasFile('gambar_perangkat')) {
            // Hapus gambar lama jika ada
            if ($perangkat->gambar_perangkat) {
                $oldImagePath = str_replace('/storage', 'public', $perangkat->gambar_perangkat);
                Storage::delete($oldImagePath);
            }

            // Simpan gambar baru
            $gambarPath = $request->file('gambar_perangkat')->store('public/perangkat');
            $gambarUrl = Storage::url($gambarPath);
            $perangkat->gambar_perangkat = $gambarUrl;
        }

        // Tentukan status berdasarkan action
        $status = $request->action === 'ajukan' ? 'diajukan' : 'draft';

        // Perbarui data perangkat
        $perangkat->update([
            'id_produk' => $request->id_produk,
            'jenis_perangkat' => $request->jenis_perangkat,
            'tarif_perangkat' => $request->tarif_perangkat,
            'deskripsi_perangkat' => $request->deskripsi_perangkat,
            'is_verified_perangkat' => $status, // Set status berdasarkan action
            'tampil_ekatalog' => $request->tampil_ekatalog,
        ]);

        // Simpan riwayat status baru
        RiwayatPerangkat::create([
            'perangkat_id' => $perangkat->id,
            'status' => $status,
            'waktu' => now(),
        ]);

        return redirect()->route('perangkat.index')->with('success', 'Perangkat berhasil diperbarui!');
    }

    /**
     * Menghapus data perangkat.
     */
    public function destroy($id)
    {
        $perangkat = Perangkat::findOrFail($id);

        // Hapus gambar jika ada
        if ($perangkat->gambar_perangkat) {
            $imagePath = str_replace('/storage', 'public', $perangkat->gambar_perangkat);
            Storage::delete($imagePath);
        }

        $perangkat->delete();
        return redirect()->route('perangkat.index')->with('success', 'Perangkat berhasil dihapus!');
    }

    /**
     * Menampilkan halaman verifikasi perangkat.
     */
    public function verifyIndex()
    {
        $perangkat = Perangkat::with(['produk', 'riwayat'])
            ->where('is_verified_perangkat', 'diajukan')
            ->get();

        return view('perangkat.verify', [
            'title' => 'Menunggu Verifikasi',
            'perangkat' => $perangkat,
        ]);
    }

    /**
     * Menerima perangkat (ubah status menjadi diverifikasi).
     */
    public function terima($id)
    {
        $perangkat = Perangkat::findOrFail($id);

        // Ubah status menjadi diverifikasi
        $perangkat->update([
            'is_verified_perangkat' => 'diverifikasi',
        ]);

        // Simpan riwayat status diverifikasi
        RiwayatPerangkat::create([
            'perangkat_id' => $perangkat->id,
            'status' => 'diverifikasi',
            'waktu' => now(),
        ]);

        return redirect()->route('perangkat.verify')->with('success', 'Perangkat berhasil diverifikasi!');
    }

    /**
     * Menolak perangkat (ubah status menjadi ditolak).
     */
    public function tolak(Request $request, $id)
    {
        // Validasi input alasan penolakan
        $request->validate([
            'alasan_penolakan' => 'required|string',
        ]);

        $perangkat = Perangkat::findOrFail($id);

        // Ubah status menjadi draft
        $perangkat->update([
            'is_verified_perangkat' => 'draft',
        ]);

        // Simpan riwayat status ditolak
        RiwayatPerangkat::create([
            'perangkat_id' => $perangkat->id,
            'status' => 'ditolak',
            'waktu' => now(),
            'keterangan' => $request->alasan_penolakan,
        ]);

        return redirect()->route('perangkat.verify')->with('success', 'Perangkat berhasil ditolak!');
    }

    /**
     * Mengubah status perangkat menjadi draft.
     */
    public function kembalikan($id)
    {
        $perangkat = Perangkat::findOrFail($id);

        // Ubah status menjadi draft
        $perangkat->update([
            'is_verified_perangkat' => 'draft',
        ]);

        // Simpan riwayat status draft dengan keterangan
        RiwayatPerangkat::create([
            'perangkat_id' => $perangkat->id,
            'status' => 'draft',
            'waktu' => now(),
            'keterangan' => 'Dikembalikan dari diverifikasi',
        ]);

        return redirect()->route('perangkat.index')->with('success', 'Perangkat berhasil dikembalikan ke draft!');
    }
}