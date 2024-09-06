<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProcessFlowStep>
 */
class ProcessFlowStepFactory extends Factory
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
            "name" => fake()->name,
            "step_route" =>  fake()->numberBetween(1, 100),
            "assignee_user_route" => fake()->word(),
            "next_user_designation" => fake()->numberBetween(1, 100),
            "next_user_department" => fake()->numberBetween(1, 100),
            "next_user_unit" => fake()->numberBetween(1, 100),
            "process_flow_id" => fake()->numberBetween(1, 100),
            "next_user_location" => fake()->numberBetween(1, 100),
            "step_type" => "approve_auto_assign",
            "user_type" => "customer",
            "next_step_id" => fake()->numberBetween(1, 100),
        ];
    }
}
