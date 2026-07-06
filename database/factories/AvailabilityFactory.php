<?php

namespace Database\Factories;

use App\Enums\AvailabilityStatus;
use App\Models\PhotographerProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvailabilityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'profile_id' => PhotographerProfile::factory(),
            'date' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'status' => fake()->randomElement(AvailabilityStatus::cases()),
        ];
    }
}
