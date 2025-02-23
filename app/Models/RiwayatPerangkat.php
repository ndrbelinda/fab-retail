<?php

// app/Models/RiwayatPerangkat.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPerangkat extends Model
{
    protected $table = 'riwayat_perangkat';

    protected $fillable = [
        'perangkat_id', 'status', 'waktu', 'keterangan',
    ];

    // Relasi ke tabel perangkat
    public function perangkat()
    {
        return $this->belongsTo(Perangkat::class, 'perangkat_id');
    }
}