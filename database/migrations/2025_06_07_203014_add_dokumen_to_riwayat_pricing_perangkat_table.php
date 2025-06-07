<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('riwayat_pricing_perangkat', function (Blueprint $table) {
            $table->string('dokumen')->nullable()->after('pricing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('riwayat_pricing_perangkat', function (Blueprint $table) {
            $table->dropColumn('dokumen');
        });
    }
};
