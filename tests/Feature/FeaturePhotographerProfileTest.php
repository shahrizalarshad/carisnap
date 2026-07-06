<?php

use App\Actions\FeaturePhotographerProfile;
use App\Actions\FeaturePhotographerProfileData;
use App\Actions\UnfeaturePhotographerProfile;
use App\Models\PhotographerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('features a verified profile for the chosen duration', function () {
    $profile = PhotographerProfile::factory()->create(['verified_at' => now()]);

    app(FeaturePhotographerProfile::class)->execute(
        $profile,
        new FeaturePhotographerProfileData(durationDays: 30),
    );

    $profile->refresh();

    expect($profile->isFeatured())->toBeTrue()
        ->and($profile->featured_until->isFuture())->toBeTrue()
        ->and($profile->featured_until->greaterThan(now()->addDays(29)))->toBeTrue();
});

it('rejects featuring an unverified profile', function () {
    $profile = PhotographerProfile::factory()->unverified()->create();

    app(FeaturePhotographerProfile::class)->execute(
        $profile,
        new FeaturePhotographerProfileData(durationDays: 7),
    );
})->throws(ValidationException::class);

it('clears featured status', function () {
    $profile = PhotographerProfile::factory()->create([
        'verified_at' => now(),
        'featured_until' => now()->addDays(30),
    ]);

    app(UnfeaturePhotographerProfile::class)->execute($profile);

    expect($profile->fresh()->featured_until)->toBeNull()
        ->and($profile->fresh()->isFeatured())->toBeFalse();
});

it('prioritises featured profiles on the homepage query', function () {
    $regular = PhotographerProfile::factory()->create([
        'verified_at' => now(),
        'featured_until' => null,
        'business_name' => 'Studio Biasa',
    ]);

    $featured = PhotographerProfile::factory()->create([
        'verified_at' => now(),
        'featured_until' => now()->addDays(30),
        'business_name' => 'Studio Pilihan',
    ]);

    $results = PhotographerProfile::query()
        ->visible()
        ->orderByRaw('CASE WHEN featured_until IS NOT NULL AND featured_until > NOW() THEN 0 ELSE 1 END')
        ->orderBy('business_name')
        ->pluck('business_name');

    expect($results->first())->toBe('Studio Pilihan')
        ->and($results->last())->toBe('Studio Biasa');
});
