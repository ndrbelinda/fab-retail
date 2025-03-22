<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\Produk;
use App\Models\RiwayatFaq;
use App\Mail\FaqStatusEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FaqController extends Controller
{
    // Menampilkan daftar FAQ
    public function index(Request $request)
    {
        //Menampilkan daftar Faq
        $sort = $request->query('sort');
        $direction = $request->query('direction', 'asc');
        $produkFilter = $request->query('produk', []);
        $waktuFilter = $request->query('waktu');

        //Query Dasar
        $faq = Faq::with('produk', 'riwayat');
        
        // Filter berdasarkan produk
        if (!empty($produkFilter)) {
            $faq->whereHas('produk', function ($query) use ($produkFilter) {
                $query->whereIn('nama_produk', $produkFilter);
            });
        }

        // Filter berdasarkan waktu pembuatan
        if ($waktuFilter === 'terlama') {
            $faq->orderBy('created_at', 'asc');
        } elseif ($waktuFilter === 'terbaru') {
            $faq->orderBy('created_at', 'desc');
        }

        // Default sorting jika tidak ada filter
        if (empty($produkFilter) && empty($waktuFilter)) {
            $faq->orderBy('created_at', 'desc');
        }

        // Ambil data
        $faq = $faq->paginate(10);

        // Ambil data produk untuk filter
        $produk = Produk::all();
        
        return view('faq.index', [
            'title' => 'Daftar FAQ',
            'faq' => $faq,
            'produk' => $produk,
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

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Simpan FAQ baru
            $faq = Faq::create([
                'id_produk' => $request->id_produk,
                'pertanyaan' => $request->pertanyaan,
                'jawaban' => $request->jawaban,
                'tampil_ekatalog' => $request->tampil_ekatalog,
                'is_verified_faq' => $status, // Pastikan ini sesuai dengan kolom di database
            ]);

            // Simpan riwayat status
            RiwayatFaq::create([
                'faq_id' => $faq->id,
                'status' => $status,
                'waktu' => now(),
            ]);

            // Kirim email hanya jika status "diajukan"
            if ($status === 'diajukan') {
                // Ambil email dari user yang sedang login
                $user = Auth::user();
                $fromEmail = $user->email;

                // Atur mailer berdasarkan email pengguna
                $mailer = ($fromEmail === 'avpprodukxyz@gmail.com') ? 'smtp_avp' : 'smtp_staff';

                // Kirim email dengan mailer yang dipilih
                Mail::mailer($mailer)->to('avpprodukxyz@gmail.com')
                    ->send(new FaqStatusEmail($faq, $status));
            }

            // Commit transaksi
            DB::commit();

            return redirect()->route('faq.index')->with('success', 'Faq berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Log error (opsional)
            Log::error('Gagal menyimpan faq: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menyimpan faq. Silakan coba lagi.');
        }
    }

    // Menampilkan form edit FAQ
    public function edit($id)
    {
        // Ambil data Faq yang akan diedit
        $faq = Faq::findOrFail($id);

        // Ambil semuda data produk
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

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Ambil data faq yang akan diperbarui
            $faq = Faq::findOrFail($id);

            // Simpan FAQ baru
            $faq->update([
                'id_produk' => $request->id_produk,
                'pertanyaan' => $request->pertanyaan,
                'jawaban' => $request->jawaban,
                'tampil_ekatalog' => $request->tampil_ekatalog,
                'is_verified_faq' => $status,
            ]);

            // Simpan riwayat status
            RiwayatFaq::create([
                'faq_id' => $faq->id,
                'status' => $status,
                'waktu' => now(),
            ]);

            // Kirim email hanya jika status "diajukan"
            if ($status === 'diajukan') {
                // Ambil email dari user yang sedang login
                $user = Auth::user();
                $fromEmail = $user->email;

                // Atur mailer berdasarkan email pengguna
                $mailer = ($fromEmail === 'avpprodukxyz@gmail.com') ? 'smtp_avp' : 'smtp_staff';

                // Kirim email dengan mailer yang dipilih
                Mail::mailer($mailer)->to('avpprodukxyz@gmail.com')
                    ->send(new FaqStatusEmail($faq, $status));
            }

            // Commit transaksi
            DB::commit();

            return redirect()->route('faq.index')->with('success', 'Faq berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Log error (opsional)
            Log::error('Gagal menyimpan faq: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menyimpan faq. Silakan coba lagi.');
        }
    }

    // Menghapus data FAQ
    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();
        return redirect()->route('faq.index')->with('success', 'FAQ berhasil dihapus!');
    }

    // Menampilkan halaman verifikasi FAQ
    public function verifyIndex(Request $request)
    {
        //Menampilkan daftar faq
        $produkFilter = $request->query('produk', []);
        $waktuFilter = $request->query('waktu');

        // Query dasar untuk faq yang statusnya 'diajukan'
        $faq = Faq::with(['produk', 'riwayat'])
            ->where('is_verified_faq', 'diajukan');
        
        // Filter berdasarkan produk
        if (!empty($produkFilter)) {
            $faq->whereHas('produk', function ($query) use ($produkFilter) {
                $query->whereIn('nama_produk', $produkFilter);
            });
        }

        // Filter berdasarkan waktu pembuatan
        if ($waktuFilter === 'terlama') {
            $faq->orderBy('created_at', 'asc');
        } elseif ($waktuFilter === 'terbaru') {
            $faq->orderBy('created_at', 'desc');
        }

        // Ambil data
        $faq = $faq->paginate(10);

        // Ambil data produk untuk filter
        $produk = Produk::all();
        
        return view('faq.verify', [
            'title' => 'Daftar FAQ',
            'faq' => $faq,
            'produk' => $produk,
        ]);
    }

    // Menerima FAQ (ubah status menjadi diverifikasi)
    public function terima($id)
    {
       // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Ambil data faq yang akan diperbarui
            $faq = Faq::findOrFail($id);

            // Simpan FAQ baru
            $faq->update([
                'is_verified_faq' => 'diverifikasi',
            ]);

            // Simpan riwayat status
            RiwayatFaq::create([
                'faq_id' => $faq->id,
                'status' => 'diverifikasi',
                'waktu' => now(),
            ]);

            // Kirim email notifikasi
            $user = Auth::user();
            $fromEmail = $user->email;
            $mailer = ($fromEmail === 'avpprodukxyz@gmail.com') ? 'smtp_avp' : 'smtp_staff';

            Mail::mailer($mailer)->to('staffprodukxyz@gmail.com')
                ->send(new FaqStatusEmail($faq, 'diverifikasi'));

            // Commit transaksi
            DB::commit();

            return redirect()->route('faq.verify')->with('success', 'Faq berhasil diverifikasi!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Log error (opsional)
            Log::error('Gagal menyimpan faq: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menyimpan faq. Silakan coba lagi.');
        }
    }

    // Menolak FAQ (ubah status menjadi ditolak)
    public function tolak(Request $request, $id)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Validasi input alasan penolakan
            $request->validate([
                'alasan_penolakan' => 'required|string',
            ]);

            // Ambil data faq yang akan ditolak
            $faq = Faq::findOrFail($id);

            // Ubah status menjadi draft
            $faq->update([
                'is_verified_faq' => 'draft',
            ]);

            // Simpan riwayat status ditolak
            RiwayatFaq::create([
                'faq_id' => $faq->id,
                'status' => 'ditolak',
                'waktu' => now(),
                'keterangan' => $request->alasan_penolakan,
            ]);

            // Kirim email notifikasi
            $user = Auth::user();
            $fromEmail = $user->email;
            $mailer = ($fromEmail === 'avpprodukxyz@gmail.com') ? 'smtp_avp' : 'smtp_staff';

            Mail::mailer($mailer)->to('staffprodukxyz@gmail.com')
                ->send(new FaqStatusEmail($faq, 'ditolak', $request->alasan_penolakan));

            // Commit transaksi
            DB::commit();

            return redirect()->route('faq.verify')->with('success', 'Faq berhasil ditolak!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Log error
            Log::error('Gagal mengirim email: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal mengirim email. Data tidak disimpan.');
        }
    }

    // Mengembalikan FAQ ke status draft
    public function kembalikan($id)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Ambil data faq yang akan dikembalikan
            $faq = Faq::findOrFail($id);

            // Ubah status menjadi draft
            $faq->update([
                'is_verified_faq' => 'draft',
            ]);

            // Simpan riwayat status draft dengan keterangan
            $keterangan = 'Dikembalikan dari diverifikasi';
            RiwayatFaq::create([
                'faq_id' => $faq->id,
                'status' => 'draft',
                'waktu' => now(),
                'keterangan' => $keterangan,
            ]);

            // Kirim email notifikasi
            $user = Auth::user();
            $fromEmail = $user->email;
            $mailer = ($fromEmail === 'avpprodukxyz@gmail.com') ? 'smtp_avp' : 'smtp_staff';

            Mail::mailer($mailer)->to('staffprodukxyz@gmail.com')
                ->send(new FaqStatusEmail($faq, 'dikembalikan', $keterangan));

            // Commit transaksi
            DB::commit();

            return redirect()->route('faq.index')->with('success', 'Faq berhasil dikembalikan ke draft!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Log error
            Log::error('Gagal mengirim email: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal mengirim email. Data tidak disimpan.');
        }
    }
}