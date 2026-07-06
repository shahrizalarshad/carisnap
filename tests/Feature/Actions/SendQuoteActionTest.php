<?php

use App\Actions\DeclineBookingRequest;
use App\Actions\SendQuote;
use App\Actions\SendQuoteData;
use App\Enums\BookingStatus;
use App\Enums\QuoteStatus;
use App\Models\BookingRequest;
use App\Notifications\BookingDeclinedNotification;
use App\Notifications\QuoteReceivedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('sends a quote and updates booking request status', function () {
    Notification::fake();

    $booking = BookingRequest::factory()->guest()->create([
        'status' => BookingStatus::Pending,
        'guest_email' => 'client@example.com',
    ]);

    $quote = app(SendQuote::class)->execute($booking, new SendQuoteData(
        amount: 2500,
        message: 'Includes 8 hours coverage.',
    ));

    $booking->refresh();

    expect($quote->status)->toBe(QuoteStatus::Sent)
        ->and($quote->amount)->toBe(2500)
        ->and($quote->message)->toBe('Includes 8 hours coverage.')
        ->and($booking->status)->toBe(BookingStatus::Quoted)
        ->and($booking->responded_at)->not->toBeNull();

    Notification::assertSentOnDemand(
        QuoteReceivedNotification::class,
        function ($notification, $channels, $notifiable) use ($quote) {
            return $notifiable->routes['mail'] === 'client@example.com'
                && $notification->quote->id === $quote->id;
        }
    );
});

it('declines a booking request and notifies the client', function () {
    Notification::fake();

    $booking = BookingRequest::factory()->guest()->create([
        'status' => BookingStatus::Pending,
        'guest_email' => 'guest@example.com',
    ]);

    app(DeclineBookingRequest::class)->execute($booking);

    $booking->refresh();

    expect($booking->status)->toBe(BookingStatus::Declined)
        ->and($booking->responded_at)->not->toBeNull();

    Notification::assertSentOnDemand(
        BookingDeclinedNotification::class,
        function ($notification, $channels, $notifiable) use ($booking) {
            return $notifiable->routes['mail'] === 'guest@example.com'
                && $notification->request->id === $booking->id;
        }
    );
});
