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
            $table->decimal('tarif_perangkat', 15, 2)->change(); // Ubah ke DECIMAL(15, 2)
        });
    }

    public function down()
    {
        Schema::table('perangkats', function (Blueprint $table) {
            $table->decimal('tarif_perangkat', 10, 2)->change(); // Kembalikan ke DECIMAL(10, 2)
        });
    }
};
