<?php

namespace App\Filament\Photographer\Resources;

use App\Enums\BookingStatus;
use App\Enums\QuoteStatus;
use App\Filament\Photographer\Resources\BookingRequestResource\Pages;
use App\Models\BookingRequest;
use App\Notifications\BookingDeclinedNotification;
use App\Notifications\QuoteReceivedNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class BookingRequestResource extends Resource
{
    protected static ?string $model = BookingRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('client_id')
                    ->numeric(),
                Forms\Components\TextInput::make('guest_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('guest_phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('guest_email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('profile_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('package_id')
                    ->numeric(),
                Forms\Components\TextInput::make('event_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('event_date')
                    ->required(),
                Forms\Components\TextInput::make('location')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('budget_from')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('budget_to')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('message')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('pending'),
                Forms\Components\DateTimePicker::make('responded_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('guest_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('guest_phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('guest_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('profile_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('package_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('event_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('budget_from')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('budget_to')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responded_at')
                    ->dateTime()
                    ->sortable(),
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('sendQuote')
                    ->label('Send Quote')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('success')
                    ->form([
                        Forms\Components\TextInput::make('amount')->numeric()->required()->prefix('RM'),
                        Forms\Components\Textarea::make('message')->nullable(),
                    ])
                    ->action(function ($record, array $data) {
                        $quote = $record->quotes()->create([
                            'amount' => $data['amount'],
                            'message' => $data['message'],
                            'status' => QuoteStatus::Sent,
                            'valid_until' => now()->addDays(7),
                        ]);
                        $record->update(['status' => BookingStatus::Quoted, 'responded_at' => now()]);

                        // Guest uses guest_email, User uses email
                        $email = $record->client_id ? $record->client->email : $record->guest_email;
                        if ($email) {
                            Notification::route('mail', $email)
                                ->notify(new QuoteReceivedNotification($quote));
                        }
                    })
                    ->visible(fn ($record) => $record->status === BookingStatus::Pending),
                Tables\Actions\Action::make('decline')
                    ->label('Decline')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function ($record) {
                        $record->update(['status' => BookingStatus::Declined, 'responded_at' => now()]);

                        $email = $record->client_id ? $record->client->email : $record->guest_email;
                        if ($email) {
                            Notification::route('mail', $email)
                                ->notify(new BookingDeclinedNotification($record));
                        }
                    })
                    ->visible(fn ($record) => $record->status === BookingStatus::Pending),
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
            'index' => Pages\ListBookingRequests::route('/'),
            'create' => Pages\CreateBookingRequest::route('/create'),
            'edit' => Pages\EditBookingRequest::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('profile_id', auth()->user()->profile?->id);
    }
}
