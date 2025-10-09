<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SectionManagerSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'sectionmanager123@gmail.com';

        $roleId = DB::table('roles')->where('name', 'Section Manager')->value('id') ?? 3;

        $existing = DB::table('users')->where('email', $email)->first();

        if ($existing) {
            DB::table('users')
                ->where('id', $existing->id)
                ->update([
                    'name' => 'sectionmanager@123',
                    'password' => Hash::make('12345678'),
                    'role_id' => $roleId,
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('users')->insert([
                'name' => 'sectionmanager@123',
                'email' => $email,
                'password' => Hash::make('12345678'),
                'role_id' => $roleId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
