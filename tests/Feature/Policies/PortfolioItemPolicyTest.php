<?php

use App\Models\PhotographerProfile;
use App\Models\PortfolioItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('allows admin to manage any portfolio item', function () {
    $admin = User::factory()->admin()->create();
    $item = PortfolioItem::factory()->create();

    expect(Gate::forUser($admin)->allows('update', $item))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('delete', $item))->toBeTrue();
});

it('allows photographers to manage only their own portfolio items', function () {
    $photographer = User::factory()->photographer()->create();
    $profile = PhotographerProfile::factory()->create(['user_id' => $photographer->id]);
    $ownItem = PortfolioItem::factory()->create(['profile_id' => $profile->id]);

    $otherItem = PortfolioItem::factory()->create();

    expect(Gate::forUser($photographer)->allows('view', $ownItem))->toBeTrue()
        ->and(Gate::forUser($photographer)->allows('update', $ownItem))->toBeTrue()
        ->and(Gate::forUser($photographer)->allows('delete', $ownItem))->toBeTrue()
        ->and(Gate::forUser($photographer)->allows('view', $otherItem))->toBeFalse()
        ->and(Gate::forUser($photographer)->allows('update', $otherItem))->toBeFalse()
        ->and(Gate::forUser($photographer)->allows('delete', $otherItem))->toBeFalse();
});

it('denies clients access to portfolio management', function () {
    $client = User::factory()->create();
    $item = PortfolioItem::factory()->create();

    expect(Gate::forUser($client)->allows('viewAny', PortfolioItem::class))->toBeFalse()
        ->and(Gate::forUser($client)->allows('view', $item))->toBeFalse()
        ->and(Gate::forUser($client)->allows('create', PortfolioItem::class))->toBeFalse();
});
