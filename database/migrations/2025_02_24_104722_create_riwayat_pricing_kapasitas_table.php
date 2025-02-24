<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_pricing_kapasitas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kapasitas_id');
            $table->decimal('pricing', 15, 2);
            $table->timestamps();

            // foreign key ke perangkat
            $table->foreign('kapasitas_id')->references('id')->on('capacities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pricing_kapasitas');
    }
};
