<?php

namespace App\Filament\Photographer\Pages;

use App\Filament\Photographer\Widgets\BookingStatsWidget;
use App\Filament\Photographer\Widgets\ProfileStatusWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Utama';

    protected static ?string $title = 'Panel Jurugambar';

    public function getWidgets(): array
    {
        return [
            ProfileStatusWidget::class,
            BookingStatsWidget::class,
        ];
    }
}
