<?php

namespace App\Filament\Photographer\Resources;

use App\Enums\AvailabilityStatus;
use App\Filament\Photographer\Resources\AvailabilityResource\Pages;
use App\Models\Availability;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AvailabilityResource extends Resource
{
    protected static ?string $model = Availability::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Kekosongan';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('profile_id')
                    ->default(fn () => auth()->user()->profile?->id),
                Forms\Components\DatePicker::make('date')
                    ->native(false)
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options(collect(AvailabilityStatus::cases())->mapWithKeys(
                        fn (AvailabilityStatus $status) => [$status->value => ucfirst($status->value)]
                    ))
                    ->default(AvailabilityStatus::Available)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Tarikh')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
            ])
            ->defaultSort('date')
            ->emptyStateHeading('Tiada tarikh kekosongan')
            ->emptyStateDescription('Tambah tarikh yang anda available supaya pelanggan boleh tapis carian ikut tarikh majlis.')
            ->emptyStateIcon('heroicon-o-calendar-days')
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAvailabilities::route('/'),
            'create' => Pages\CreateAvailability::route('/create'),
            'edit' => Pages\EditAvailability::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('profile_id', auth()->user()->profile?->id);
    }
}
