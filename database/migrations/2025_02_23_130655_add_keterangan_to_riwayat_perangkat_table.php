<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeteranganToRiwayatPerangkatTable extends Migration
{
    public function up()
    {
        Schema::table('riwayat_perangkat', function (Blueprint $table) {
            $table->text('keterangan')->nullable()->after('waktu'); // Tambahkan kolom `keterangan` setelah `waktu`
        });
    }

    public function down()
    {
        Schema::table('riwayat_perangkat', function (Blueprint $table) {
            $table->dropColumn('keterangan'); // Hapus kolom `keterangan` jika rollback
        });
    }
}