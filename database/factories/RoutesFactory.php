<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Route>
 */
class RoutesFactory extends Factory
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
            "link" => fake()->word(),
            "dynamic_content" => json_encode(["customer_id", "customer_site_id"]),
            "status" => 1,
        ];
    }
}
