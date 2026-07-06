<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('renders the homepage with hero copy and ctas', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Cari jurugambar perkahwinan yang ngam dengan bajet', false)
        ->assertSee('Mula Cari', false)
        ->assertSee('Saya Jurugambar', false)
        ->assertSee('CariSnap — Cari Jurugambar Perkahwinan Klang Valley', false);
});

it('includes seo meta tags on the homepage', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('og:site_name', false)
        ->assertSee('Lembah Klang', false);
});
