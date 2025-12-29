<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'Admin',
                'guard_name' => 'admin',
            ],
            [
                'id' => 2,
                'name' => 'Verified Author',
                'guard_name' => 'web',
            ],
            [
                'id' => 3,
                'name' => 'Author',
                'guard_name' => 'web',
            ],
            [
                'id' => 4,
                'name' => 'User',
                'guard_name' => 'web',
            ],
            [
                'id' => 5,
                'name' => 'Sub_admin',
                'guard_name' => 'web',
            ],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::create($role);
        }
    }
}
