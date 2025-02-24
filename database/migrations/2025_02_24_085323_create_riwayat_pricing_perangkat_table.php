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
        Schema::create('riwayat_pricing_perangkat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('perangkat_id');
            $table->decimal('pricing', 15, 2);
            $table->timestamps();

            // foreign key ke perangkat
            $table->foreign('perangkat_id')->references('id')->on('perangkats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pricing_perangkat');
    }
};
