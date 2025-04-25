<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('produk')->insert([
            [
                'nama_produk' => 'Terralink',
                'level_produk' => '2',
                'deskripsi_produk' => 'Layanan internet satelit berkecepatan tinggi untuk lokasi tetap, memastikan konektivitas stabil di area terpencil dan sulit dijangkau',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_produk' => 'Swiftlink',
                'level_produk' => '2',
                'deskripsi_produk' => 'Internet satelit bergerak dengan akses cepat dan andal untuk kendaraan operasional, kapal, dan pesawat, menjaga konektivitas di mana pun',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
