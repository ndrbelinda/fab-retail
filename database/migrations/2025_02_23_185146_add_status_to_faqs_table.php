<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToFaqsTable extends Migration
{
    public function up()
    {
        Schema::table('faqs', function (Blueprint $table) {
            // Tambahkan kolom status dengan tipe enum dan default value 'draft'
            $table->enum('status', ['draft', 'diajukan', 'diverifikasi'])->default('draft');
        });
    }

    public function down()
    {
        Schema::table('faqs', function (Blueprint $table) {
            // Hapus kolom status jika rollback
            $table->dropColumn('status');
        });
    }
}