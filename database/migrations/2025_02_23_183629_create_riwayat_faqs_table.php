<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatFaqsTable extends Migration
{
    public function up()
    {
        Schema::create('riwayat_faqs', function (Blueprint $table) {
            $table->id(); // ID riwayat (primary key)
            $table->foreignId('faq_id')->constrained('faqs')->onDelete('cascade'); // Foreign key ke tabel faqs
            $table->string('status'); // Status perubahan (misalnya: draft, diajukan, diverifikasi, ditolak)
            $table->timestamp('waktu')->useCurrent(); // Waktu perubahan status
            $table->text('keterangan')->nullable(); // Keterangan perubahan (opsional)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_faqs');
    }
}