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
        Schema::table('faqs', function (Blueprint $table) {
            $table->renameColumn('is_verified_kapasitas', 'is_verified_faq'); // Ubah nama kolom
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->renameColumn('is_verified_faq', 'is_verified_kapasitas'); // Kembalikan nama kolom jika di-rollback
        });
    }
};
