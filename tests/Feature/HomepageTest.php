<?php

use App\Models\PhotographerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('renders the homepage with hero copy and ctas', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Cari jurugambar perkahwinan yang ngam dengan bajet', false)
        ->assertSee('Mula Cari', false)
        ->assertSee('Saya Jurugambar', false)
        ->assertSee('Kenapa CariSnap?', false)
        ->assertSee('Popular sekarang:', false)
        ->assertDontSee('Ready untuk cari jurugambar?', false)
        ->assertSee('CariSnap — Cari Jurugambar Perkahwinan Klang Valley', false)
        ->assertSee('viewBox="0 0 32 32"', false);
});

it('includes favicon links in the document head', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('rel="icon"', false)
        ->assertSee('favicon.svg', false)
        ->assertSee('apple-touch-icon.svg', false);
});

it('includes seo meta tags on the homepage', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('og:site_name', false)
        ->assertSee('Lembah Klang', false);
});

it('shows featured photographers section when verified profiles exist', function () {
    PhotographerProfile::factory()->create([
        'verified_at' => now(),
        'business_name' => 'Studio Featured',
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Studio Pilihan', false)
        ->assertSee('Studio Featured', false);
});
