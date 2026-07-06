<?php

use App\Enums\BookingStatus;
use App\Enums\QuoteStatus;
use App\Models\Quote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('expires past quotes and updates booking request status', function () {
    // Valid quote
    $validQuote = Quote::factory()->create([
        'status' => QuoteStatus::Sent,
        'valid_until' => now()->addDays(2),
    ]);
    $validQuote->bookingRequest->update(['status' => BookingStatus::Quoted]);

    // Expired quote
    $expiredQuote = Quote::factory()->create([
        'status' => QuoteStatus::Sent,
        'valid_until' => now()->subDays(1),
    ]);
    $expiredQuote->bookingRequest->update(['status' => BookingStatus::Quoted]);

    $this->artisan('quotes:expire')
        ->expectsOutputToContain('Expired 1 quotes.')
        ->assertExitCode(0);

    // Assert valid quote unchanged
    $validQuote->refresh();
    expect($validQuote->status)->toBe(QuoteStatus::Sent)
        ->and($validQuote->bookingRequest->status)->toBe(BookingStatus::Quoted);

    // Assert expired quote changed
    $expiredQuote->refresh();
    expect($expiredQuote->status)->toBe(QuoteStatus::Expired)
        ->and($expiredQuote->bookingRequest->status)->toBe(BookingStatus::Expired);
});
