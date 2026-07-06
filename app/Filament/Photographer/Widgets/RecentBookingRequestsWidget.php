<?php

namespace App\Filament\Photographer\Widgets;

use App\Enums\BookingStatus;
use App\Filament\Photographer\Resources\BookingRequestResource;
use App\Models\BookingRequest;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentBookingRequestsWidget extends BaseWidget
{
    protected static ?string $heading = 'Permintaan Terkini';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => BookingRequest::query()
                ->where('profile_id', auth()->user()->profile?->id)
                ->latest())
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10])
            ->emptyStateHeading('Belum ada permintaan tempahan')
            ->emptyStateDescription('Bila pelanggan hantar permintaan dari profil public anda, ia akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-inbox')
            ->columns([
                Tables\Columns\TextColumn::make('client_display')
                    ->label('Pelanggan')
                    ->getStateUsing(fn (BookingRequest $record): string => $record->guest_name
                        ?? $record->client?->name
                        ?? 'Pelanggan')
                    ->description(fn (BookingRequest $record): ?string => $record->guest_phone ?? $record->client?->phone),
                Tables\Columns\TextColumn::make('event_date')
                    ->label('Tarikh Majlis')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->limit(28),
                Tables\Columns\TextColumn::make('budget_from')
                    ->label('Bajet')
                    ->formatStateUsing(fn ($state, BookingRequest $record): string => 'RM'.number_format($record->budget_from).' – RM'.number_format($record->budget_to)),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (BookingStatus $state): string => $state->label())
                    ->color(fn (BookingStatus $state): string => match ($state) {
                        BookingStatus::Pending => 'warning',
                        BookingStatus::Quoted => 'info',
                        BookingStatus::Accepted => 'success',
                        BookingStatus::Declined => 'danger',
                        BookingStatus::Expired => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dihantar')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn (BookingRequest $record): string => BookingRequestResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
