<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('renders login page with bahasa malaysia copy and register links', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('Log Masuk', false)
        ->assertSee('E-mel', false)
        ->assertSee('Kata Laluan', false)
        ->assertSee('Lupa kata laluan?', false)
        ->assertSee('Daftar sekarang', false)
        ->assertSee('Daftar sebagai Pro', false)
        ->assertDontSee('Forgot your password?', false);
});

it('renders verify email page in bahasa malaysia', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->get('/verify-email')
        ->assertOk()
        ->assertSee('Hantar Semula E-mel Pengesahan', false)
        ->assertSee('Log Keluar', false);
});

it('renders forgot password page in bahasa malaysia', function () {
    $this->get(route('password.request'))
        ->assertOk()
        ->assertSee('Lupa Kata Laluan', false)
        ->assertSee('Hantar Pautan Set Semula', false);
});

it('renders profile settings page in bahasa malaysia', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertSee('Maklumat Profil', false)
        ->assertSee('Tukar Kata Laluan', false)
        ->assertSee('Padam Akaun', false);
});
