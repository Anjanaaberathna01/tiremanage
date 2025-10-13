<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DriverUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'driver123@gmail.com';

        $roleId = DB::table('roles')->where('name', 'Driver')->value('id') ?? 2;

        $existing = DB::table('users')->where('email', $email)->first();

        if ($existing) {
            DB::table('users')
                ->where('id', $existing->id)
                ->update([
                    'name' => 'driver@123',
                    'password' => Hash::make('12345678'),
                    'role_id' => $roleId,
                    'must_change_password' => true, // 👈 add this line
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('users')->insert([
                'name' => 'driver@123',
                'email' => $email,
                'password' => Hash::make('12345678'),
                'role_id' => $roleId,
                'must_change_password' => true, // 👈 add this line
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
