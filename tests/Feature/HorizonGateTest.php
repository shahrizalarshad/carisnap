<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('allows admin users to view horizon in non-local environments', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $photographer = User::factory()->create(['role' => UserRole::Photographer]);

    expect(Gate::forUser($admin)->allows('viewHorizon'))->toBeTrue()
        ->and(Gate::forUser($photographer)->allows('viewHorizon'))->toBeFalse()
        ->and(Gate::allows('viewHorizon'))->toBeFalse();
});
