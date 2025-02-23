<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyStatusColumnInFaqsTable extends Migration
{
    public function up()
    {
        // Ubah tipe data kolom status menjadi enum dengan nilai yang diperbarui
        Schema::table('faqs', function (Blueprint $table) {
            $table->enum('status', ['draft', 'diajukan', 'diverifikasi', 'ditolak'])->default('draft')->change();
        });
    }

    public function down()
    {
        // Kembalikan ke enum sebelumnya jika rollback
        Schema::table('faqs', function (Blueprint $table) {
            $table->enum('status', ['draft', 'diajukan', 'diverifikasi'])->default('draft')->change();
        });
    }
}