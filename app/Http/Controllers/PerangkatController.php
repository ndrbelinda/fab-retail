<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perangkat;
use App\Models\Produk;
use App\Models\RiwayatPerangkat;
use App\Mail\PerangkatStatusEmail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class PerangkatController extends Controller
{
    /**
     * Menampilkan daftar perangkat.
     */
    public function index(Request $request)
    {
        //Ambil parameter sorting dan filter dari request
        $sort = $request->query('sort');
        $direction = $request->query('direction', 'asc');
        $produkFilter = $request->query('produk', []);
        $tarifFilter = $request->query('tarif');
        $waktuFilter = $request->query('waktu');

        // Query dasar
        $perangkat = Perangkat::with('produk', 'riwayat');


        // Filter berdasarkan produk
        if (!empty($produkFilter)) {
            $perangkat->whereHas('produk', function ($query) use ($produkFilter) {
                $query->whereIn('nama_produk', $produkFilter);
            });
        }

        // Filter berdasarkan tarif
        if ($tarifFilter === 'terendah') {
            $perangkat->orderBy('tarif_perangkat', 'asc');
        } elseif ($tarifFilter === 'tertinggi') {
            $perangkat->orderBy('tarif_perangkat', 'desc');
        }

        // Filter berdasarkan waktu pembuatan
        if ($waktuFilter === 'terlama') {
            $perangkat->orderBy('created_at', 'asc');
        } elseif ($waktuFilter === 'terbaru') {
            $perangkat->orderBy('created_at', 'desc');
        }

        // Default sorting jika tidak ada filter
        if (empty($tarifFilter) && empty($perangkatFilter) && empty($waktuFilter)) {
            $perangkat->orderBy('created_at', 'desc');
        }

        // Ambil data
        $perangkat = $perangkat->paginate(10);

        // Ambil data produk untuk filter
        $produk = Produk::all();
        
        return view('perangkat.index', [
            'title' => 'Daftar Perangkat',
            'perangkat' => $perangkat,
            'produk' => $produk,
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
            'tarif_perangkat' => 'required|integer',
            'deskripsi_perangkat' => 'nullable|string',
            'tampil_ekatalog' => 'required|boolean',
        ]);

        // Debug: Cek apakah file diunggah
        if (!$request->hasFile('gambar_perangkat')) {
            Log::error('Tidak ada file gambar yang diunggah.');
            return redirect()->back()->with('error', 'Tidak ada file gambar yang diunggah.');
        }

        $image = $request->file('gambar_perangkat');

        // Debug: Cek apakah file valid
        if (!$image->isValid()) {
            Log::error('File gambar tidak valid.');
            return redirect()->back()->with('error', 'File gambar tidak valid.');
        }

        // Simpan gambar ke storage
        try {
            $gambarPath = $image->store('perangkat', 'public');
	        $gambarUrl = asset('storage/' . $gambarPath);

            // Debug: Tampilkan path dan URL gambar
            Log::info('Gambar berhasil diunggah.', [
                'gambarPath' => $gambarPath,
                'gambarUrl' => $gambarUrl,
                'fullPath' => storage_path('app/' . $gambarPath),
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan gambar: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan gambar.');
        }

        // Tentukan status berdasarkan action
        $status = $request->action === 'ajukan' ? 'diajukan' : 'draft';

        // Mulai transaksi database
        DB::beginTransaction();

        try {
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

            // Kirim email hanya jika status "diajukan"
            if ($status === 'diajukan') {
                // Ambil email dari user yang sedang login
                $user = Auth::user();
                $fromEmail = $user->email;

                // Atur mailer berdasarkan email pengguna
                $mailer = ($fromEmail === 'avpprodukxyz@gmail.com') ? 'smtp_avp' : 'smtp_staff';

                // Kirim email dengan mailer yang dipilih
                Mail::mailer($mailer)->to('avpprodukxyz@gmail.com')
                    ->send(new PerangkatStatusEmail($perangkat, $status));
            }

            // Commit transaksi
            DB::commit();

            return redirect()->route('perangkat.index')->with('success', 'Perangkat berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Log error (opsional)
            Log::error('Gagal menyimpan perangkat: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menyimpan perangkat. Silakan coba lagi.');
        }
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
            'tarif_perangkat' => 'required|integer',
            'deskripsi_perangkat' => 'nullable|string',
            'tampil_ekatalog' => 'required|boolean',
        ]);

        // Ambil data perangkat yang akan diperbarui
        $perangkat = Perangkat::findOrFail($id);

        // Jika ada gambar baru, simpan gambar dan hapus gambar lama
        if ($request->hasFile('gambar_perangkat')) {
            $image = $request->file('gambar_perangkat');

            // Debug: Cek apakah file valid
            if (!$image->isValid()) {
                Log::error('File gambar tidak valid.');
                return redirect()->back()->with('error', 'File gambar tidak valid.');
            }

            // Hapus gambar lama jika ada
            if ($perangkat->gambar_perangkat) {
                $oldImagePath = str_replace('/storage', 'public', $perangkat->gambar_perangkat);
                Storage::delete($oldImagePath);
            }

            // Simpan gambar baru
            try {
                $gambarPath = $image->store('perangkat', 'public');
                $gambarUrl = asset('storage/' . $gambarPath);
                $perangkat->gambar_perangkat = $gambarUrl;

                // Debug: Tampilkan path dan URL gambar
                Log::info('Gambar berhasil diunggah.', [
                    'gambarPath' => $gambarPath,
                    'gambarUrl' => $gambarUrl,
                    'fullPath' => storage_path('app/' . $gambarPath),
                ]);
            } catch (\Exception $e) {
                Log::error('Gagal menyimpan gambar: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal menyimpan gambar.');
            }
        }

        // Tentukan status berdasarkan action
        $status = $request->action === 'ajukan' ? 'diajukan' : 'draft';

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Perbarui data perangkat
            $perangkat->update([
                'id_produk' => $request->id_produk,
                'jenis_perangkat' => $request->jenis_perangkat,
                'gambar_perangkat' => $gambarUrl ?? $perangkat->gambar_perangkat, // Simpan URL gambar baru atau tetap gunakan yang lama
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

            // Kirim email hanya jika status "diajukan"
            if ($status === 'diajukan') {
                // Ambil email dari user yang sedang login
                $user = Auth::user();
                $fromEmail = $user->email;

                // Atur mailer berdasarkan email pengguna
                $mailer = ($fromEmail === 'avpprodukxyz@gmail.com') ? 'smtp_avp' : 'smtp_staff';

                // Kirim email dengan mailer yang dipilih
                Mail::mailer($mailer)->to('avpprodukxyz@gmail.com')
                    ->send(new PerangkatStatusEmail($perangkat, $status));
            }

            // Commit transaksi
            DB::commit();

            return redirect()->route('perangkat.index')->with('success', 'Perangkat berhasil diperbarui!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Log error (opsional)
            Log::error('Gagal memperbarui perangkat: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal memperbarui perangkat. Silakan coba lagi.');
        }
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
    public function verifyIndex(Request $request)
    {
        // Ambil parameter filter dari request
        $produkFilter = $request->query('produk', []);
        $tarifFilter = $request->query('tarif');
        $waktuFilter = $request->query('waktu');

        // Query dasar untuk perangkat yang statusnya 'diajukan'
        $perangkat = Perangkat::with(['produk', 'riwayat'])
            ->where('is_verified_perangkat', 'diajukan');

        // Filter berdasarkan produk
        // Filter berdasarkan produk
        if (!empty($produkFilter)) {
            $perangkat->whereHas('produk', function ($query) use ($produkFilter) {
                $query->whereIn('nama_produk', $produkFilter);
            });
        }

        // Filter berdasarkan tarif
        if ($tarifFilter === 'terendah') {
            $perangkat->orderBy('tarif_perangkat', 'asc');
        } elseif ($tarifFilter === 'tertinggi') {
            $perangkat->orderBy('tarif_perangkat', 'desc');
        }

        // Filter berdasarkan waktu pembuatan
        if ($waktuFilter === 'terlama') {
            $perangkat->orderBy('created_at', 'asc');
        } elseif ($waktuFilter === 'terbaru') {
            $perangkat->orderBy('created_at', 'desc');
        }

        // Default sorting jika tidak ada filter
        if (empty($tarifFilter) && empty($perangkatFilter) && empty($waktuFilter)) {
            $perangkat->orderBy('created_at', 'desc');
        }

        // Ambil data
        $perangkat = $perangkat->paginate(10);

        // Ambil data produk untuk filter
        $produk = Produk::all();
        
        return view('perangkat.verify', [
            'title' => 'Daftar Perangkat',
            'perangkat' => $perangkat,
            'produk' => $produk,
        ]);
    }

    /**
     * Menerima perangkat (ubah status menjadi diverifikasi).
     */
    public function terima($id)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Ambil data perangkat yang akan diverifikasi
            $perangkat = Perangkat::findOrFail($id);

            // Ubah status menjadi diverifikasi
            $perangkat->update([
                'is_verified_perangkat' => 'diverifikasi',
            ]);

            // Simpan riwayat status
            RiwayatPerangkat::create([
                'perangkat_id' => $perangkat->id,
                'status' => 'diverifikasi',
                'waktu' => now(),
            ]);

            // Kirim email notifikasi
            $user = Auth::user();
            $fromEmail = $user->email;
            $mailer = ($fromEmail === 'avpprodukxyz@gmail.com') ? 'smtp_avp' : 'smtp_staff';

            Mail::mailer($mailer)->to('staffprodukxyz@gmail.com')
                ->send(new PerangkatStatusEmail($perangkat, 'diverifikasi'));

            // Commit transaksi
            DB::commit();

            return redirect()->route('perangkat.verify')->with('success', 'Perangkat berhasil diverifikasi!');
            } catch (\Exception $e) {
                // Rollback transaksi jika terjadi kesalahan
                DB::rollBack();

                // Log error (opsional)
                Log::error('Gagal menyimpan perangkat: ' . $e->getMessage());

                return redirect()->back()->with('error', 'Gagal menyimpan perangkat. Silakan coba lagi.');
            }
    }

    /**
     * Menolak perangkat (ubah status menjadi ditolak).
     */
    public function tolak(Request $request, $id)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Validasi input alasan penolakan
            $request->validate([
                'alasan_penolakan' => 'required|string',
            ]);

            // Ambil data perangkat yang akan diverifikasi
            $perangkat = Perangkat::findOrFail($id);

            // Ubah status menjadi draft
            $perangkat->update([
                'is_verified_perangkat' => 'draft',
            ]);

            // Simpan riwayat status
            RiwayatPerangkat::create([
                'perangkat_id' => $perangkat->id,
                'status' => 'ditolak',
                'waktu' => now(),
                'keterangan' => $request->alasan_penolakan,
            ]);

            // Kirim email notifikasi
            $user = Auth::user();
            $fromEmail = $user->email;
            $mailer = ($fromEmail === 'avpprodukxyz@gmail.com') ? 'smtp_avp' : 'smtp_staff';

            Mail::mailer($mailer)->to('staffprodukxyz@gmail.com')
                ->send(new PerangkatStatusEmail($perangkat, 'ditolak', $request->alasan_penolakan));

            // Commit transaksi
            DB::commit();

            return redirect()->route('perangkat.verify')->with('success', 'Perangkat berhasil ditolak!');
            } catch (\Exception $e) {
                // Rollback transaksi jika terjadi kesalahan
                DB::rollBack();

                // Log error (opsional)
                Log::error('Gagal menyimpan perangkat: ' . $e->getMessage());

                return redirect()->back()->with('error', 'Gagal menyimpan perangkat. Silakan coba lagi.');
            }
    }

    /**
     * Mengubah status perangkat menjadi draft.
     */
    public function kembalikan($id)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Ambil data perangkat yang akan diverifikasi
            $perangkat = Perangkat::findOrFail($id);

            // Ubah status menjadi diverifikasi
            $perangkat->update([
                'is_verified_perangkat' => 'draft',
            ]);

            // Simpan riwayat status
            $keterangan = 'Dikembalikan dari diverifikasi';
            RiwayatPerangkat::create([
                'perangkat_id' => $perangkat->id,
                'status' => 'draft',
                'waktu' => now(),
                'keterangan' => $keterangan,
            ]);

            // Kirim email notifikasi
            $user = Auth::user();
            $fromEmail = $user->email;
            $mailer = ($fromEmail === 'avpprodukxyz@gmail.com') ? 'smtp_avp' : 'smtp_staff';

            Mail::mailer($mailer)->to('staffprodukxyz@gmail.com')
                ->send(new PerangkatStatusEmail($perangkat, 'dikembalikan', $keterangan));

            // Commit transaksi
            DB::commit();

            return redirect()->route('perangkat.index')->with('success', 'Perangkat berhasil dikembalikan ke draft!');
            } catch (\Exception $e) {
                // Rollback transaksi jika terjadi kesalahan
                DB::rollBack();

                // Log error (opsional)
                Log::error('Gagal menyimpan perangkat: ' . $e->getMessage());

                return redirect()->back()->with('error', 'Gagal menyimpan perangkat. Silakan coba lagi.');
            }
    }
}