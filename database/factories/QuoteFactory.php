<?php

namespace Database\Factories;

use App\Enums\QuoteStatus;
use App\Models\BookingRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_request_id' => BookingRequest::factory(),
            'amount' => fake()->numberBetween(1500, 5000),
            'message' => fake()->paragraph(),
            'valid_until' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'status' => QuoteStatus::Sent,
        ];
    }
}
