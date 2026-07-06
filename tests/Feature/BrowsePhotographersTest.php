<?php

use App\Livewire\BrowsePhotographers;
use App\Models\PhotographerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('loads the public photographers browse page', function () {
    $response = $this->get('/photographers');
    $response->assertStatus(200);
    $response->assertSeeLivewire(BrowsePhotographers::class);
});

it('only shows verified profiles', function () {
    $verifiedProfile = PhotographerProfile::factory()->create([
        'verified_at' => now(),
        'business_name' => 'Verified Studio',
    ]);

    $unverifiedProfile = PhotographerProfile::factory()->create([
        'verified_at' => null,
        'business_name' => 'Unverified Studio',
    ]);

    Livewire::test(BrowsePhotographers::class)
        ->assertSee('Verified Studio')
        ->assertDontSee('Unverified Studio');
});

it('filters profiles by location', function () {
    PhotographerProfile::factory()->create([
        'verified_at' => now(),
        'business_name' => 'KL Studio',
        'coverage_areas' => ['Kuala Lumpur'],
    ]);

    PhotographerProfile::factory()->create([
        'verified_at' => now(),
        'business_name' => 'Selangor Studio',
        'coverage_areas' => ['Selangor'],
    ]);

    Livewire::test(BrowsePhotographers::class)
        ->set('location', 'Kuala Lumpur')
        ->assertSee('KL Studio')
        ->assertDontSee('Selangor Studio');
});
