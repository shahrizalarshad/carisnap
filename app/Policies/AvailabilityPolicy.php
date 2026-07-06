<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Availability;
use App\Models\User;

class AvailabilityPolicy
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

    public function view(User $user, Availability $availability): bool
    {
        return $user->profile?->id === $availability->profile_id;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Photographer;
    }

    public function update(User $user, Availability $availability): bool
    {
        return $user->profile?->id === $availability->profile_id;
    }

    public function delete(User $user, Availability $availability): bool
    {
        return $user->profile?->id === $availability->profile_id;
    }
}
