<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PhotographerProfileResource\Pages;
use App\Models\PhotographerProfile;
use App\Notifications\ProfileApprovedNotification;
use App\Notifications\ProfileRejectedNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PhotographerProfileResource extends Resource
{
    protected static ?string $model = PhotographerProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Profile Verification & Details')
                    ->description('Review the photographer profile details before approving.')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->disabled(),
                        Forms\Components\TextInput::make('business_name')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\Textarea::make('bio')
                            ->required()
                            ->columnSpanFull()
                            ->disabled(),
                        Forms\Components\TextInput::make('location_area')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\TextInput::make('whatsapp_number')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\TextInput::make('instagram_handle')
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\TextInput::make('tier')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('verified_at')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('featured_until')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Owner Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('business_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location_area')
                    ->searchable(),
                Tables\Columns\TextColumn::make('whatsapp_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tier')
                    ->badge()
                    ->color(fn ($state): string => match ($state->value ?? $state) {
                        'free' => 'gray',
                        'pro' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_verified')
                    ->label('Verification')
                    ->boolean()
                    ->getStateUsing(fn ($record): bool => ! is_null($record->verified_at)),
                Tables\Columns\TextColumn::make('verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('featured_until')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('verified')
                    ->label('Verified Profiles')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('verified_at')),
                Tables\Filters\Filter::make('unverified')
                    ->label('Pending Verification')
                    ->query(fn (Builder $query): Builder => $query->whereNull('verified_at')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($record) {
                        $record->update(['verified_at' => now()]);
                        $record->user->notify(new ProfileApprovedNotification);
                    })
                    ->visible(fn ($record) => is_null($record->verified_at))
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function ($record) {
                        $record->update(['verified_at' => null]);
                        $record->user->notify(new ProfileRejectedNotification);
                    })
                    ->visible(fn ($record) => ! is_null($record->verified_at))
                    ->requiresConfirmation(),
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
            'index' => Pages\ListPhotographerProfiles::route('/'),
            'create' => Pages\CreatePhotographerProfile::route('/create'),
            'edit' => Pages\EditPhotographerProfile::route('/{record}/edit'),
        ];
    }
}
