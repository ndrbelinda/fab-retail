<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_produk',
        'pertanyaan',
        'jawaban',
        'tampil_ekatalog',
        'status',
    ];

    // Relasi ke tabel produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    // Relasi ke tabel riwayat_faqs (jika diperlukan)
    public function riwayat()
    {
        return $this->hasMany(RiwayatFaq::class, 'faq_id');
    }
}