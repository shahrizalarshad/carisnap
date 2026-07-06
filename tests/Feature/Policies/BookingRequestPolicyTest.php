<?php

use App\Models\BookingRequest;
use App\Models\PhotographerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('allows admin to perform any action on booking requests', function () {
    $admin = User::factory()->admin()->create();
    $booking = BookingRequest::factory()->create();

    expect(Gate::forUser($admin)->allows('view', $booking))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('update', $booking))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('delete', $booking))->toBeTrue();
});

it('allows photographers to view and update requests for their own profile', function () {
    $photographer = User::factory()->photographer()->create();
    $profile = PhotographerProfile::factory()->create(['user_id' => $photographer->id]);
    $ownBooking = BookingRequest::factory()->create(['profile_id' => $profile->id]);

    $otherBooking = BookingRequest::factory()->create();

    expect(Gate::forUser($photographer)->allows('viewAny', BookingRequest::class))->toBeTrue()
        ->and(Gate::forUser($photographer)->allows('view', $ownBooking))->toBeTrue()
        ->and(Gate::forUser($photographer)->allows('update', $ownBooking))->toBeTrue()
        ->and(Gate::forUser($photographer)->allows('view', $otherBooking))->toBeFalse()
        ->and(Gate::forUser($photographer)->allows('update', $otherBooking))->toBeFalse();
});

it('allows clients to view only their own booking requests', function () {
    $client = User::factory()->create();
    $ownBooking = BookingRequest::factory()->create(['client_id' => $client->id]);
    $otherBooking = BookingRequest::factory()->create();

    expect(Gate::forUser($client)->allows('view', $ownBooking))->toBeTrue()
        ->and(Gate::forUser($client)->allows('view', $otherBooking))->toBeFalse();
});

it('prevents clients from updating or deleting booking requests', function () {
    $client = User::factory()->create();
    $ownBooking = BookingRequest::factory()->create(['client_id' => $client->id]);

    expect(Gate::forUser($client)->allows('viewAny', BookingRequest::class))->toBeFalse()
        ->and(Gate::forUser($client)->allows('update', $ownBooking))->toBeFalse()
        ->and(Gate::forUser($client)->allows('delete', $ownBooking))->toBeFalse();
});

it('denies create and delete for all non-admin users', function () {
    $photographer = User::factory()->photographer()->create();
    PhotographerProfile::factory()->create(['user_id' => $photographer->id]);
    $client = User::factory()->create();
    $booking = BookingRequest::factory()->create();

    expect(Gate::forUser($photographer)->allows('create', BookingRequest::class))->toBeFalse()
        ->and(Gate::forUser($photographer)->allows('delete', $booking))->toBeFalse()
        ->and(Gate::forUser($client)->allows('create', BookingRequest::class))->toBeFalse();
});
