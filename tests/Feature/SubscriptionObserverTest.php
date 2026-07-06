<?php

use App\Enums\ProfileTier;
use App\Enums\SubscriptionPlan;
use App\Enums\SubscriptionStatus;
use App\Models\PhotographerProfile;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('syncs profile tier to pro when active pro subscription is created', function () {
    $profile = PhotographerProfile::factory()->create([
        'tier' => ProfileTier::Free,
    ]);

    Subscription::factory()->create([
        'profile_id' => $profile->id,
        'plan' => SubscriptionPlan::Pro,
        'status' => SubscriptionStatus::Active,
        'ends_at' => now()->addMonth(),
    ]);

    expect($profile->fresh()->tier)->toBe(ProfileTier::Pro);
});

it('reverts profile tier to free when pro subscription expires', function () {
    $profile = PhotographerProfile::factory()->create([
        'tier' => ProfileTier::Free,
    ]);

    $subscription = Subscription::factory()->create([
        'profile_id' => $profile->id,
        'plan' => SubscriptionPlan::Pro,
        'status' => SubscriptionStatus::Active,
        'ends_at' => now()->addMonth(),
    ]);

    expect($profile->fresh()->tier)->toBe(ProfileTier::Pro);

    $subscription->update([
        'ends_at' => now()->subDay(),
    ]);

    expect($profile->fresh()->tier)->toBe(ProfileTier::Free);
});

it('reverts profile tier to free when pro subscription is cancelled', function () {
    $profile = PhotographerProfile::factory()->create([
        'tier' => ProfileTier::Free,
    ]);

    $subscription = Subscription::factory()->create([
        'profile_id' => $profile->id,
        'plan' => SubscriptionPlan::Pro,
        'status' => SubscriptionStatus::Active,
        'ends_at' => now()->addMonth(),
    ]);

    $subscription->update([
        'status' => SubscriptionStatus::Cancelled,
    ]);

    expect($profile->fresh()->tier)->toBe(ProfileTier::Free);
});
