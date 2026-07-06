<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PhotographerProfileResource;
use App\Filament\Resources\ReviewResource;
use App\Models\BookingRequest;
use App\Models\PhotographerProfile;
use App\Models\Review;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $pendingProfiles = PhotographerProfile::query()->whereNull('verified_at')->count();
        $verifiedProfiles = PhotographerProfile::query()->whereNotNull('verified_at')->count();
        $pendingReviews = Review::query()->whereNull('published_at')->count();
        $bookingRequests = BookingRequest::query()->count();

        return [
            Stat::make('Profil Menunggu', (string) $pendingProfiles)
                ->description($pendingProfiles > 0 ? 'Perlu disemak & disahkan' : 'Tiada profil menunggu semakan')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color($pendingProfiles > 0 ? 'warning' : 'gray')
                ->url(PhotographerProfileResource::getUrl('index')),
            Stat::make('Ulasan Menunggu', (string) $pendingReviews)
                ->description($pendingReviews > 0 ? 'Perlu dimoderasi' : 'Semua ulasan telah diproses')
                ->descriptionIcon('heroicon-m-star')
                ->color($pendingReviews > 0 ? 'warning' : 'gray')
                ->url(ReviewResource::getUrl('index')),
            Stat::make('Studio Disahkan', (string) $verifiedProfiles)
                ->description($verifiedProfiles > 0 ? 'Aktif di laman public' : 'Belum ada studio disahkan')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color($verifiedProfiles > 0 ? 'success' : 'gray')
                ->url(PhotographerProfileResource::getUrl('index')),
            Stat::make('Permintaan Tempahan', (string) $bookingRequests)
                ->description($bookingRequests > 0 ? 'Jumlah permintaan platform' : 'Belum ada permintaan tempahan')
                ->descriptionIcon('heroicon-m-inbox')
                ->color($bookingRequests > 0 ? 'info' : 'gray'),
        ];
    }
}
