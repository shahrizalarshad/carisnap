<?php

namespace App\Actions;

use App\Models\PhotographerProfile;
use App\Notifications\ProfileApprovedNotification;
use Filament\Notifications\Notification;

class ApprovePhotographerProfile
{
    public function execute(PhotographerProfile $profile): PhotographerProfile
    {
        $profile->update(['verified_at' => now()]);

        $profile->user->notify(new ProfileApprovedNotification($profile));

        Notification::make()
            ->title('Profil disahkan')
            ->body($profile->business_name.' kini boleh dilihat oleh pelanggan.')
            ->success()
            ->send();

        return $profile->fresh();
    }
}
