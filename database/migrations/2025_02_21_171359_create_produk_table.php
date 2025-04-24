<?php

// database/migrations/xxxx_xx_xx_create_produk_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdukTable extends Migration
{
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('nama_produk'); // Kolom nama_produk dengan tipe string
            $table->string('level_produk'); // Kolom level_produk dengan tipe string
            $table->text('deskripi_produk')->nullable(); // Kolom deskripi_produk dengan tipe text, nullable
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('produk');
    }
}