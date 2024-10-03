<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Skillz\Nnpcreusable\Models\Location;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{

    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'location' => fake()->streetAddress(),
            'zone' => fake()->city(),
            'state' => fake()->city(),
        ];
    }
}
