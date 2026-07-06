<?php

use App\Enums\AvailabilityStatus;
use App\Livewire\CreateBookingRequest;
use App\Mail\BookingRequestConfirmation;
use App\Mail\BookingRequestReceived;
use App\Models\PhotographerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('can submit a booking request as a guest', function () {
    Mail::fake();

    $profile = PhotographerProfile::factory()->create();

    Livewire::test(CreateBookingRequest::class, ['profile' => $profile])
        ->set('event_date', date('Y-m-d', strtotime('+1 day')))
        ->set('location', 'Kuala Lumpur')
        ->set('budget_range', '1000-2000')
        ->set('message', 'Hello!')
        ->set('guest_name', 'Ahmad')
        ->set('guest_phone', '0123456789')
        ->set('guest_email', 'ahmad@example.com')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('success', true);

    $this->assertDatabaseHas('booking_requests', [
        'profile_id' => $profile->id,
        'guest_name' => 'Ahmad',
        'location' => 'Kuala Lumpur',
        'budget_from' => 1000,
        'budget_to' => 2000,
        'client_id' => null,
    ]);

    Mail::assertQueued(BookingRequestReceived::class);
    Mail::assertQueued(BookingRequestConfirmation::class);
});

it('can submit a booking request as an authenticated user', function () {
    Mail::fake();

    $profile = PhotographerProfile::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(CreateBookingRequest::class, ['profile' => $profile])
        ->set('event_date', date('Y-m-d', strtotime('+2 days')))
        ->set('location', 'Selangor')
        ->set('budget_range', '3000-5000')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('success', true);

    $this->assertDatabaseHas('booking_requests', [
        'profile_id' => $profile->id,
        'client_id' => $user->id,
        'guest_name' => null,
        'location' => 'Selangor',
    ]);

    Mail::assertQueued(BookingRequestReceived::class);
    Mail::assertQueued(BookingRequestConfirmation::class);
});

it('shows warning when event date is not explicitly available', function () {
    $profile = PhotographerProfile::factory()->create();

    // Create an availability that does NOT match the test date
    $profile->availabilities()->create([
        'date' => date('Y-m-d', strtotime('+10 days')),
        'status' => AvailabilityStatus::Available,
    ]);

    Livewire::test(CreateBookingRequest::class, ['profile' => $profile])
        ->set('event_date', date('Y-m-d', strtotime('+1 day')))
        ->assertSet('showAvailabilityWarning', true);
});

it('does not show warning when event date is available', function () {
    $profile = PhotographerProfile::factory()->create();

    $date = date('Y-m-d', strtotime('+5 days'));

    $profile->availabilities()->create([
        'date' => $date,
        'status' => AvailabilityStatus::Available,
    ]);

    Livewire::test(CreateBookingRequest::class, ['profile' => $profile])
        ->set('event_date', $date)
        ->assertSet('showAvailabilityWarning', false);
});
