<?php

use App\Models\Package;
use App\Models\PhotographerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('allows admin to manage any package', function () {
    $admin = User::factory()->admin()->create();
    $package = Package::factory()->create();

    expect(Gate::forUser($admin)->allows('update', $package))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('delete', $package))->toBeTrue();
});

it('allows photographers to manage only their own packages', function () {
    $photographer = User::factory()->photographer()->create();
    $profile = PhotographerProfile::factory()->create(['user_id' => $photographer->id]);
    $ownPackage = Package::factory()->create(['profile_id' => $profile->id]);

    $otherPackage = Package::factory()->create();

    expect(Gate::forUser($photographer)->allows('viewAny', Package::class))->toBeTrue()
        ->and(Gate::forUser($photographer)->allows('view', $ownPackage))->toBeTrue()
        ->and(Gate::forUser($photographer)->allows('update', $ownPackage))->toBeTrue()
        ->and(Gate::forUser($photographer)->allows('delete', $ownPackage))->toBeTrue()
        ->and(Gate::forUser($photographer)->allows('view', $otherPackage))->toBeFalse()
        ->and(Gate::forUser($photographer)->allows('update', $otherPackage))->toBeFalse()
        ->and(Gate::forUser($photographer)->allows('delete', $otherPackage))->toBeFalse();
});

it('denies clients access to package management', function () {
    $client = User::factory()->create();
    $package = Package::factory()->create();

    expect(Gate::forUser($client)->allows('viewAny', Package::class))->toBeFalse()
        ->and(Gate::forUser($client)->allows('view', $package))->toBeFalse()
        ->and(Gate::forUser($client)->allows('create', Package::class))->toBeFalse();
});
