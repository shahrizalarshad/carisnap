<?php

namespace App\Filament\Resources\PhotographerProfileResource\Pages;

use App\Filament\Resources\PhotographerProfileResource;
use Filament\Resources\Pages\ListRecords;

class ListPhotographerProfiles extends ListRecords
{
    protected static string $resource = PhotographerProfileResource::class;

    public ?array $tableFilters = [
        'unverified' => true,
    ];

    protected function getHeaderActions(): array
    {
        return [];
    }
}
