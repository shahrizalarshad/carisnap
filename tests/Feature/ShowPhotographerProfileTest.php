<?php

use App\Models\PhotographerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('shows a verified profile', function () {
    $profile = PhotographerProfile::factory()->create([
        'slug' => 'test-studio',
        'verified_at' => now(),
        'business_name' => 'Test Studio',
        'bio' => 'This is a test bio.',
    ]);

    $response = $this->get('/test-studio');

    $response->assertStatus(200);
    $response->assertSee('Test Studio');
    $response->assertSee('This is a test bio.');
});

it('returns 404 for unverified profiles', function () {
    $profile = PhotographerProfile::factory()->create([
        'slug' => 'hidden-studio',
        'verified_at' => null,
    ]);

    $response = $this->get('/hidden-studio');

    $response->assertStatus(404);
});
