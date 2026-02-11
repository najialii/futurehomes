<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'display_order' => fake()->numberBetween(1, 100),
            'is_active' => fake()->boolean(),
        ];
    }
}
