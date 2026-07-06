<?php

namespace App\Observers;

use App\Actions\LinkGuestBookingRequests;
use App\Enums\UserRole;
use App\Models\User;
use App\Support\PhoneNumber;

class UserObserver
{
    public function saving(User $user): void
    {
        if ($user->phone) {
            $user->phone = PhoneNumber::normalize($user->phone);
        }
    }

    public function saved(User $user): void
    {
        if ($user->role !== UserRole::Client || ! $user->wasChanged('phone') || blank($user->phone)) {
            return;
        }

        app(LinkGuestBookingRequests::class)->execute($user);
    }
}
