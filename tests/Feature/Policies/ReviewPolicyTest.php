<?php

use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('allows admin to manage reviews', function () {
    $admin = User::factory()->admin()->create();
    $review = Review::factory()->create();

    expect(Gate::forUser($admin)->allows('viewAny', Review::class))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('view', $review))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('update', $review))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('delete', $review))->toBeTrue();
});

it('denies photographers from managing reviews', function () {
    $photographer = User::factory()->photographer()->create();
    $review = Review::factory()->create();

    expect(Gate::forUser($photographer)->allows('viewAny', Review::class))->toBeFalse()
        ->and(Gate::forUser($photographer)->allows('view', $review))->toBeFalse()
        ->and(Gate::forUser($photographer)->allows('create', Review::class))->toBeFalse()
        ->and(Gate::forUser($photographer)->allows('update', $review))->toBeFalse()
        ->and(Gate::forUser($photographer)->allows('delete', $review))->toBeFalse();
});

it('denies clients from managing reviews', function () {
    $client = User::factory()->create();
    $review = Review::factory()->create();

    expect(Gate::forUser($client)->allows('viewAny', Review::class))->toBeFalse()
        ->and(Gate::forUser($client)->allows('view', $review))->toBeFalse()
        ->and(Gate::forUser($client)->allows('create', Review::class))->toBeFalse()
        ->and(Gate::forUser($client)->allows('update', $review))->toBeFalse()
        ->and(Gate::forUser($client)->allows('delete', $review))->toBeFalse();
});
