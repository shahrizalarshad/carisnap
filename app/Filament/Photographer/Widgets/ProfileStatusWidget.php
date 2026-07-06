<?php

namespace App\Filament\Photographer\Widgets;

use Filament\Widgets\Widget;

class ProfileStatusWidget extends Widget
{
    protected static string $view = 'filament.photographer.widgets.profile-status';

    protected int|string|array $columnSpan = 'full';

    public function getProfile()
    {
        return auth()->user()->profile;
    }
}
