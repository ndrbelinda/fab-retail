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
            // Tambahkan unique constraint
            $table->unique(['id_produk', 'jenis_perangkat'], 'produk_perangkat_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('perangkats', function (Blueprint $table) {
            // Hapus unique constraint saat rollback
            $table->dropUnique('produk_perangkat_unique');
        });
    }
};