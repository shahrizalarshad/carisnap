<?php

namespace App\Filament\Photographer\Resources\PortfolioItemResource\Pages;

use App\Filament\Photographer\Resources\PortfolioItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPortfolioItems extends ListRecords
{
    protected static string $resource = PortfolioItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
