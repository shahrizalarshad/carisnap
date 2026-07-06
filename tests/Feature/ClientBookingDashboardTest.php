<?php

use App\Enums\BookingStatus;
use App\Livewire\MyBookingRequests;
use App\Models\BookingRequest;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('redirects unauthenticated users to login from bookings index', function () {
    $this->get(route('bookings.index'))
        ->assertRedirect(route('login'));
});

it('shows only the authenticated client own booking requests', function () {
    $client = User::factory()->create();
    $otherClient = User::factory()->create();

    $ownBooking = BookingRequest::factory()->create([
        'client_id' => $client->id,
        'status' => BookingStatus::Pending,
    ]);

    $otherBooking = BookingRequest::factory()->create([
        'client_id' => $otherClient->id,
        'status' => BookingStatus::Quoted,
    ]);

    $this->actingAs($client)
        ->get(route('bookings.index'))
        ->assertOk()
        ->assertSee('Tempahan Saya')
        ->assertSee($ownBooking->profile->business_name)
        ->assertSee('Menunggu')
        ->assertDontSee($otherBooking->profile->business_name);
});

it('filters booking requests by status', function () {
    $client = User::factory()->create();

    $pendingBooking = BookingRequest::factory()->create([
        'client_id' => $client->id,
        'status' => BookingStatus::Pending,
    ]);

    $quotedBooking = BookingRequest::factory()->create([
        'client_id' => $client->id,
        'status' => BookingStatus::Quoted,
    ]);

    Livewire::actingAs($client)
        ->withQueryParams(['status' => BookingStatus::Quoted->value])
        ->test(MyBookingRequests::class)
        ->assertSee($quotedBooking->profile->business_name)
        ->assertSee('Sebut Harga Dihantar')
        ->assertDontSee($pendingBooking->profile->business_name);
});

it('shows quoted booking with link to respond to quote', function () {
    $client = User::factory()->create();

    $booking = BookingRequest::factory()->create([
        'client_id' => $client->id,
        'status' => BookingStatus::Quoted,
    ]);

    $quote = Quote::factory()->create([
        'booking_request_id' => $booking->id,
        'amount' => 3500,
    ]);

    $this->actingAs($client)
        ->get(route('bookings.index'))
        ->assertOk()
        ->assertSee('RM3,500')
        ->assertSee('Balas Sebut Harga');
});

it('allows a client to view their own booking request details', function () {
    $client = User::factory()->create();

    $booking = BookingRequest::factory()->create([
        'client_id' => $client->id,
        'status' => BookingStatus::Accepted,
        'location' => 'Shah Alam',
    ]);

    $this->actingAs($client)
        ->get(route('bookings.show', $booking))
        ->assertOk()
        ->assertSee('Butiran Tempahan')
        ->assertSee('Shah Alam')
        ->assertSee('Diterima')
        ->assertSee('WhatsApp Jurugambar');
});

it('denies a client from viewing another clients booking request', function () {
    $client = User::factory()->create();
    $otherClient = User::factory()->create();

    $booking = BookingRequest::factory()->create([
        'client_id' => $otherClient->id,
    ]);

    $this->actingAs($client)
        ->get(route('bookings.show', $booking))
        ->assertForbidden();
});

it('redirects clients from dashboard to bookings index', function () {
    $client = User::factory()->create();

    $this->actingAs($client)
        ->get(route('dashboard'))
        ->assertRedirect(route('bookings.index'));
});

it('redirects photographers from dashboard to filament panel', function () {
    $photographer = User::factory()->photographer()->create();

    $this->actingAs($photographer)
        ->get(route('dashboard'))
        ->assertRedirect('/photographer');
});
