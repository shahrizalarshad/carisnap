<?php

use App\Models\Availability;
use App\Models\PhotographerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('allows admin to manage any availability', function () {
    $admin = User::factory()->admin()->create();
    $availability = Availability::factory()->create();

    expect(Gate::forUser($admin)->allows('update', $availability))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('delete', $availability))->toBeTrue();
});

it('allows photographers to manage only their own availability', function () {
    $photographer = User::factory()->photographer()->create();
    $profile = PhotographerProfile::factory()->create(['user_id' => $photographer->id]);
    $ownAvailability = Availability::factory()->create(['profile_id' => $profile->id]);

    $otherAvailability = Availability::factory()->create();

    expect(Gate::forUser($photographer)->allows('view', $ownAvailability))->toBeTrue()
        ->and(Gate::forUser($photographer)->allows('update', $ownAvailability))->toBeTrue()
        ->and(Gate::forUser($photographer)->allows('delete', $ownAvailability))->toBeTrue()
        ->and(Gate::forUser($photographer)->allows('view', $otherAvailability))->toBeFalse()
        ->and(Gate::forUser($photographer)->allows('update', $otherAvailability))->toBeFalse()
        ->and(Gate::forUser($photographer)->allows('delete', $otherAvailability))->toBeFalse();
});

it('denies clients access to availability management', function () {
    $client = User::factory()->create();
    $availability = Availability::factory()->create();

    expect(Gate::forUser($client)->allows('viewAny', Availability::class))->toBeFalse()
        ->and(Gate::forUser($client)->allows('view', $availability))->toBeFalse()
        ->and(Gate::forUser($client)->allows('create', Availability::class))->toBeFalse();
});
