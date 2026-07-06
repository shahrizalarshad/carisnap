<?php

use App\Actions\CreateBookingRequest;
use App\Actions\CreateBookingRequestData;
use App\Actions\LinkGuestBookingRequests;
use App\Enums\UserRole;
use App\Models\BookingRequest;
use App\Models\PhotographerProfile;
use App\Models\User;
use App\Support\PhoneNumber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('normalizes malaysian phone numbers for comparison', function () {
    expect(PhoneNumber::normalize('012-345 6789'))->toBe('0123456789')
        ->and(PhoneNumber::normalize('+60123456789'))->toBe('0123456789')
        ->and(PhoneNumber::matches('0123456789', '+60 12-345 6789'))->toBeTrue();
});

it('links past guest bookings when a client adds a matching phone', function () {
    $profile = PhotographerProfile::factory()->create();

    $guestBooking = BookingRequest::factory()->guest()->create([
        'profile_id' => $profile->id,
        'guest_phone' => '0123456789',
    ]);

    $client = User::factory()->create([
        'role' => UserRole::Client,
        'phone' => null,
    ]);

    $client->update(['phone' => '+60123456789']);

    expect($guestBooking->fresh())
        ->client_id->toBe($client->id)
        ->guest_phone->toBeNull()
        ->guest_name->toBeNull();
});

it('auto links a guest booking when the phone already belongs to a client', function () {
    $profile = PhotographerProfile::factory()->create();
    $client = User::factory()->create([
        'role' => UserRole::Client,
        'phone' => '0123456789',
    ]);

    $booking = app(CreateBookingRequest::class)->execute(new CreateBookingRequestData(
        profile: $profile,
        eventDate: now()->addMonth()->toDateString(),
        location: 'Kuala Lumpur',
        budgetFrom: 1500,
        budgetTo: 2500,
        guestName: 'Siti',
        guestPhone: '+60123456789',
        guestEmail: 'siti@example.com',
    ));

    expect($booking->client_id)->toBe($client->id)
        ->and($booking->guest_phone)->toBeNull()
        ->and($booking->guest_name)->toBeNull();
});

it('does not link guest bookings to photographers', function () {
    $profile = PhotographerProfile::factory()->create();

    BookingRequest::factory()->guest()->create([
        'profile_id' => $profile->id,
        'guest_phone' => '0123456789',
    ]);

    $photographer = User::factory()->photographer()->create([
        'phone' => '0123456789',
    ]);

    expect(app(LinkGuestBookingRequests::class)->execute($photographer))->toBe(0);
});

it('does not link bookings with a different guest phone', function () {
    $profile = PhotographerProfile::factory()->create();

    $guestBooking = BookingRequest::factory()->guest()->create([
        'profile_id' => $profile->id,
        'guest_phone' => '0123456789',
    ]);

    $client = User::factory()->create([
        'role' => UserRole::Client,
        'phone' => '0198765432',
    ]);

    app(LinkGuestBookingRequests::class)->execute($client);

    expect($guestBooking->fresh()->client_id)->toBeNull();
});

it('links guest bookings when a client updates phone via profile', function () {
    $profile = PhotographerProfile::factory()->create();

    $guestBooking = BookingRequest::factory()->guest()->create([
        'profile_id' => $profile->id,
        'guest_phone' => '0123456789',
    ]);

    $client = User::factory()->create([
        'role' => UserRole::Client,
        'phone' => null,
    ]);

    $this->actingAs($client)
        ->patch(route('profile.update'), [
            'name' => $client->name,
            'email' => $client->email,
            'phone' => '0123456789',
        ])
        ->assertRedirect(route('profile.edit'));

    expect($guestBooking->fresh()->client_id)->toBe($client->id);
});
