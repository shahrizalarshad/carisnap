<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Package;
use App\Models\User;

class PackagePolicy
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

    public function view(User $user, Package $package): bool
    {
        return $user->profile?->id === $package->profile_id;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Photographer;
    }

    public function update(User $user, Package $package): bool
    {
        return $user->profile?->id === $package->profile_id;
    }

    public function delete(User $user, Package $package): bool
    {
        return $user->profile?->id === $package->profile_id;
    }
}
