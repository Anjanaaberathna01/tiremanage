<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SectionManagerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'sectionmanager@123',
            'email' => 'sectionmanager123@gmail.com',
            'password' => Hash::make('12345678'),
            'role_id' => 2, // Assuming Driver is ID 2
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
