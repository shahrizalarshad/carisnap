<?php

use App\Actions\CreateBookingRequest;
use App\Actions\CreateBookingRequestData;
use App\Enums\BookingStatus;
use App\Enums\EventType;
use App\Enums\UserRole;
use App\Models\PhotographerProfile;
use App\Models\User;
use App\Notifications\BookingRequestReceivedNotification;
use App\Notifications\NewBookingRequestNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('creates a guest booking request and notifies both parties', function () {
    Notification::fake();

    $photographer = User::factory()->photographer()->create();
    $profile = PhotographerProfile::factory()->create(['user_id' => $photographer->id]);

    $booking = app(CreateBookingRequest::class)->execute(new CreateBookingRequestData(
        profile: $profile,
        eventDate: now()->addMonth()->toDateString(),
        location: 'Kuala Lumpur',
        budgetFrom: 1500,
        budgetTo: 2500,
        message: 'Looking for a candid style.',
        guestName: 'Siti',
        guestPhone: '0123456789',
        guestEmail: 'siti@example.com',
    ));

    expect($booking->status)->toBe(BookingStatus::Pending)
        ->and($booking->guest_name)->toBe('Siti')
        ->and($booking->client_id)->toBeNull()
        ->and($booking->event_type)->toBe(EventType::Wedding);

    Notification::assertSentTo($photographer, NewBookingRequestNotification::class);
    Notification::assertSentOnDemand(
        BookingRequestReceivedNotification::class,
        fn ($notification, $channels, $notifiable) => $notifiable->routes['mail'] === 'siti@example.com'
    );
});

it('creates an authenticated client booking request', function () {
    Notification::fake();

    $photographer = User::factory()->photographer()->create();
    $profile = PhotographerProfile::factory()->create(['user_id' => $photographer->id]);
    $client = User::factory()->create(['role' => UserRole::Client, 'email' => 'client@example.com']);

    $booking = app(CreateBookingRequest::class)->execute(new CreateBookingRequestData(
        profile: $profile,
        eventDate: now()->addWeeks(2)->toDateString(),
        location: 'Selangor',
        budgetFrom: 2000,
        budgetTo: 3500,
        clientId: $client->id,
    ));

    expect($booking->client_id)->toBe($client->id)
        ->and($booking->guest_name)->toBeNull();

    Notification::assertSentTo($photographer, NewBookingRequestNotification::class);
    Notification::assertSentOnDemand(
        BookingRequestReceivedNotification::class,
        fn ($notification, $channels, $notifiable) => $notifiable->routes['mail'] === 'client@example.com'
    );
});

it('does not notify client when no email is available', function () {
    Notification::fake();

    $profile = PhotographerProfile::factory()->create();

    app(CreateBookingRequest::class)->execute(new CreateBookingRequestData(
        profile: $profile,
        eventDate: now()->addMonth()->toDateString(),
        location: 'Putrajaya',
        budgetFrom: 1000,
        budgetTo: 2000,
        guestName: 'Ali',
        guestPhone: '0123456789',
        guestEmail: null,
    ));

    Notification::assertSentTo($profile->user, NewBookingRequestNotification::class);
    Notification::assertSentOnDemandTimes(BookingRequestReceivedNotification::class, 0);
});
