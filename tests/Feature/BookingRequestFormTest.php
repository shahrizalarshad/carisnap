<?php

use App\Enums\AvailabilityStatus;
use App\Enums\UserRole;
use App\Livewire\BookingRequestForm;
use App\Models\Availability;
use App\Models\Package;
use App\Models\PhotographerProfile;
use App\Models\User;
use App\Notifications\NewBookingRequestNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->photographerUser = User::factory()->create(['role' => UserRole::Photographer]);
    $this->profile = PhotographerProfile::factory()->create([
        'user_id' => $this->photographerUser->id,
        'verified_at' => now(),
        'coverage_areas' => ['Kuala Lumpur', 'Selangor'],
    ]);
    $this->package = Package::factory()->create([
        'profile_id' => $this->profile->id,
        'price_from' => 1500,
    ]);

    Notification::fake();
});

it('allows guest to submit booking request', function () {
    Livewire::test(BookingRequestForm::class, ['profile' => $this->profile, 'package' => $this->package])
        ->set('guest_name', 'Abu Bakar')
        ->set('guest_phone', '0123456789')
        ->set('guest_email', 'abu@example.com')
        ->set('event_date', date('Y-m-d', strtotime('+1 month')))
        ->set('location', 'Kuala Lumpur')
        ->set('budget_from', 1500)
        ->set('budget_to', 2500)
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('booking_requests', [
        'profile_id' => $this->profile->id,
        'guest_name' => 'Abu Bakar',
        'location' => 'Kuala Lumpur',
    ]);

    Notification::assertSentTo($this->photographerUser, NewBookingRequestNotification::class);
});

it('allows authenticated client to submit booking request', function () {
    $client = User::factory()->create(['role' => UserRole::Client]);

    $this->actingAs($client);

    Livewire::test(BookingRequestForm::class, ['profile' => $this->profile])
        ->set('event_date', date('Y-m-d', strtotime('+1 month')))
        ->set('location', 'Selangor')
        ->set('budget_from', 2000)
        ->set('budget_to', 3000)
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('booking_requests', [
        'profile_id' => $this->profile->id,
        'client_id' => $client->id,
        'location' => 'Selangor',
    ]);

    Notification::assertSentTo($this->photographerUser, NewBookingRequestNotification::class);
});

it('warns about date unavailability', function () {
    $date = date('Y-m-d', strtotime('+2 weeks'));

    Availability::factory()->create([
        'profile_id' => $this->profile->id,
        'date' => $date,
        'status' => AvailabilityStatus::Booked,
    ]);

    Livewire::test(BookingRequestForm::class, ['profile' => $this->profile])
        ->set('event_date', $date)
        ->assertSet('is_date_unavailable', true);
});
