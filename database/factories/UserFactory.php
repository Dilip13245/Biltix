<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->phoneNumber(),
            'password' => Hash::make('password123'),
            'role' => fake()->randomElement(['contractor', 'site_engineer', 'consultant', 'project_manager', 'stakeholder']),
            'company_name' => fake()->company(),
            'designation' => fake()->jobTitle(),
            'employee_count' => fake()->numberBetween(1, 500),
            'is_active' => true,
        ];
    }

    public function contractor()
    {
        return $this->state(['role' => 'contractor']);
    }

    public function siteEngineer()
    {
        return $this->state(['role' => 'site_engineer']);
    }
}