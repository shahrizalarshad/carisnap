<?php

namespace App\Actions;

use App\Enums\ProfileTier;
use App\Enums\SubscriptionPlan;
use App\Enums\SubscriptionStatus;
use App\Models\PhotographerProfile;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class CreatePhotographerProfile
{
    public function execute(User $user, CreatePhotographerProfileData $data): PhotographerProfile
    {
        Gate::forUser($user)->authorize('create', PhotographerProfile::class);

        if ($data->phone) {
            $user->update(['phone' => $data->phone]);
        }

        $profile = PhotographerProfile::create([
            'user_id' => $user->id,
            'slug' => $this->uniqueSlug($data->businessName),
            'business_name' => $data->businessName,
            'bio' => $data->bio,
            'location_area' => $data->locationArea,
            'coverage_areas' => array_values($data->coverageAreas),
            'instagram_handle' => $data->instagramHandle,
            'whatsapp_number' => $data->whatsappNumber,
            'tier' => ProfileTier::Free,
            'verified_at' => null,
        ]);

        Subscription::create([
            'profile_id' => $profile->id,
            'plan' => SubscriptionPlan::Free,
            'status' => SubscriptionStatus::Active,
            'starts_at' => now(),
            'ends_at' => null,
        ]);

        return $profile;
    }

    protected function uniqueSlug(string $businessName): string
    {
        $base = Str::slug($businessName) ?: 'studio';
        $slug = $base;
        $suffix = 1;

        while (PhotographerProfile::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
