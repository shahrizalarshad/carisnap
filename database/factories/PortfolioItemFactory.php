<?php

namespace Database\Factories;

use App\Enums\EventType;
use App\Models\PhotographerProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class PortfolioItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'profile_id' => PhotographerProfile::factory(),
            'event_type' => EventType::Wedding,
            'caption' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
