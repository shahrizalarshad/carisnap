<?php

namespace App\Filament\Photographer\Resources\PhotographerProfileResource\Pages;

use App\Filament\Photographer\Resources\PhotographerProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPhotographerProfile extends EditRecord
{
    protected static string $resource = PhotographerProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
