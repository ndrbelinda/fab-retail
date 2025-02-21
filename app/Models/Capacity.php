<?php

/// app/Models/Capacity.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capacity extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi (fillable) secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'id_produk',
        'besar_kapasitas',
        'tarif_kapasitas',
        'deskripsi_kapasitas',
        'is_verified_kapasitas',
        'tampil_ekatalog',
    ];

    /**
     * Kolom yang harus di-cast ke tipe data tertentu.
     *
     * @var array
     */
    protected $casts = [
        'tarif_kapasitas' => 'decimal:2', // Cast tarif_kapasitas ke tipe decimal
        'tampil_ekatalog' => 'boolean',   // Cast tampil_ekatalog ke tipe boolean
    ];

    /**
     * Relasi ke model Produk.
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    // Relasi ke tabel riwayat_kapasitas
    public function riwayat()
    {
        return $this->hasMany(RiwayatKapasitas::class, 'kapasitas_id');
    }
}