<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    DB::table('tasks')->insert([
        'user_id' => 1,
        'title' => 'Sample Task',
        'description' => 'This is a sample task description that meets the minimum requirement of 10 characters.',
        'status' => 'Pending',
        'due_date' => now()->addDays(7),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
}
