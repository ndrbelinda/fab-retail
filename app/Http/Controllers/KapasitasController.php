<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Capacity;
use App\Models\Produk;
use App\Models\RiwayatKapasitas;
use App\Mail\KapasitasStatusEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class KapasitasController extends Controller
{
    /**
     * Menampilkan daftar kapasitas.
     */
    public function index(Request $request)
    {
        // Ambil parameter sorting dan filter dari request
        $sort = $request->query('sort');
        $direction = $request->query('direction', 'asc');
        $produkFilter = $request->query('produk', []);
        $tarifFilter = $request->query('tarif');
        $kapasitasFilter = $request->query('kapasitas');
        $waktuFilter = $request->query('waktu');

        // Query dasar
        $kapasitas = Capacity::with('produk', 'riwayat');

        // Filter berdasarkan produk
        if (!empty($produkFilter)) {
            $kapasitas->whereHas('produk', function ($query) use ($produkFilter) {
                $query->whereIn('nama_produk', $produkFilter);
            });
        }

        // Filter berdasarkan tarif
        if ($tarifFilter === 'terendah') {
            $kapasitas->orderBy('tarif_kapasitas', 'asc');
        } elseif ($tarifFilter === 'tertinggi') {
            $kapasitas->orderBy('tarif_kapasitas', 'desc');
        }

        // Filter berdasarkan kapasitas
        if ($kapasitasFilter === 'terendah') {
            $kapasitas->orderBy('besar_kapasitas', 'asc');
        } elseif ($kapasitasFilter === 'tertinggi') {
            $kapasitas->orderBy('besar_kapasitas', 'desc');
        }

        // Filter berdasarkan waktu pembuatan
        if ($waktuFilter === 'terlama') {
            $kapasitas->orderBy('created_at', 'asc');
        } elseif ($waktuFilter === 'terbaru') {
            $kapasitas->orderBy('created_at', 'desc');
        }

        // Default sorting jika tidak ada filter
        if (empty($tarifFilter) && empty($kapasitasFilter) && empty($waktuFilter)) {
            $kapasitas->orderBy('created_at', 'desc');
        }

        // Ambil data
        $kapasitas = $kapasitas->paginate(10);

        // Ambil data produk untuk filter
        $produk = Produk::all();

        return view('kapasitas.index', [
            'title' => 'Daftar Kapasitas',
            'kapasitas' => $kapasitas,
            'produk' => $produk,
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
            'besar_kapasitas' => [
            'required',
            'numeric',
            'integer',
            'min:1',
            Rule::unique('capacities')->where(function ($query) use ($request) {
                return $query->where('id_produk', $request->id_produk);
                })
            ],
            'tarif_kapasitas' => 'required|numeric|integer|min:1',
            'deskripsi_kapasitas' => 'nullable|string',
            'tampil_ekatalog' => 'required|boolean', 
        ], [
        'besar_kapasitas.unique' => 'Kapasitas Internet sudah ada untuk produk ini.'
        ]);

        $status = $request->action === 'ajukan' ? 'diajukan' : 'draft';

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Simpan data kapasitas
            $kapasitas = Capacity::create([
                'id_produk' => $request->id_produk,
                'besar_kapasitas' => $request->besar_kapasitas,
                'tarif_kapasitas' => $request->tarif_kapasitas,
                'deskripsi_kapasitas' => $request->deskripsi_kapasitas,
                'is_verified_kapasitas' => $status,
                'tampil_ekatalog' => $request->tampil_ekatalog,
            ]);

            // Simpan riwayat kapasitas
            RiwayatKapasitas::create([
                'kapasitas_id' => $kapasitas->id,
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
                    ->send(new KapasitasStatusEmail($kapasitas, $status));
            }

            // Commit transaksi
            DB::commit();

            return redirect()->route('kapasitas.index')->with('success', 'Kapasitas berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Log error (opsional)
            Log::error('Gagal menyimpan kapasitas: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menyimpan kapasitas. Silakan coba lagi.');
        }
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
            'besar_kapasitas' => [
                'required',
                'numeric',
                'integer',
                'min:1',
                Rule::unique('capacities')->where(function ($query) use ($request) {
                    return $query->where('id_produk', $request->id_produk);
                })->ignore($id) // Ignore current record
            ],
            'tarif_kapasitas' => 'required|numeric|integer|min:1',
            'deskripsi_kapasitas' => 'nullable|string',
            'tampil_ekatalog' => 'required|boolean',
        ], [
            'besar_kapasitas.unique' => 'Kapasitas Internet sudah ada untuk produk ini.'
        ]);

        // Tentukan status berdasarkan action
        $status = $request->action === 'ajukan' ? 'diajukan' : 'draft';

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Ambil data kapasitas yang akan diperbarui
            $kapasitas = Capacity::findOrFail($id);

            // Perbarui data kapasitas
            $kapasitas->update([
                'id_produk' => $request->id_produk,
                'besar_kapasitas' => $request->besar_kapasitas,
                'tarif_kapasitas' => $request->tarif_kapasitas,
                'deskripsi_kapasitas' => $request->deskripsi_kapasitas,
                'is_verified_kapasitas' => $status,
                'tampil_ekatalog' => $request->tampil_ekatalog,
            ]);

            // Simpan riwayat status baru
            RiwayatKapasitas::create([
                'kapasitas_id' => $kapasitas->id,
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
                    ->send(new KapasitasStatusEmail($kapasitas, $status));
            }

            // Commit transaksi
            DB::commit();

            return redirect()->route('kapasitas.index')->with('success', 'Kapasitas berhasil diperbarui!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Log error (opsional)
            Log::error('Gagal memperbarui kapasitas: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal memperbarui kapasitas. Silakan coba lagi.');
        }
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
    public function verifyIndex(Request $request)
    {
        // Ambil parameter filter dari request
        $produkFilter = $request->query('produk', []);
        $tarifFilter = $request->query('tarif');
        $kapasitasFilter = $request->query('kapasitas');
        $waktuFilter = $request->query('waktu');

        // Query dasar untuk kapasitas yang statusnya 'diajukan'
        $kapasitas = Capacity::with(['produk', 'riwayat'])
            ->where('is_verified_kapasitas', 'diajukan');

        // Filter berdasarkan produk
        if (!empty($produkFilter)) {
            $kapasitas->whereHas('produk', function ($query) use ($produkFilter) {
                $query->whereIn('nama_produk', $produkFilter);
            });
        }

        // Filter berdasarkan tarif
        if ($tarifFilter === 'terendah') {
            $kapasitas->orderBy('tarif_kapasitas', 'asc');
        } elseif ($tarifFilter === 'tertinggi') {
            $kapasitas->orderBy('tarif_kapasitas', 'desc');
        }

        // Filter berdasarkan kapasitas
        if ($kapasitasFilter === 'terendah') {
            $kapasitas->orderBy('besar_kapasitas', 'asc');
        } elseif ($kapasitasFilter === 'tertinggi') {
            $kapasitas->orderBy('besar_kapasitas', 'desc');
        }

        // Filter berdasarkan waktu pembuatan
        if ($waktuFilter === 'terlama') {
            $kapasitas->orderBy('created_at', 'asc');
        } elseif ($waktuFilter === 'terbaru') {
            $kapasitas->orderBy('created_at', 'desc');
        }

        // Ambil data
        $kapasitas = $kapasitas->paginate(10);

        // Ambil data produk untuk filter
        $produk = Produk::all();

        return view('kapasitas.verify', [
            'title' => 'Menunggu Verifikasi',
            'kapasitas' => $kapasitas,
            'produk' => $produk,
        ]);
    }

    /**
     * Menerima kapasitas (ubah status menjadi diverifikasi).
     */
    public function terima($id)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
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

            // Kirim email notifikasi
            $user = Auth::user();
            $fromEmail = $user->email;
            $mailer = ($fromEmail === 'avpprodukxyz@gmail.com') ? 'smtp_avp' : 'smtp_staff';

            Mail::mailer($mailer)->to('staffprodukxyz@gmail.com')
                ->send(new KapasitasStatusEmail($kapasitas, 'diverifikasi'));

            // Commit transaksi
            DB::commit();

            return redirect()->route('kapasitas.verify')->with('success', 'Kapasitas berhasil diverifikasi!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Log error
            Log::error('Gagal mengirim email: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal mengirim email. Data tidak disimpan.');
        }
    }

    /**
     * Menolak kapasitas (ubah status menjadi ditolak).
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

            // Kirim email notifikasi
            $user = Auth::user();
            $fromEmail = $user->email;
            $mailer = ($fromEmail === 'avpprodukxyz@gmail.com') ? 'smtp_avp' : 'smtp_staff';

            Mail::mailer($mailer)->to('staffprodukxyz@gmail.com')
                ->send(new KapasitasStatusEmail($kapasitas, 'ditolak', $request->alasan_penolakan));

            // Commit transaksi
            DB::commit();

            return redirect()->route('kapasitas.verify')->with('success', 'Kapasitas berhasil ditolak!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Log error
            Log::error('Gagal mengirim email: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal mengirim email. Data tidak disimpan.');
        }
    }

    /**
     * Mengubah status kapasitas menjadi draft.
     */
    public function kembalikan($id)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Ambil data kapasitas yang akan dikembalikan
            $kapasitas = Capacity::findOrFail($id);

            // Ubah status menjadi draft
            $kapasitas->update([
                'is_verified_kapasitas' => 'draft',
            ]);

            // Simpan riwayat status draft dengan keterangan
            $keterangan = 'Dikembalikan dari diverifikasi';
            RiwayatKapasitas::create([
                'kapasitas_id' => $kapasitas->id,
                'status' => 'draft',
                'waktu' => now(),
                'keterangan' => $keterangan,
            ]);

            // Kirim email notifikasi
            $user = Auth::user();
            $fromEmail = $user->email;
            $mailer = ($fromEmail === 'avpprodukxyz@gmail.com') ? 'smtp_avp' : 'smtp_staff';

            Mail::mailer($mailer)->to('staffprodukxyz@gmail.com')
                ->send(new KapasitasStatusEmail($kapasitas, 'dikembalikan', $keterangan));

            // Commit transaksi
            DB::commit();

            return redirect()->route('kapasitas.index')->with('success', 'Kapasitas berhasil dikembalikan ke draft!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Log error
            Log::error('Gagal mengirim email: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal mengirim email. Data tidak disimpan.');
        }
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