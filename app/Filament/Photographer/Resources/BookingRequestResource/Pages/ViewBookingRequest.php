<?php

namespace App\Filament\Photographer\Resources\BookingRequestResource\Pages;

use App\Filament\Photographer\Resources\BookingRequestResource;
use Filament\Resources\Pages\ViewRecord;

class ViewBookingRequest extends ViewRecord
{
    protected static string $resource = BookingRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            BookingRequestResource::makeSendQuotePageAction(),
            BookingRequestResource::makeDeclinePageAction(),
        ];
    }
}
