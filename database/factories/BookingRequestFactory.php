<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Enums\EventType;
use App\Models\PhotographerProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingRequestFactory extends Factory
{
    public function definition(): array
    {
        $budgetFrom = fake()->numberBetween(1000, 3000);

        return [
            'client_id' => User::factory(),
            'guest_name' => null,
            'guest_phone' => null,
            'guest_email' => null,
            'profile_id' => PhotographerProfile::factory(),
            'package_id' => null,
            'event_type' => EventType::Wedding,
            'event_date' => fake()->dateTimeBetween('+1 month', '+1 year')->format('Y-m-d'),
            'location' => fake()->city(),
            'budget_from' => $budgetFrom,
            'budget_to' => $budgetFrom + 2000,
            'message' => fake()->paragraph(),
            'status' => fake()->randomElement(BookingStatus::cases()),
            'responded_at' => null,
        ];
    }

    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'client_id' => null,
            'guest_name' => fake()->name(),
            'guest_phone' => fake()->phoneNumber(),
            'guest_email' => fake()->safeEmail(),
        ]);
    }
}
