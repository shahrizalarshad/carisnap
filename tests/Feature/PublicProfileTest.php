<?php

use App\Enums\UserRole;
use App\Livewire\ShowPhotographerProfile;
use App\Models\PhotographerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('loads correctly for a verified profile', function () {
    $user = User::factory()->create(['role' => UserRole::Photographer]);
    $profile = PhotographerProfile::factory()->create([
        'user_id' => $user->id,
        'verified_at' => now(),
    ]);

    Livewire::test(ShowPhotographerProfile::class, ['slug' => $profile->slug])
        ->assertStatus(200)
        ->assertSee($profile->business_name);
});

it('fails (404) for an unverified profile', function () {
    $user = User::factory()->create(['role' => UserRole::Photographer]);
    $profile = PhotographerProfile::factory()->create([
        'user_id' => $user->id,
        'verified_at' => null,
    ]);

    $this->get('/'.$profile->slug)
        ->assertStatus(404);
});
