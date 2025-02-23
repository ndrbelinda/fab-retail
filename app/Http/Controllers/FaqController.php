<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\Produk;
use App\Models\RiwayatFaq;

class FaqController extends Controller
{
    // Menampilkan daftar FAQ
    public function index()
    {
        $faqs = Faq::with(['produk', 'riwayat'])->get();
        return view('faq.index', [
            'title' => 'Daftar FAQ',
            'faqs' => $faqs,
        ]);
    }

    // Menampilkan form tambah FAQ
    public function create()
    {
        $produk = Produk::all();
        return view('faq.create', [
            'title' => 'Tambah FAQ',
            'produk' => $produk,
        ]);
    }

    // Menyimpan data FAQ baru
        public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id',
            'pertanyaan' => 'required|string',
            'jawaban' => 'required|string',
            'tampil_ekatalog' => 'required|boolean',
        ]);

        // Tentukan status berdasarkan action
        $status = $request->action === 'ajukan' ? 'diajukan' : 'draft';

        // Simpan FAQ baru
        $faq = Faq::create([
            'id_produk' => $request->id_produk,
            'pertanyaan' => $request->pertanyaan,
            'jawaban' => $request->jawaban,
            'tampil_ekatalog' => $request->tampil_ekatalog,
            'status' => $status, // Set status berdasarkan action
        ]);

        // Simpan riwayat status
        RiwayatFaq::create([
            'faq_id' => $faq->id,
            'status' => $status,
            'waktu' => now(),
        ]);

        return redirect()->route('faq.index')->with('success', 'FAQ berhasil ditambahkan!');
    }

    // Menampilkan form edit FAQ
    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        $produk = Produk::all();
        return view('faq.edit', [
            'title' => 'Ubah FAQ',
            'faq' => $faq,
            'produk' => $produk,
        ]);
    }

    // Memperbarui data FAQ
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id',
            'pertanyaan' => 'required|string',
            'jawaban' => 'required|string',
            'tampil_ekatalog' => 'required|boolean',
        ]);

        // Tentukan status berdasarkan action
        $status = $request->action === 'ajukan' ? 'diajukan' : 'draft';

        // Ambil data FAQ yang akan diperbarui
        $faq = Faq::findOrFail($id);

        // Perbarui data FAQ
        $faq->update([
            'id_produk' => $request->id_produk,
            'pertanyaan' => $request->pertanyaan,
            'jawaban' => $request->jawaban,
            'tampil_ekatalog' => $request->tampil_ekatalog,
            'status' => $status, // Set status berdasarkan action
        ]);

        // Simpan riwayat status baru
        RiwayatFaq::create([
            'faq_id' => $faq->id,
            'status' => $status,
            'waktu' => now(),
        ]);

        return redirect()->route('faq.index')->with('success', 'FAQ berhasil diperbarui!');
    }

    // Menghapus data FAQ
    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();
        return redirect()->route('faq.index')->with('success', 'FAQ berhasil dihapus!');
    }

    // Menampilkan halaman verifikasi FAQ
    public function verifyIndex()
    {
        $faqs = Faq::where('status', 'diajukan')->get();
        return view('faq.verify', [
            'title' => 'Menunggu Verifikasi',
            'faqs' => $faqs,
            'is_verify' => true,
        ]);
    }

    // Menerima FAQ (ubah status menjadi diverifikasi)
    public function terima($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->update(['status' => 'diverifikasi']);

        RiwayatFaq::create([
            'faq_id' => $faq->id,
            'status' => 'diverifikasi',
            'waktu' => now(),
        ]);

        return redirect()->route('faq.verify')->with('success', 'FAQ berhasil diverifikasi!');
    }

    // Menolak FAQ (ubah status menjadi ditolak)
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string',
        ]);

        // Ambil data FAQ yang akan ditolak
        $faq = Faq::findOrFail($id);

        // Ubah status menjadi draft
        $faq->update([
            'status' => 'draft', // Kembalikan status ke draft
        ]);

        // Simpan riwayat status ditolak
        RiwayatFaq::create([
            'faq_id' => $faq->id,
            'status' => 'ditolak', // Status riwayat tetap 'ditolak'
            'waktu' => now(),
            'keterangan' => $request->alasan_penolakan,
        ]);

        return redirect()->route('faq.verify')->with('success', 'FAQ berhasil ditolak dan dikembalikan ke draft!');
    }

    // Mengembalikan FAQ ke status draft
    public function kembalikan($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->update(['status' => 'draft']);

        RiwayatFaq::create([
            'faq_id' => $faq->id,
            'status' => 'draft',
            'waktu' => now(),
            'keterangan' => 'Dikembalikan dari diverifikasi',
        ]);

        return redirect()->route('faq.index')->with('success', 'FAQ berhasil dikembalikan ke draft!');
    }
}