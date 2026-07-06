<?php

use App\Models\BookingRequest;
use App\Models\User;
use Database\Seeders\DemoAccountsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('seeds demo client account with working password', function () {
    Artisan::call('db:seed', ['--class' => DemoAccountsSeeder::class]);

    $client = User::query()->where('email', 'client@example.com')->first();

    expect($client)->not->toBeNull()
        ->and(Hash::check('password', $client->password))->toBeTrue()
        ->and($client->bookingRequests()->count())->toBe(2);
});

it('can re-run demo accounts seeder without duplicating bookings', function () {
    Artisan::call('db:seed', ['--class' => DemoAccountsSeeder::class]);
    Artisan::call('db:seed', ['--class' => DemoAccountsSeeder::class]);

    $client = User::query()->where('email', 'client@example.com')->first();

    expect($client->bookingRequests()->count())->toBe(2)
        ->and(BookingRequest::query()->count())->toBe(2);
});
