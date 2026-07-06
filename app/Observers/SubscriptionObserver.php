<?php

namespace App\Observers;

use App\Enums\ProfileTier;
use App\Enums\SubscriptionPlan;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;

class SubscriptionObserver
{
    public function saved(Subscription $subscription): void
    {
        $this->syncTier($subscription);
    }

    public function deleted(Subscription $subscription): void
    {
        $this->syncTier($subscription);
    }

    protected function syncTier(Subscription $subscription): void
    {
        $profile = $subscription->profile;
        if (! $profile) {
            return;
        }

        // Find if there is an active pro subscription
        $hasActivePro = $profile->subscriptions()
            ->where('status', SubscriptionStatus::Active->value)
            ->where('plan', SubscriptionPlan::Pro->value)
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            })
            ->exists();

        $profile->update([
            'tier' => $hasActivePro ? ProfileTier::Pro : ProfileTier::Free,
        ]);
    }
}
