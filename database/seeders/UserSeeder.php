<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = Role::where('name', 'Admin')->first()->id;
        $userId = Role::where('name', 'User')->first()->id;

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.admin',
            'password' => Hash::make('adminadmin'),
            'role_id' => $adminId,
        ]);

        User::factory()->create([
            'name' => 'User',
            'email' => 'user@user.user',
            'password' => Hash::make('useruser'),
            'role_id' => $userId,
        ]);
    }
}
