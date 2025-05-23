<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'gmrcs',
                'password' => Hash::make('password123'),
                'email' => 'avpprodukxyz@gmail.com',
                'role' => 'gm_rcs',
            ],
            [
                'username' => 'staffproduk',
                'password' => Hash::make('password123'),
                'email' => 'staffprodukxyz@gmail.com',
                'role' => 'staff',
            ],
            [
                'username' => 'gmproduk',
                'password' => Hash::make('password123'),
                'email' => 'gmprodukxyz@gmail.com',
                'role' => 'avp',
            ],
        ]);
    }
}
