<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePricingTypeInRiwayatPricingKapasitasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('riwayat_pricing_kapasitas', function (Blueprint $table) {
            // Ubah tipe kolom pricing menjadi integer
            $table->integer('pricing')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('riwayat_pricing_kapasitas', function (Blueprint $table) {
            // Jika ingin rollback, kembalikan ke tipe semula (decimal/float)
            $table->decimal('pricing', 12, 2)->change();
        });
    }
}