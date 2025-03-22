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
        Schema::table('perangkats', function (Blueprint $table) {
            $table->integer('tarif_perangkat')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perangkats', function (Blueprint $table) {
            $table->string('tarif_perangkat')->change(); // Ubah kembali ke tipe data sebelumnya jika diperlukan
        });
    }
};
