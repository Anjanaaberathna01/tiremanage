<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TransportOfficerSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'Transportofficer123@gmail.com';

        $roleId = DB::table('roles')->where('name', 'Transport Officer')->value('id') ?? 5;

        $existing = DB::table('users')->where('email', $email)->first();

        if ($existing) {
            DB::table('users')
                ->where('id', $existing->id)
                ->update([
                    'name' => 'Transportofficer@123',
                    'password' => Hash::make('12345678'),
                    'role_id' => $roleId,
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('users')->insert([
                'name' => 'Transportofficer@123',
                'email' => $email,
                'password' => Hash::make('12345678'),
                'role_id' => $roleId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
