<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('allows admin to manage users', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    expect(Gate::forUser($admin)->allows('viewAny', User::class))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('view', $user))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('update', $user))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('delete', $user))->toBeTrue();
});

it('denies photographers from managing users', function () {
    $photographer = User::factory()->photographer()->create();
    $user = User::factory()->create();

    expect(Gate::forUser($photographer)->allows('viewAny', User::class))->toBeFalse()
        ->and(Gate::forUser($photographer)->allows('view', $user))->toBeFalse()
        ->and(Gate::forUser($photographer)->allows('create', User::class))->toBeFalse()
        ->and(Gate::forUser($photographer)->allows('update', $user))->toBeFalse()
        ->and(Gate::forUser($photographer)->allows('delete', $user))->toBeFalse();
});

it('denies clients from managing users', function () {
    $client = User::factory()->create();
    $user = User::factory()->create();

    expect(Gate::forUser($client)->allows('viewAny', User::class))->toBeFalse()
        ->and(Gate::forUser($client)->allows('view', $user))->toBeFalse()
        ->and(Gate::forUser($client)->allows('create', User::class))->toBeFalse()
        ->and(Gate::forUser($client)->allows('update', $user))->toBeFalse()
        ->and(Gate::forUser($client)->allows('delete', $user))->toBeFalse();
});
