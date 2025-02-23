<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKapasitasIdToRiwayatPricingKapasitasTable extends Migration
{
    public function up()
    {
        Schema::table('riwayat_pricing_kapasitas', function (Blueprint $table) {
            // Tambahkan kolom kapasitas_id
            $table->unsignedBigInteger('kapasitas_id')->after('id');

            // Tambahkan foreign key ke tabel capacities
            $table->foreign('kapasitas_id')->references('id')->on('capacities')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('riwayat_pricing_kapasitas', function (Blueprint $table) {
            // Hapus foreign key
            $table->dropForeign(['kapasitas_id']);

            // Hapus kolom kapasitas_id
            $table->dropColumn('kapasitas_id');
        });
    }
}