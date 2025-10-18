<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Admin',
            'description' => 'Administrator role with full permissions.',
        ]);

        Role::create([
            'name' => 'User',
            'description' => 'Standard user role with limited permissions.',
        ]);
    }
}
