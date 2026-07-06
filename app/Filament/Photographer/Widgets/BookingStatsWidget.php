<?php

namespace App\Filament\Photographer\Widgets;

use App\Enums\BookingStatus;
use App\Models\BookingRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $profileId = auth()->user()->profile?->id;

        if (! $profileId) {
            return [];
        }

        $pending = BookingRequest::query()
            ->where('profile_id', $profileId)
            ->where('status', BookingStatus::Pending)
            ->count();

        $quoted = BookingRequest::query()
            ->where('profile_id', $profileId)
            ->where('status', BookingStatus::Quoted)
            ->count();

        $accepted = BookingRequest::query()
            ->where('profile_id', $profileId)
            ->where('status', BookingStatus::Accepted)
            ->count();

        return [
            Stat::make('Menunggu Respons', (string) $pending)
                ->description($pending > 0 ? 'Permintaan baharu perlu dibalas' : 'Tiada permintaan baharu buat masa ini')
                ->descriptionIcon('heroicon-m-inbox')
                ->color($pending > 0 ? 'warning' : 'gray')
                ->url('/photographer/booking-requests'),
            Stat::make('Sebut Harga Dihantar', (string) $quoted)
                ->description($quoted > 0 ? 'Menunggu respons pelanggan' : 'Belum ada sebut harga aktif')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color($quoted > 0 ? 'info' : 'gray')
                ->url('/photographer/booking-requests'),
            Stat::make('Tempahan Disahkan', (string) $accepted)
                ->description($accepted > 0 ? 'Deal disahkan pelanggan' : 'Belum ada tempahan disahkan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($accepted > 0 ? 'success' : 'gray')
                ->url('/photographer/booking-requests'),
        ];
    }
}
