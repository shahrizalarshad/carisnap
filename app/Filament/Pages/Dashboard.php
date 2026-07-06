<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminStatsWidget;
use App\Filament\Widgets\PendingReviewsWidget;
use App\Filament\Widgets\PendingVerificationsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Utama';

    protected static ?string $title = 'Panel Admin';

    public function getWidgets(): array
    {
        return [
            AdminStatsWidget::class,
            PendingVerificationsWidget::class,
            PendingReviewsWidget::class,
        ];
    }
}
