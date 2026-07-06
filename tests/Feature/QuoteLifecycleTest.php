<?php

use App\Enums\BookingStatus;
use App\Enums\QuoteStatus;
use App\Livewire\ReviewQuote;
use App\Models\Quote;
use App\Notifications\QuoteAcceptedNotification;
use App\Notifications\QuoteDeclinedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('requires a valid signature to review a quote', function () {
    $quote = Quote::factory()->create();

    $response = $this->get(route('quotes.show', $quote));
    $response->assertStatus(403);
});

it('allows guests to accept a quote and updates status', function () {
    $quote = Quote::factory()->create([
        'status' => QuoteStatus::Sent,
    ]);

    $quote->bookingRequest->update(['status' => BookingStatus::Quoted]);

    $signedUrl = URL::signedRoute('quotes.show', $quote);

    // Visit the signed route
    $this->get($signedUrl)->assertOk();

    // Call the Livewire action to accept
    Livewire::test(ReviewQuote::class, ['quote' => $quote])
        ->call('accept');

    $quote->refresh();
    expect($quote->status)->toBe(QuoteStatus::Accepted)
        ->and($quote->bookingRequest->status)->toBe(BookingStatus::Accepted);
});

it('allows guests to decline a quote and updates status', function () {
    $quote = Quote::factory()->create([
        'status' => QuoteStatus::Sent,
    ]);

    $quote->bookingRequest->update(['status' => BookingStatus::Quoted]);

    Livewire::test(ReviewQuote::class, ['quote' => $quote])
        ->call('decline');

    $quote->refresh();
    expect($quote->status)->toBe(QuoteStatus::Declined)
        ->and($quote->bookingRequest->status)->toBe(BookingStatus::Declined);
});

it('does not allow accepting a quote that is no longer sent', function (QuoteStatus $status) {
    $quote = Quote::factory()->create(['status' => $status]);
    $quote->bookingRequest->update(['status' => BookingStatus::Quoted]);

    Livewire::test(ReviewQuote::class, ['quote' => $quote])
        ->call('accept');

    $quote->refresh();
    expect($quote->status)->toBe($status);
})->with([
    'accepted' => QuoteStatus::Accepted,
    'declined' => QuoteStatus::Declined,
    'expired' => QuoteStatus::Expired,
]);

it('does not allow declining a quote that is no longer sent', function (QuoteStatus $status) {
    $quote = Quote::factory()->create(['status' => $status]);
    $quote->bookingRequest->update(['status' => BookingStatus::Quoted]);

    Livewire::test(ReviewQuote::class, ['quote' => $quote])
        ->call('decline');

    $quote->refresh();
    expect($quote->status)->toBe($status);
})->with([
    'accepted' => QuoteStatus::Accepted,
    'declined' => QuoteStatus::Declined,
    'expired' => QuoteStatus::Expired,
]);

it('does not allow accepting a quote past its valid_until date', function () {
    $quote = Quote::factory()->create([
        'status' => QuoteStatus::Sent,
        'valid_until' => now()->subDay()->toDateString(),
    ]);
    $quote->bookingRequest->update(['status' => BookingStatus::Quoted]);

    Livewire::test(ReviewQuote::class, ['quote' => $quote])
        ->call('accept');

    $quote->refresh();
    expect($quote->status)->toBe(QuoteStatus::Sent)
        ->and($quote->bookingRequest->status)->toBe(BookingStatus::Quoted);
});

it('allows accepting a quote on its valid_until date', function () {
    $quote = Quote::factory()->create([
        'status' => QuoteStatus::Sent,
        'valid_until' => now()->toDateString(),
    ]);
    $quote->bookingRequest->update(['status' => BookingStatus::Quoted]);

    Livewire::test(ReviewQuote::class, ['quote' => $quote])
        ->call('accept');

    $quote->refresh();
    expect($quote->status)->toBe(QuoteStatus::Accepted);
});

it('shows expired message for quotes past valid_until date', function () {
    $quote = Quote::factory()->create([
        'status' => QuoteStatus::Sent,
        'valid_until' => now()->subDay()->toDateString(),
    ]);

    $signedUrl = URL::signedRoute('quotes.show', $quote);

    $this->get($signedUrl)
        ->assertOk()
        ->assertSee('telah tamat tempoh');
});

it('sends notification to photographer when quote is accepted', function () {
    Notification::fake();

    $quote = Quote::factory()->create(['status' => QuoteStatus::Sent]);
    $quote->bookingRequest->update(['status' => BookingStatus::Quoted]);
    $photographer = $quote->bookingRequest->profile->user;

    Livewire::test(ReviewQuote::class, ['quote' => $quote])
        ->call('accept');

    Notification::assertSentTo($photographer, QuoteAcceptedNotification::class);
});

it('sends notification to photographer when quote is declined', function () {
    Notification::fake();

    $quote = Quote::factory()->create(['status' => QuoteStatus::Sent]);
    $quote->bookingRequest->update(['status' => BookingStatus::Quoted]);
    $photographer = $quote->bookingRequest->profile->user;

    Livewire::test(ReviewQuote::class, ['quote' => $quote])
        ->call('decline');

    Notification::assertSentTo($photographer, QuoteDeclinedNotification::class);
});

it('sets responded_at on booking request when quote is accepted', function () {
    $quote = Quote::factory()->create(['status' => QuoteStatus::Sent]);
    $quote->bookingRequest->update(['status' => BookingStatus::Quoted, 'responded_at' => null]);

    Livewire::test(ReviewQuote::class, ['quote' => $quote])
        ->call('accept');

    expect($quote->bookingRequest->fresh()->responded_at)->not->toBeNull();
});
