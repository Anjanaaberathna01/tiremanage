<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'admin123',
            'email' => 'admin123@gmail.com',
            'password' => Hash::make('12345678'),
            'role_id' => 1, // Assuming 'Admin' is ID 1 in roles table
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
