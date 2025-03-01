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
                'username' => 'zigroseno',
                'password' => Hash::make('password123'),
                'role' => 'gm_rcs',
            ],
            [
                'username' => 'herunugraha',
                'password' => Hash::make('password123'),
                'role' => 'staff',
            ],
            [
                'username' => 'risnando',
                'password' => Hash::make('password123'),
                'role' => 'avp',
            ],
        ]);
    }
}
