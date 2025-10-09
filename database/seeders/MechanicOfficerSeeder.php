<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MechanicOfficerSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'Mechanicofficer123@gmail.com';

        $roleId = DB::table('roles')->where('name', 'Mechanic Officer')->value('id') ?? 4;

        $existing = DB::table('users')->where('email', $email)->first();

        if ($existing) {
            DB::table('users')
                ->where('id', $existing->id)
                ->update([
                    'name' => 'Mechanicofficer@123',
                    'password' => Hash::make('12345678'),
                    'role_id' => $roleId,
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('users')->insert([
                'name' => 'Mechanicofficer@123',
                'email' => $email,
                'password' => Hash::make('12345678'),
                'role_id' => $roleId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
