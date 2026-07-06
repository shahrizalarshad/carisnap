<?php

use App\Actions\ApprovePhotographerProfile;
use App\Actions\RejectPhotographerProfile;
use App\Models\PhotographerProfile;
use App\Models\Quote;
use App\Notifications\ProfileApprovedNotification;
use App\Notifications\ProfileRejectedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('approves an unverified profile and notifies the photographer', function () {
    Notification::fake();

    $profile = PhotographerProfile::factory()->unverified()->create();

    app(ApprovePhotographerProfile::class)->execute($profile);

    expect($profile->fresh()->verified_at)->not->toBeNull();

    Notification::assertSentTo($profile->user, ProfileApprovedNotification::class);
});

it('rejects a profile and notifies the photographer with optional reason', function () {
    Notification::fake();

    $profile = PhotographerProfile::factory()->create(['verified_at' => now()]);

    app(RejectPhotographerProfile::class)->execute($profile, 'Portfolio terlalu sedikit.');

    expect($profile->fresh()->verified_at)->toBeNull();

    Notification::assertSentTo(
        $profile->user,
        ProfileRejectedNotification::class,
        fn (ProfileRejectedNotification $notification) => $notification->reason === 'Portfolio terlalu sedikit.',
    );
});

it('serves sitemap xml with verified profiles only', function () {
    $verified = PhotographerProfile::factory()->create([
        'slug' => 'verified-studio',
        'verified_at' => now(),
    ]);

    PhotographerProfile::factory()->unverified()->create([
        'slug' => 'hidden-studio',
    ]);

    $this->get(route('sitemap'))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml')
        ->assertSee(route('photographers.show', $verified->slug), false)
        ->assertDontSee('hidden-studio');
});

it('includes seo meta tags on public photographer profiles', function () {
    $profile = PhotographerProfile::factory()->create([
        'business_name' => 'Studio Cahaya',
        'bio' => str_repeat('Jurugambar perkahwinan profesional di Klang Valley dengan pengalaman lebih 10 tahun. ', 2),
        'verified_at' => now(),
    ]);

    $this->get(route('photographers.show', $profile->slug))
        ->assertOk()
        ->assertSee('Studio Cahaya — Jurugambar Perkahwinan | CariSnap', false)
        ->assertSee('rel="canonical"', false)
        ->assertSee('og:site_name', false)
        ->assertSee('application/ld+json', false);
});

it('marks signed quote pages as noindex', function () {
    $quote = Quote::factory()->create();

    $this->get(URL::signedRoute('quotes.show', $quote))
        ->assertOk()
        ->assertSee('noindex', false);
});
