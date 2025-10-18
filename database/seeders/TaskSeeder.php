<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $userId = User::where('name', 'User')->first()->id;
        
        Task::create([
            'title' => 'Initial Task',
            'description' => 'This is the first task seeded into the database.',
            'user_id' => $userId,
        ]);
    }
}
