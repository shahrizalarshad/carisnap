<?php

namespace App\Actions;

use App\Models\PhotographerProfile;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class FeaturePhotographerProfile
{
    public function execute(PhotographerProfile $profile, FeaturePhotographerProfileData $data): PhotographerProfile
    {
        if (is_null($profile->verified_at)) {
            throw ValidationException::withMessages([
                'profile' => 'Hanya profil yang telah disahkan boleh ditandakan sebagai featured.',
            ]);
        }

        if ($data->durationDays < 1) {
            throw ValidationException::withMessages([
                'duration_days' => 'Tempoh featured mestilah sekurang-kurangnya 1 hari.',
            ]);
        }

        $profile->update([
            'featured_until' => now()->addDays($data->durationDays),
        ]);

        Notification::make()
            ->title('Studio ditandakan featured')
            ->body($profile->business_name.' akan dipaparkan di bahagian utama sehingga '.$profile->fresh()->featured_until->format('d/m/Y').'.')
            ->success()
            ->send();

        return $profile->fresh();
    }
}
