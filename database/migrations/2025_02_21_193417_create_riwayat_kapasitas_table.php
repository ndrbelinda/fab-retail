<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatKapasitasTable extends Migration
{
    public function up()
    {
        Schema::create('riwayat_kapasitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kapasitas_id')->constrained('capacities')->onDelete('cascade');
            $table->string('status'); // draft, diajukan, ditolak, diverifikasi
            $table->timestamp('waktu')->useCurrent(); // Waktu perubahan status
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_kapasitas');
    }
}