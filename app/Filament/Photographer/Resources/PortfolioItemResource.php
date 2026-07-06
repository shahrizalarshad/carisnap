<?php

namespace App\Filament\Photographer\Resources;

use App\Enums\EventType;
use App\Filament\Photographer\Resources\PortfolioItemResource\Pages;
use App\Models\PortfolioItem;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PortfolioItemResource extends Resource
{
    protected static ?string $model = PortfolioItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Portfolio';

    protected static ?string $modelLabel = 'Portfolio Item';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('profile_id')
                    ->default(fn () => auth()->user()->profile?->id),
                SpatieMediaLibraryFileUpload::make('portfolio')
                    ->label('Photo')
                    ->collection('portfolio')
                    ->image()
                    ->imageEditor()
                    ->maxSize(10240)
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('event_type')
                    ->label('Event Type')
                    ->options(EventType::class)
                    ->default(EventType::Wedding)
                    ->required()
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\Textarea::make('caption')
                    ->label('Caption')
                    ->rows(2)
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->minValue(0)
                    ->default(function (): int {
                        $max = PortfolioItem::query()
                            ->where('profile_id', auth()->user()->profile?->id)
                            ->max('sort_order');

                        return (int) $max + 1;
                    })
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('portfolio')
                    ->label('Photo')
                    ->collection('portfolio')
                    ->conversion('thumbnail')
                    ->square()
                    ->size(60),
                Tables\Columns\TextColumn::make('caption')
                    ->label('Caption')
                    ->searchable()
                    ->limit(40)
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('event_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Uploaded')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPortfolioItems::route('/'),
            'create' => Pages\CreatePortfolioItem::route('/create'),
            'edit' => Pages\EditPortfolioItem::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('profile_id', auth()->user()->profile?->id);
    }
}
