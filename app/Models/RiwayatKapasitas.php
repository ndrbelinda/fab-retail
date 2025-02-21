<?php

// app/Models/RiwayatKapasitas.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatKapasitas extends Model
{
    protected $table = 'riwayat_kapasitas';

    protected $fillable = [
        'kapasitas_id', 'status', 'waktu', 'keterangan',
    ];

    // Relasi ke tabel capacities
    public function kapasitas()
    {
        return $this->belongsTo(Capacity::class, 'kapasitas_id');
    }
}