<?php

namespace App\Filament\Photographer\Resources\BookingRequestResource\Pages;

use App\Filament\Photographer\Resources\BookingRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookingRequests extends ListRecords
{
    protected static string $resource = BookingRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
