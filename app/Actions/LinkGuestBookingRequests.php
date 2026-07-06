<?php

namespace App\Actions;

use App\Enums\UserRole;
use App\Models\BookingRequest;
use App\Models\User;
use App\Support\PhoneNumber;

class LinkGuestBookingRequests
{
    public function execute(User $user): int
    {
        if ($user->role !== UserRole::Client || blank($user->phone)) {
            return 0;
        }

        $linked = 0;

        BookingRequest::query()
            ->whereNull('client_id')
            ->whereNotNull('guest_phone')
            ->lazy()
            ->each(function (BookingRequest $booking) use ($user, &$linked): void {
                if (! PhoneNumber::matches($booking->guest_phone, $user->phone)) {
                    return;
                }

                $booking->update([
                    'client_id' => $user->id,
                    'guest_name' => null,
                    'guest_phone' => null,
                    'guest_email' => null,
                ]);

                $linked++;
            });

        return $linked;
    }
}
