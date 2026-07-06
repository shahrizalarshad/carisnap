<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Review;
use App\Models\User;

class ReviewPolicy
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
        return false;
    } // Only admin reviews them

    public function view(User $user, Review $review): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    } // Handled via public frontend

    public function update(User $user, Review $review): bool
    {
        return false;
    } // Only admin can update reviews (publish/unpublish)

    public function delete(User $user, Review $review): bool
    {
        return false;
    }
}
