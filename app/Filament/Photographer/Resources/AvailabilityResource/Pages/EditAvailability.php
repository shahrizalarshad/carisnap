<?php

namespace App\Filament\Photographer\Resources\AvailabilityResource\Pages;

use App\Filament\Photographer\Resources\AvailabilityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAvailability extends EditRecord
{
    protected static string $resource = AvailabilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
