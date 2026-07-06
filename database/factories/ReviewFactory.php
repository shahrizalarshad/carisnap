<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Models\BookingRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_request_id' => BookingRequest::factory()->state(['status' => BookingStatus::Accepted]),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => MalayTestData::reviewComment(),
            'published_at' => now(),
        ];
    }
}
