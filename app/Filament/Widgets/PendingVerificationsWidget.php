<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PhotographerProfileResource;
use App\Models\PhotographerProfile;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PendingVerificationsWidget extends BaseWidget
{
    protected static ?string $heading = 'Profil Menunggu Semakan';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => PhotographerProfile::query()
                ->whereNull('verified_at')
                ->latest())
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10])
            ->emptyStateHeading('Tiada profil menunggu semakan')
            ->emptyStateDescription('Profil jurugambar baharu akan dipaparkan di sini untuk kelulusan admin.')
            ->emptyStateIcon('heroicon-o-shield-check')
            ->columns([
                Tables\Columns\TextColumn::make('business_name')
                    ->label('Studio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pemilik'),
                Tables\Columns\TextColumn::make('location_area')
                    ->label('Lokasi'),
                Tables\Columns\TextColumn::make('portfolio_items_count')
                    ->label('Portfolio')
                    ->counts('portfolioItems'),
                Tables\Columns\TextColumn::make('packages_count')
                    ->label('Pakej')
                    ->counts('packages'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dihantar')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('review')
                    ->label('Semak')
                    ->icon('heroicon-m-eye')
                    ->url(fn (PhotographerProfile $record): string => PhotographerProfileResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
