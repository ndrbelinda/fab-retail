<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPricingKapasitas extends Model
{
    protected $table = 'riwayat_pricing_kapasitas';

    protected $fillable = [
        'kapasitas_id', 'pricing', 'dokumen' // Ubah dari nominal ke pricing
    ];

    // Relasi ke tabel capacities
    public function kapasitas()
    {
        return $this->belongsTo(Capacity::class, 'kapasitas_id');
    }
}