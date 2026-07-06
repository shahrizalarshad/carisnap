<?php

use App\Enums\BookingStatus;
use App\Livewire\SubmitReview;
use App\Models\BookingRequest;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('requires a valid signature to submit a review', function () {
    $booking = BookingRequest::factory()->create([
        'status' => BookingStatus::Accepted,
    ]);

    $response = $this->get(route('reviews.create', $booking));
    $response->assertStatus(403);
});

it('blocks reviews for non-accepted bookings', function () {
    $booking = BookingRequest::factory()->create([
        'status' => BookingStatus::Quoted,
    ]);

    $signedUrl = URL::signedRoute('reviews.create', $booking);

    $response = $this->get($signedUrl);
    $response->assertStatus(403);
});

it('allows submitting a review for an accepted booking', function () {
    $booking = BookingRequest::factory()->create([
        'status' => BookingStatus::Accepted,
    ]);

    $signedUrl = URL::signedRoute('reviews.create', $booking);

    $this->get($signedUrl)->assertOk();

    Livewire::test(SubmitReview::class, ['bookingRequest' => $booking])
        ->set('rating', 5)
        ->set('comment', 'Amazing service!')
        ->call('submit');

    $this->assertDatabaseHas('reviews', [
        'booking_request_id' => $booking->id,
        'rating' => 5,
        'comment' => 'Amazing service!',
        'published_at' => null, // Pending moderation
    ]);
});

it('prevents submitting duplicate reviews for the same booking', function () {
    $booking = BookingRequest::factory()->create([
        'status' => BookingStatus::Accepted,
    ]);

    Review::factory()->create([
        'booking_request_id' => $booking->id,
    ]);

    $signedUrl = URL::signedRoute('reviews.create', $booking);

    $this->get($signedUrl)
        ->assertOk()
        ->assertSee('telah pun menghantar ulasan');
});

it('does not create a duplicate review when submit is called twice', function () {
    $booking = BookingRequest::factory()->create([
        'status' => BookingStatus::Accepted,
    ]);

    Livewire::test(SubmitReview::class, ['bookingRequest' => $booking])
        ->set('rating', 4)
        ->set('comment', 'Great!')
        ->call('submit')
        ->call('submit');

    $this->assertDatabaseCount('reviews', 1);
});

it('rejects ratings below 1', function () {
    $booking = BookingRequest::factory()->create([
        'status' => BookingStatus::Accepted,
    ]);

    Livewire::test(SubmitReview::class, ['bookingRequest' => $booking])
        ->set('rating', 0)
        ->call('submit')
        ->assertHasErrors(['rating']);

    $this->assertDatabaseCount('reviews', 0);
});

it('rejects ratings above 5', function () {
    $booking = BookingRequest::factory()->create([
        'status' => BookingStatus::Accepted,
    ]);

    Livewire::test(SubmitReview::class, ['bookingRequest' => $booking])
        ->set('rating', 6)
        ->call('submit')
        ->assertHasErrors(['rating']);

    $this->assertDatabaseCount('reviews', 0);
});

it('accepts the minimum rating of 1', function () {
    $booking = BookingRequest::factory()->create([
        'status' => BookingStatus::Accepted,
    ]);

    Livewire::test(SubmitReview::class, ['bookingRequest' => $booking])
        ->set('rating', 1)
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('reviews', [
        'booking_request_id' => $booking->id,
        'rating' => 1,
    ]);
});

it('rejects comments longer than 1000 characters', function () {
    $booking = BookingRequest::factory()->create([
        'status' => BookingStatus::Accepted,
    ]);

    Livewire::test(SubmitReview::class, ['bookingRequest' => $booking])
        ->set('rating', 5)
        ->set('comment', str_repeat('a', 1001))
        ->call('submit')
        ->assertHasErrors(['comment']);

    $this->assertDatabaseCount('reviews', 0);
});

it('blocks reviews for non-accepted booking statuses', function (BookingStatus $status) {
    $booking = BookingRequest::factory()->create(['status' => $status]);

    $signedUrl = URL::signedRoute('reviews.create', $booking);

    $this->get($signedUrl)->assertStatus(403);
})->with([
    'pending' => BookingStatus::Pending,
    'declined' => BookingStatus::Declined,
    'expired' => BookingStatus::Expired,
]);
