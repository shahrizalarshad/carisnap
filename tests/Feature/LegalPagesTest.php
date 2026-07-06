<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('shows the privacy policy page', function () {
    $this->get(route('privacy'))
        ->assertOk()
        ->assertSee('Dasar Privasi', false)
        ->assertSee('Data yang Kami Kumpul', false);
});

it('shows the terms of service page', function () {
    $this->get(route('terms'))
        ->assertOk()
        ->assertSee('Terma Penggunaan', false)
        ->assertSee('Peranan CariSnap', false);
});

it('shows legal footer links on the homepage', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Dasar Privasi', false)
        ->assertSee('Terma Penggunaan', false)
        ->assertSee('hello@carisnap.my', false);
});

it('does not treat legal routes as photographer profile slugs', function () {
    $this->get('/dasar-privasi')->assertOk()->assertSee('Dasar Privasi', false);
    $this->get('/terma-penggunaan')->assertOk()->assertSee('Terma Penggunaan', false);
});
