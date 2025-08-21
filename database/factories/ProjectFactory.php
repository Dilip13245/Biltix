<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'project_number' => 'PRJ-' . fake()->unique()->numberBetween(1000, 9999),
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'project_type' => fake()->randomElement(['residential', 'commercial', 'industrial', 'renovation']),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'critical']),
            'status' => fake()->randomElement(['planning', 'active', 'on_hold', 'completed']),
            'location' => fake()->address(),
            'start_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'end_date' => fake()->dateTimeBetween('now', '+2 years'),
            'budget' => fake()->numberBetween(100000, 10000000),
            'currency' => fake()->randomElement(['USD', 'EUR', 'GBP']),
            'created_by' => User::factory(),
            'progress_percentage' => fake()->numberBetween(0, 100),
        ];
    }
}