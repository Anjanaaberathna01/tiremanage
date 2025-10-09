<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Admin',
            'Driver',
            'Section Manager',
            'Mechanic Officer',
            'Transport Officer',
        ];

        foreach ($roles as $name) {
            DB::table('roles')->updateOrInsert(
                ['name' => $name],
                ['name' => $name]
            );
        }
    }
}
