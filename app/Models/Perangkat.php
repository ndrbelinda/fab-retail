<?php

/// app/Models/Perangkat.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perangkat extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi (fillable) secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'id_produk',
        'jenis_perangkat',
        'gambar_perangkat',
        'tarif_perangkat',
        'deskripsi_perangkat',
        'is_verified_perangkat',
        'tampil_ekatalog',
    ];

    /**
     * Kolom yang harus di-cast ke tipe data tertentu.
     *
     * @var array
     */
    protected $casts = [
        'tarif_perangkat' => 'decimal:2', // Cast tarif_kapasitas ke tipe decimal
        'tampil_ekatalog' => 'boolean',   // Cast tampil_ekatalog ke tipe boolean
    ];

    /**
     * Relasi ke model Produk.
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    // Relasi ke tabel riwayat_perangkat
    public function riwayat()
    {
        return $this->hasMany(RiwayatPerangkat::class, 'perangkat_id');
    }

    // Relasi ke tabel riwayat_pricing_perangkat
    public function riwayatPricingPerangkat()
    {
        return $this->hasMany(RiwayatPricingPerangkat::class, 'perangkat_id');
    }

}