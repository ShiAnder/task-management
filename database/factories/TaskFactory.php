<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(3),
            'status' => $this->faker->randomElement(['Pending', 'Completed']),
            'due_date' => $this->faker->dateTimeBetween('now', '+2 weeks')->format('Y-m-d'),
        ];
    }
    
    public function pending(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'Pending',
            ];
        });
    }
    
    public function completed(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'Completed',
            ];
        });
    }
}