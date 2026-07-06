<?php

use App\Actions\CreatePhotographerProfile;
use App\Actions\CreatePhotographerProfileData;
use App\Enums\ProfileTier;
use App\Enums\SubscriptionPlan;
use App\Enums\SubscriptionStatus;
use App\Enums\UserRole;
use App\Livewire\PhotographerOnboarding;
use App\Models\PhotographerProfile;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('registers a photographer and redirects to onboarding', function () {
    $response = $this->post('/register', [
        'name' => 'Aiman Studio',
        'email' => 'aiman@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'photographer',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('photographer.onboarding'));

    expect(auth()->user())
        ->role->toBe(UserRole::Photographer)
        ->email->toBe('aiman@example.com');
});

it('shows photographer registration page', function () {
    $this->get(route('register.photographer'))
        ->assertOk()
        ->assertSee('Daftar sebagai Jurugambar');
});

it('creates an unverified profile with a free subscription via action', function () {
    $photographer = User::factory()->photographer()->create();

    $profile = app(CreatePhotographerProfile::class)->execute(
        $photographer,
        new CreatePhotographerProfileData(
            businessName: 'Aiman Wedding Studio',
            bio: str_repeat('Pengalaman lebih 10 tahun merakam majlis perkahwinan di seluruh Lembah Klang. ', 2),
            locationArea: 'Selangor',
            coverageAreas: ['Selangor', 'Kuala Lumpur'],
            whatsappNumber: '0123456789',
            instagramHandle: '@aimanstudio',
            phone: '0123456789',
        ),
    );

    expect($profile->verified_at)->toBeNull()
        ->and($profile->tier)->toBe(ProfileTier::Free)
        ->and($profile->slug)->toBe('aiman-wedding-studio')
        ->and($photographer->fresh()->phone)->toBe('0123456789');

    $subscription = Subscription::where('profile_id', $profile->id)->first();

    expect($subscription)->not->toBeNull()
        ->and($subscription->plan)->toBe(SubscriptionPlan::Free)
        ->and($subscription->status)->toBe(SubscriptionStatus::Active);
});

it('generates unique slugs when business names collide', function () {
    $first = User::factory()->photographer()->create();
    $second = User::factory()->photographer()->create();

    $action = app(CreatePhotographerProfile::class);
    $bio = str_repeat('Bio studio jurugambar perkahwinan yang panjang untuk validation. ', 2);

    $action->execute($first, new CreatePhotographerProfileData(
        businessName: 'Studio Cahaya',
        bio: $bio,
        locationArea: 'Kuala Lumpur',
        coverageAreas: ['Kuala Lumpur'],
        whatsappNumber: '0111111111',
    ));

    $secondProfile = $action->execute($second, new CreatePhotographerProfileData(
        businessName: 'Studio Cahaya',
        bio: $bio,
        locationArea: 'Kuala Lumpur',
        coverageAreas: ['Kuala Lumpur'],
        whatsappNumber: '0222222222',
    ));

    expect($secondProfile->slug)->toBe('studio-cahaya-1');
});

it('submits onboarding form and redirects to photographer panel', function () {
    $photographer = User::factory()->photographer()->create();

    Livewire::actingAs($photographer)
        ->test(PhotographerOnboarding::class)
        ->set('business_name', 'Lightbox Studio')
        ->set('bio', str_repeat('Kami pakar fotografi perkahwinan candid dan editorial di Klang Valley. ', 2))
        ->set('location_area', 'Kuala Lumpur')
        ->set('coverage_areas', ['Kuala Lumpur', 'Selangor'])
        ->set('whatsapp_number', '0133333333')
        ->set('phone', '0133333333')
        ->call('submit')
        ->assertRedirect('/photographer');

    $profile = PhotographerProfile::where('user_id', $photographer->id)->first();

    expect($profile)->not->toBeNull()
        ->and($profile->business_name)->toBe('Lightbox Studio')
        ->and($profile->verified_at)->toBeNull();
});

it('redirects photographers without a profile from panel to onboarding', function () {
    $photographer = User::factory()->photographer()->create();

    $this->actingAs($photographer)
        ->get('/photographer')
        ->assertRedirect(route('photographer.onboarding'));
});

it('allows photographers with a profile to access the panel', function () {
    $photographer = User::factory()->photographer()->create();
    PhotographerProfile::factory()->create(['user_id' => $photographer->id]);

    $this->actingAs($photographer)
        ->get('/photographer')
        ->assertOk();
});

it('denies clients access to photographer onboarding', function () {
    $client = User::factory()->create();

    $this->actingAs($client)
        ->get(route('photographer.onboarding'))
        ->assertForbidden();
});

it('redirects dashboard to onboarding for photographers without profile', function () {
    $photographer = User::factory()->photographer()->create();

    $this->actingAs($photographer)
        ->get(route('dashboard'))
        ->assertRedirect(route('photographer.onboarding'));
});

it('prevents photographers from creating a second profile', function () {
    $photographer = User::factory()->photographer()->create();
    PhotographerProfile::factory()->create(['user_id' => $photographer->id]);

    $action = app(CreatePhotographerProfile::class);

    expect(fn () => $action->execute(
        $photographer,
        new CreatePhotographerProfileData(
            businessName: 'Duplicate Studio',
            bio: str_repeat('Bio studio jurugambar perkahwinan yang panjang untuk validation. ', 2),
            locationArea: 'Kuala Lumpur',
            coverageAreas: ['Kuala Lumpur'],
            whatsappNumber: '0144444444',
        ),
    ))->toThrow(AuthorizationException::class);
});
