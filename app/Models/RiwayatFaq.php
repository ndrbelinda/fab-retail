<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatFaq extends Model
{
    protected $fillable = [
        'faq_id',
        'status',
        'waktu',
        'keterangan',
    ];

    // Relasi ke tabel faqs
    public function faq()
    {
        return $this->belongsTo(Faq::class, 'faq_id');
    }
}