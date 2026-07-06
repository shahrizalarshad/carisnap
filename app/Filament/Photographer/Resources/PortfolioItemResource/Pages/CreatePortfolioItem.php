<?php

namespace App\Filament\Photographer\Resources\PortfolioItemResource\Pages;

use App\Enums\EventType;
use App\Filament\Photographer\Resources\PortfolioItemResource;
use App\Models\PortfolioItem;
use Filament\Resources\Pages\CreateRecord;

class CreatePortfolioItem extends CreateRecord
{
    protected static string $resource = PortfolioItemResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['event_type'] = EventType::Wedding;

        if (! isset($data['sort_order']) || $data['sort_order'] === 0) {
            $data['sort_order'] = (int) PortfolioItem::query()
                ->where('profile_id', $data['profile_id'])
                ->max('sort_order') + 1;
        }

        return $data;
    }
}
