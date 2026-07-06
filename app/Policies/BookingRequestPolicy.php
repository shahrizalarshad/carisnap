<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\BookingRequest;
use App\Models\User;

class BookingRequestPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Photographer;
    }

    public function view(User $user, BookingRequest $bookingRequest): bool
    {
        return $user->profile?->id === $bookingRequest->profile_id || $user->id === $bookingRequest->client_id;
    }

    public function create(User $user): bool
    {
        return false;
    } // Handled via public frontend

    public function update(User $user, BookingRequest $bookingRequest): bool
    {
        return $user->profile?->id === $bookingRequest->profile_id;
    }

    public function delete(User $user, BookingRequest $bookingRequest): bool
    {
        return false;
    }
}
