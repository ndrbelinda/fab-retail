
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatPerangkatTable extends Migration
{
    public function up()
    {
        Schema::create('riwayat_perangkat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perangkat_id')->constrained('perangkats')->onDelete('cascade');
            $table->string('status'); // draft, diajukan, ditolak, diverifikasi
            $table->timestamp('waktu')->useCurrent(); // Waktu perubahan status
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_perangkat');
    }
}