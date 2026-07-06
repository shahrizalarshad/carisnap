<?php

namespace Database\Factories;

use App\Enums\EventType;
use App\Models\PhotographerProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'profile_id' => PhotographerProfile::factory(),
            'name' => MalayTestData::packageName(),
            'event_type' => EventType::Wedding,
            'price_from' => fake()->numberBetween(1000, 5000),
            'deliverables' => MalayTestData::deliverables(),
            'duration_hours' => fake()->numberBetween(4, 12),
            'is_active' => true,
        ];
    }
}
