<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameNominalToPricingInRiwayatPricingKapasitasTable extends Migration
{
    public function up()
    {
        Schema::table('riwayat_pricing_kapasitas', function (Blueprint $table) {
            $table->renameColumn('nominal', 'pricing'); // Ubah nama kolom
        });
    }

    public function down()
    {
        Schema::table('riwayat_pricing_kapasitas', function (Blueprint $table) {
            $table->renameColumn('pricing', 'nominal'); // Kembalikan nama kolom
        });
    }
}