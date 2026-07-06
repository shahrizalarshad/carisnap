<?php

use App\Models\PhotographerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('allows admin to perform any action on any profile', function () {
    $admin = User::factory()->admin()->create();
    $profile = PhotographerProfile::factory()->create();

    expect(Gate::forUser($admin)->allows('view', $profile))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('update', $profile))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('delete', $profile))->toBeTrue();
});

it('allows photographers to view and update only their own profile', function () {
    $owner = User::factory()->photographer()->create();
    $ownProfile = PhotographerProfile::factory()->create(['user_id' => $owner->id]);

    $otherProfile = PhotographerProfile::factory()->create();

    expect(Gate::forUser($owner)->allows('view', $ownProfile))->toBeTrue()
        ->and(Gate::forUser($owner)->allows('update', $ownProfile))->toBeTrue()
        ->and(Gate::forUser($owner)->allows('view', $otherProfile))->toBeFalse()
        ->and(Gate::forUser($owner)->allows('update', $otherProfile))->toBeFalse();
});

it('allows photographers without a profile to create one', function () {
    $photographer = User::factory()->photographer()->create();

    expect(Gate::forUser($photographer)->allows('create', PhotographerProfile::class))->toBeTrue();
});

it('prevents photographers who already have a profile from creating another', function () {
    $photographer = User::factory()->photographer()->create();
    PhotographerProfile::factory()->create(['user_id' => $photographer->id]);

    expect(Gate::forUser($photographer)->allows('create', PhotographerProfile::class))->toBeFalse();
});

it('denies clients access to photographer profile management', function () {
    $client = User::factory()->create();
    $profile = PhotographerProfile::factory()->create();

    expect(Gate::forUser($client)->allows('viewAny', PhotographerProfile::class))->toBeFalse()
        ->and(Gate::forUser($client)->allows('view', $profile))->toBeFalse()
        ->and(Gate::forUser($client)->allows('create', PhotographerProfile::class))->toBeFalse();
});
