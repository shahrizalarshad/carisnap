<?php

use App\Models\PhotographerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('scopes visible profiles to those with verified_at not null', function () {
    $verifiedProfile = PhotographerProfile::factory()->create([
        'verified_at' => now(),
    ]);

    $unverifiedProfile = PhotographerProfile::factory()->unverified()->create();

    $visibleProfiles = PhotographerProfile::visible()->get();

    expect($visibleProfiles)->toHaveCount(1)
        ->and($visibleProfiles->first()->id)->toBe($verifiedProfile->id);
});
