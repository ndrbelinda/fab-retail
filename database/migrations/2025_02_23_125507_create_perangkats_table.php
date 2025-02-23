<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerangkatsTable extends Migration
{
    public function up()
    {
        Schema::create('perangkats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_produk');
            $table->string('jenis_perangkat');
            $table->string('gambar_perangkat');
            $table->decimal('tarif_perangkat', 10);
            $table->text('deskripsi_perangkat')->nullable();
            $table->enum('is_verified_perangkat', ['draft', 'diajukan', 'diverifikasi'])->default('draft');
            $table->boolean('tampil_ekatalog')->default(false);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_produk')->references('id')->on('produk')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('perangkats');
    }
}