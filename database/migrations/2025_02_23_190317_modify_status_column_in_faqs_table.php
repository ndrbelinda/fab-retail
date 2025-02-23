<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatPricingKapasitasTable extends Migration
{
    public function up()
    {
        Schema::create('riwayat_pricing_kapasitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kapasitas_id')->constrained('capacities')->onDelete('cascade');
            $table->decimal('pricing', 10, 2); // Menyimpan nominal pricing
            $table->timestamps(); // Kolom `created_at` akan digunakan sebagai waktu perubahan
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_pricing_kapasitas');
    }
}