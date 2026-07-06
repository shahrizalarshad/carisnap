<?php

namespace App\Actions;

use App\Models\PhotographerProfile;
use App\Notifications\ProfileRejectedNotification;
use Filament\Notifications\Notification;

class RejectPhotographerProfile
{
    public function execute(PhotographerProfile $profile, ?string $reason = null): PhotographerProfile
    {
        $profile->update(['verified_at' => null]);

        $profile->user->notify(new ProfileRejectedNotification($reason));

        Notification::make()
            ->title('Profil ditolak')
            ->body($profile->business_name.' telah dimaklumkan untuk kemaskini profil.')
            ->warning()
            ->send();

        return $profile->fresh();
    }
}
