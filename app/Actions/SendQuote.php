<?php

namespace App\Actions;

use App\Enums\BookingStatus;
use App\Enums\QuoteStatus;
use App\Models\BookingRequest;
use App\Models\Quote;
use App\Notifications\QuoteReceivedNotification;
use Illuminate\Support\Facades\Notification;

class SendQuote
{
    public function execute(BookingRequest $bookingRequest, SendQuoteData $data): Quote
    {
        $quote = $bookingRequest->quotes()->create([
            'amount' => $data->amount,
            'message' => $data->message,
            'status' => QuoteStatus::Sent,
            'valid_until' => now()->addDays($data->validForDays)->toDateString(),
        ]);

        $bookingRequest->update([
            'status' => BookingStatus::Quoted,
            'responded_at' => now(),
        ]);

        $email = $bookingRequest->client_id
            ? $bookingRequest->client->email
            : $bookingRequest->guest_email;

        if ($email) {
            Notification::route('mail', $email)
                ->notify(new QuoteReceivedNotification($quote));
        }

        return $quote;
    }
}
