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
        $name = fake()->company();

        return [
            'user_id' => User::factory()->photographer(),
            'slug' => Str::slug($name.'-'.fake()->randomNumber(4)),
            'business_name' => $name,
            'bio' => fake()->paragraphs(2, true),
            'location_area' => fake()->city(),
            'coverage_areas' => [fake()->city(), fake()->city()],
            'instagram_handle' => '@'.fake()->word(),
            'whatsapp_number' => fake()->phoneNumber(),
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
