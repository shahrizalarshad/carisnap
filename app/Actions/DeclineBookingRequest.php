<?php

namespace App\Actions;

use App\Enums\BookingStatus;
use App\Models\BookingRequest;
use App\Notifications\BookingDeclinedNotification;
use Illuminate\Support\Facades\Notification;

class DeclineBookingRequest
{
    public function execute(BookingRequest $bookingRequest): BookingRequest
    {
        $bookingRequest->update([
            'status' => BookingStatus::Declined,
            'responded_at' => now(),
        ]);

        $email = $bookingRequest->client_id
            ? $bookingRequest->client->email
            : $bookingRequest->guest_email;

        if ($email) {
            Notification::route('mail', $email)
                ->notify(new BookingDeclinedNotification($bookingRequest));
        }

        return $bookingRequest;
    }
}
