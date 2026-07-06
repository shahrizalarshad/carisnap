<?php

namespace App\Actions;

use App\Models\PhotographerProfile;
use Filament\Notifications\Notification;

class UnfeaturePhotographerProfile
{
    public function execute(PhotographerProfile $profile): PhotographerProfile
    {
        $profile->update(['featured_until' => null]);

        Notification::make()
            ->title('Status featured dibuang')
            ->body($profile->business_name.' tidak lagi dipaparkan sebagai studio pilihan.')
            ->success()
            ->send();

        return $profile->fresh();
    }
}
