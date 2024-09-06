<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AutomatorTask>
 */
class AutomatorTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'processflow_history_id' => fake()->numberBetween(1, 100),
            'formbuilder_data_id' => fake()->numberBetween(1, 100),
            'entity_id' => fake()->numberBetween(1, 100),
            'entity_site_id' => fake()->numberBetween(1, 100),
            'user_id' => fake()->numberBetween(1, 100),
            'processflow_id' => fake()->numberBetween(1, 100),
            'processflow_step_id' => fake()->numberBetween(1, 100),
            'task_status' => 0,
        ];
    }
}
