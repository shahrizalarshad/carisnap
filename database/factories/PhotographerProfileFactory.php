<?php

namespace Database\Factories;

use App\Enums\ProfileTier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PhotographerProfileFactory extends Factory
{
    public function definition(): array
    {
        $name = MalayTestData::studioName();

        return [
            'user_id' => User::factory()->photographer()->create([
                'name' => MalayTestData::personName(),
            ]),
            'slug' => Str::slug($name.'-'.fake()->unique()->numberBetween(1000, 9999)),
            'business_name' => $name,
            'bio' => MalayTestData::bio(),
            'location_area' => MalayTestData::locationArea(),
            'coverage_areas' => MalayTestData::coverageAreas(),
            'instagram_handle' => MalayTestData::instagramHandle(),
            'whatsapp_number' => MalayTestData::whatsappNumber(),
            'tier' => ProfileTier::Free,
            'verified_at' => now(),
            'featured_until' => null,
        ];
    }

    public function pro(): static
    {
        return $this->state(fn (array $attributes) => [
            'tier' => ProfileTier::Pro,
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verified_at' => null,
        ]);
    }
}
