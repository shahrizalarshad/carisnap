<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Ulasan';

    protected static ?string $modelLabel = 'Ulasan';

    protected static ?string $navigationGroup = 'Moderasi';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $count = Review::query()->whereNull('published_at')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('booking_request_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('rating')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('comment')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('published_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bookingRequest.profile.business_name')
                    ->label('Studio')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bookingRequest.guest_name')
                    ->label('Pelanggan')
                    ->state(fn ($record) => $record->bookingRequest->guest_name ?? $record->bookingRequest->client?->name)
                    ->searchable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Komen')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\IconColumn::make('published_at')
                    ->label('Status')
                    ->icon(fn ($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-clock')
                    ->color(fn ($state) => $state ? 'success' : 'warning')
                    ->tooltip(fn ($state) => $state ? 'Diterbitkan' : 'Menunggu moderasi'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('published_at')
                    ->label('Status Moderasi')
                    ->placeholder('Semua ulasan')
                    ->trueLabel('Diterbitkan')
                    ->falseLabel('Menunggu moderasi')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('published_at'),
                        false: fn (Builder $query) => $query->whereNull('published_at'),
                        blank: fn (Builder $query) => $query,
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('publish')
                    ->label('Terbitkan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn ($record) => $record->update(['published_at' => now()]))
                    ->visible(fn ($record) => is_null($record->published_at)),
                Tables\Actions\Action::make('unpublish')
                    ->label('Sorok')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(fn ($record) => $record->update(['published_at' => null]))
                    ->visible(fn ($record) => ! is_null($record->published_at)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
