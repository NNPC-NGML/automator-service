<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProcessFlowHistory>
 */
class ProcessFlowHistoryFactory extends Factory
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
            "task_id" => fake()->numberBetween(1, 100),
            "step_id" => fake()->numberBetween(1, 100),
            "process_flow_id" => fake()->numberBetween(1, 100),
            "user_id" => fake()->numberBetween(1, 100),
            "for" => fake()->numberBetween(1, 100),
            "for_id" => "",
            "form_builder_id" => fake()->numberBetween(1, 100),
            "approval" => 1,
            "status" => 1,
        ];
    }
}
