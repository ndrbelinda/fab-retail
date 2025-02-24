<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPricingPerangkat extends Model
{
    protected $table = 'riwayat_pricing_perangkat';

    protected $fillable = [
        'perangkat_id', 'pricing', // Ubah dari nominal ke pricing
    ];

    // Relasi ke tabel perangkat
    public function perangkat()
    {
        return $this->belongsTo(Perangkat::class, 'perangkat_id');
    }
}