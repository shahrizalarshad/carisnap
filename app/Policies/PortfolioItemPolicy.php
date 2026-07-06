<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\PortfolioItem;
use App\Models\User;

class PortfolioItemPolicy
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

    public function view(User $user, PortfolioItem $portfolioItem): bool
    {
        return $user->profile?->id === $portfolioItem->profile_id;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Photographer;
    }

    public function update(User $user, PortfolioItem $portfolioItem): bool
    {
        return $user->profile?->id === $portfolioItem->profile_id;
    }

    public function delete(User $user, PortfolioItem $portfolioItem): bool
    {
        return $user->profile?->id === $portfolioItem->profile_id;
    }
}
