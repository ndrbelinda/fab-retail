<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNominalToRiwayatPricingKapasitasTable extends Migration
{
    public function up()
    {
        Schema::table('riwayat_pricing_kapasitas', function (Blueprint $table) {
            $table->decimal('nominal', 10, 2)->after('kapasitas_id'); // Tambahkan kolom nominal
        });
    }

    public function down()
    {
        Schema::table('riwayat_pricing_kapasitas', function (Blueprint $table) {
            $table->dropColumn('nominal'); // Hapus kolom nominal
        });
    }
}