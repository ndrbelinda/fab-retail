<?php

// app/Models/Produk.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara manual
    protected $table = 'produk'; // Gunakan 'produk' sebagai nama tabel
}