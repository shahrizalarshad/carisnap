<?php

use App\Enums\BookingStatus;
use App\Models\BookingRequest;
use App\Models\Review;
use App\Notifications\RequestReviewNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('sends review requests for accepted bookings that occurred yesterday', function () {
    Notification::fake();

    // Valid booking (yesterday, accepted, no review)
    $validBooking = BookingRequest::factory()->guest()->create([
        'status' => BookingStatus::Accepted,
        'event_date' => now()->subDay(),
        'guest_email' => 'client@example.com',
    ]);

    // Invalid booking (not accepted)
    $pendingBooking = BookingRequest::factory()->create([
        'status' => BookingStatus::Pending,
        'event_date' => now()->subDay(),
    ]);

    // Invalid booking (accepted, but event is today)
    $todayBooking = BookingRequest::factory()->create([
        'status' => BookingStatus::Accepted,
        'event_date' => now(),
    ]);

    // Invalid booking (accepted, yesterday, but already has a review)
    $reviewedBooking = BookingRequest::factory()->create([
        'status' => BookingStatus::Accepted,
        'event_date' => now()->subDay(),
    ]);
    Review::factory()->create([
        'booking_request_id' => $reviewedBooking->id,
    ]);

    $this->artisan('reviews:request')
        ->expectsOutputToContain('Sent 1 review requests.')
        ->assertExitCode(0);

    // Assert notification sent to the valid booking email via Notification facade anonymous route
    Notification::assertSentOnDemand(
        RequestReviewNotification::class,
        function ($notification, $channels, $notifiable) use ($validBooking) {
            return $notifiable->routes['mail'] === 'client@example.com' &&
                   $notification->request->id === $validBooking->id;
        }
    );
});
