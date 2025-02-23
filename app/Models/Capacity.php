<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capacity extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_produk', 'besar_kapasitas', 'tarif_kapasitas', 'deskripsi_kapasitas', 'is_verified_kapasitas', 'tampil_ekatalog',
    ];

    protected $casts = [
        'tarif_kapasitas' => 'decimal:2',
        'tampil_ekatalog' => 'boolean',
    ];

    // Relasi ke tabel produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    // Relasi ke tabel riwayat_kapasitas
    public function riwayat()
    {
        return $this->hasMany(RiwayatKapasitas::class, 'kapasitas_id');
    }

    // Relasi ke tabel riwayat_pricing_kapasitas
    public function riwayatPricing()
    {
        return $this->hasMany(RiwayatPricingKapasitas::class, 'kapasitas_id');
    }
}