<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaqsTable extends Migration
{
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id(); // ID FAQ (primary key)
            $table->unsignedBigInteger('id_produk'); // Foreign key ke tabel produk
            $table->text('pertanyaan'); // Kolom untuk pertanyaan
            $table->text('jawaban'); // Kolom untuk jawaban
            $table->boolean('tampil_ekatalog')->default(false); // Opsi tampil di e-Katalog
            $table->timestamps(); // Kolom created_at dan updated_at

            // Foreign key constraint
            $table->foreign('id_produk')->references('id')->on('produk')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('faqs');
    }
}