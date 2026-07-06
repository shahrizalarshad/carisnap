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
                ->description('Permintaan baharu')
                ->descriptionIcon('heroicon-m-inbox')
                ->color($pending > 0 ? 'warning' : 'gray')
                ->url('/photographer/booking-requests'),
            Stat::make('Sebut Harga Dihantar', (string) $quoted)
                ->description('Menunggu pelanggan')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('info')
                ->url('/photographer/booking-requests'),
            Stat::make('Tempahan Disahkan', (string) $accepted)
                ->description('Diterima pelanggan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->url('/photographer/booking-requests'),
        ];
    }
}
