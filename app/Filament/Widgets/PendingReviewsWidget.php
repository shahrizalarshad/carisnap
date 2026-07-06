<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ReviewResource;
use App\Models\Review;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PendingReviewsWidget extends BaseWidget
{
    protected static ?string $heading = 'Ulasan Menunggu Moderasi';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Review::query()
                ->whereNull('published_at')
                ->with(['bookingRequest.profile', 'bookingRequest.client'])
                ->latest())
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10])
            ->emptyStateHeading('Tiada ulasan menunggu moderasi')
            ->emptyStateDescription('Ulasan pelanggan yang perlu disemak akan dipaparkan di sini.')
            ->emptyStateIcon('heroicon-o-star')
            ->columns([
                Tables\Columns\TextColumn::make('bookingRequest.profile.business_name')
                    ->label('Studio'),
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Pelanggan')
                    ->getStateUsing(fn (Review $record): string => $record->bookingRequest->guest_name
                        ?? $record->bookingRequest->client?->name
                        ?? 'Pelanggan'),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 4 => 'success',
                        $state >= 3 => 'warning',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Komen')
                    ->limit(40)
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dihantar')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('moderate')
                    ->label('Moderasi')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Review $record): string => ReviewResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
