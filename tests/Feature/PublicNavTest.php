<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('shows guest nav links on the homepage', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Log Masuk', false)
        ->assertSee('Daftar sebagai Pro', false)
        ->assertDontSee('Tempahan Saya', false);
});

it('shows bookings nav for verified clients only', function () {
    $verifiedClient = User::factory()->create(['role' => UserRole::Client]);
    $unverifiedClient = User::factory()->unverified()->create(['role' => UserRole::Client]);

    $this->actingAs($verifiedClient)
        ->get(route('home'))
        ->assertSee('Tempahan Saya', false)
        ->assertDontSee('Log Masuk', false);

    $this->actingAs($unverifiedClient)
        ->get(route('home'))
        ->assertDontSee('Tempahan Saya', false);
});

it('shows panel pro nav for photographers', function () {
    $photographer = User::factory()->create(['role' => UserRole::Photographer]);

    $this->actingAs($photographer)
        ->get(route('home'))
        ->assertSee('Panel Pro', false)
        ->assertDontSee('Tempahan Saya', false);
});

it('shows admin nav for admins', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->actingAs($admin)
        ->get(route('home'))
        ->assertSee('Admin', false)
        ->assertDontSee('Tempahan Saya', false);
});

it('shows profile and logout options for authenticated users', function () {
    $client = User::factory()->create(['role' => UserRole::Client]);

    $this->actingAs($client)
        ->get(route('bookings.index'))
        ->assertOk()
        ->assertSee('Log Keluar', false)
        ->assertSee('Profil', false)
        ->assertDontSee('Log Masuk', false);
});
