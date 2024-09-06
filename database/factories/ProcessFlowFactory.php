<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProcessFlow>
 */
class ProcessFlowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "id" => fake()->numberBetween(1, 100),
            "name" => fake()->word(),
            "start_step_id" => fake()->numberBetween(1, 100),
            "frequency" => "monthly",
            "status" => 1,
            "frequency_for" => "customers",
            "day" => fake()->numberBetween(1, 30),
            "week" => fake()->numberBetween(1, 4),
        ];
    }
}
