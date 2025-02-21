<?php

// database/migrations/2025_02_21_145612_create_capacities_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapacitiesTable extends Migration
{
    public function up()
    {
        Schema::create('capacities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_produk');
            $table->string('besar_kapasitas');
            $table->decimal('tarif_kapasitas', 10, 2);
            $table->text('deskripsi_kapasitas')->nullable();
            $table->enum('is_verified_kapasitas', ['draft', 'diajukan', 'diverifikasi'])->default('draft');
            $table->boolean('tampil_ekatalog')->default(false);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_produk')->references('id')->on('produk')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('capacities');
    }
}