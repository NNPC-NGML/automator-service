<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerSite>
 */
class CustomerSiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->numberBetween(1, 50),
            'customer_id' => $this->faker->numberBetween(1, 50),
            'site_address' => $this->faker->address,
            'ngml_zone_id' => $this->faker->numberBetween(1, 50),
            'site_name' => $this->faker->company,
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'site_contact_person_name' => $this->faker->name,
            'site_contact_person_email' => $this->faker->unique()->safeEmail,
            'site_contact_person_phone_number' => $this->faker->phoneNumber,
            'site_contact_person_signature' => $this->faker->optional()->imageUrl(),
            'site_existing_status' => $this->faker->boolean,
            'created_by_user_id' => $this->faker->numberBetween(1, 50),
            'status' => $this->faker->boolean,
        ];
    }
}
