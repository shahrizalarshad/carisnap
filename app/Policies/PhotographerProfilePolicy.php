<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\PhotographerProfile;
use App\Models\User;

class PhotographerProfilePolicy
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

    public function view(User $user, PhotographerProfile $photographerProfile): bool
    {
        return $user->id === $photographerProfile->user_id;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Photographer && ! $user->profile()->exists();
    }

    public function update(User $user, PhotographerProfile $photographerProfile): bool
    {
        return $user->id === $photographerProfile->user_id;
    }

    public function delete(User $user, PhotographerProfile $photographerProfile): bool
    {
        return $user->id === $photographerProfile->user_id;
    }

    public function restore(User $user, PhotographerProfile $photographerProfile): bool
    {
        return false;
    }

    public function forceDelete(User $user, PhotographerProfile $photographerProfile): bool
    {
        return false;
    }
}
