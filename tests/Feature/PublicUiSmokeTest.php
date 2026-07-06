<?php

use App\Enums\AvailabilityStatus;
use App\Enums\BookingStatus;
use App\Enums\QuoteStatus;
use App\Models\Availability;
use App\Models\BookingRequest;
use App\Models\Package;
use App\Models\PhotographerProfile;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('shows accept and decline buttons on the last valid day of a quote', function () {
    $quote = Quote::factory()->create([
        'status' => QuoteStatus::Sent,
        'valid_until' => now()->toDateString(),
    ]);

    $this->get(URL::signedRoute('quotes.show', $quote))
        ->assertOk()
        ->assertSee('Terima Sebut Harga', false)
        ->assertSee('Tolak Sebut Harga', false)
        ->assertDontSee('telah tamat tempoh', false);
});

it('hides action buttons for expired quotes in the ui', function () {
    $quote = Quote::factory()->create([
        'status' => QuoteStatus::Sent,
        'valid_until' => now()->subDay()->toDateString(),
    ]);

    $this->get(URL::signedRoute('quotes.show', $quote))
        ->assertOk()
        ->assertDontSee('Terima Sebut Harga', false)
        ->assertSee('telah tamat tempoh', false);
});

it('does not use wire navigate on signed quote links from booking details', function () {
    $client = User::factory()->create();
    $booking = BookingRequest::factory()->create([
        'client_id' => $client->id,
        'status' => BookingStatus::Quoted,
    ]);
    $quote = Quote::factory()->create([
        'booking_request_id' => $booking->id,
    ]);

    $response = $this->actingAs($client)->get(route('bookings.show', $booking));

    $response->assertOk();

    $signedUrl = URL::signedRoute('quotes.show', $quote);
    $html = $response->getContent();

    expect($html)->toContain($signedUrl);
    preg_match('/<a[^>]*href="'.preg_quote($signedUrl, '/').'"[^>]*>/', $html, $matches);
    expect($matches[0] ?? '')->not->toContain('wire:navigate');
});

it('renders instagram handle and gallery scripts on photographer profiles', function () {
    $profile = PhotographerProfile::factory()->create([
        'slug' => 'studio-cahaya',
        'verified_at' => now(),
        'business_name' => 'Studio Cahaya',
        'instagram_handle' => '@cahayastudio',
    ]);

    $this->get(route('photographers.show', $profile->slug))
        ->assertOk()
        ->assertSee('@cahayastudio', false)
        ->assertSee('swiper-bundle.min.js', false)
        ->assertSee('alpine:init', false)
        ->assertSee('application/ld+json', false);
});

it('renders malay month labels for availability on profile page', function () {
    $profile = PhotographerProfile::factory()->create([
        'slug' => 'avail-studio',
        'verified_at' => now(),
    ]);

    Availability::factory()->create([
        'profile_id' => $profile->id,
        'date' => now()->addMonth()->startOfMonth()->addDays(4),
        'status' => AvailabilityStatus::Available,
    ]);

    $malayMonth = now()->addMonth()->locale('ms')->translatedFormat('F');

    $this->get(route('photographers.show', $profile->slug))
        ->assertOk()
        ->assertSee($malayMonth, false)
        ->assertSee('3 Bulan Akan Datang', false);
});

it('shows browse page with bm filter labels, chips, and loading skeleton', function () {
    PhotographerProfile::factory()->create(['verified_at' => now()]);

    $this->get(route('photographers.index'))
        ->assertOk()
        ->assertSee('wire:loading', false)
        ->assertSee('location,budget,date', false)
        ->assertSee('Kawasan', false)
        ->assertSee('1 jurugambar dijumpai', false)
        ->assertDontSee('(Location)', false)
        ->assertDontSee('(Budget)', false);
});

it('shows active filter chips when browse filters are applied', function () {
    PhotographerProfile::factory()->create([
        'verified_at' => now(),
        'coverage_areas' => ['Kuala Lumpur'],
    ]);

    $this->get(route('photographers.index', ['loc' => 'Kuala Lumpur']))
        ->assertOk()
        ->assertSee('Filter aktif:', false)
        ->assertSee('Kuala Lumpur', false)
        ->assertSee('clearFilter', false);
});

it('shows hantar permintaan cta with price on photographer profile', function () {
    $profile = PhotographerProfile::factory()->create([
        'slug' => 'studio-harga',
        'verified_at' => now(),
        'business_name' => 'Studio Harga',
    ]);

    Package::factory()->create([
        'profile_id' => $profile->id,
        'price_from' => 2500,
        'is_active' => true,
    ]);

    $this->get(route('photographers.show', $profile->slug))
        ->assertOk()
        ->assertSee('Hantar Permintaan', false)
        ->assertSee('RM2,500', false)
        ->assertDontSee('Hantar Booking Request', false);
});
