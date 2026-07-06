<?php

namespace App\Filament\Photographer\Resources;

use App\Actions\DeclineBookingRequest;
use App\Actions\SendQuote;
use App\Actions\SendQuoteData;
use App\Enums\BookingStatus;
use App\Filament\Photographer\Resources\BookingRequestResource\Pages;
use App\Models\BookingRequest;
use Filament\Actions\Action as PageAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BookingRequestResource extends Resource
{
    protected static ?string $model = BookingRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationLabel = 'Permintaan Tempahan';

    protected static ?string $modelLabel = 'Booking Request';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema(static::getFormSchema())->disabled();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema(static::getInfolistSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client_display')
                    ->label('Client')
                    ->getStateUsing(fn (BookingRequest $record): string => static::clientName($record))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $query) use ($search): void {
                            $query->where('guest_name', 'like', "%{$search}%")
                                ->orWhereHas('client', fn (Builder $q) => $q->where('name', 'like', "%{$search}%"));
                        });
                    })
                    ->description(fn (BookingRequest $record): ?string => static::clientContact($record)),
                Tables\Columns\TextColumn::make('event_date')
                    ->label('Event Date')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('budget_from')
                    ->label('Budget')
                    ->formatStateUsing(fn ($state, BookingRequest $record): string => 'RM'.number_format($record->budget_from).' – RM'.number_format($record->budget_to)),
                Tables\Columns\TextColumn::make('package.name')
                    ->label('Package')
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (BookingStatus $state): string => match ($state) {
                        BookingStatus::Pending => 'warning',
                        BookingStatus::Quoted => 'info',
                        BookingStatus::Accepted => 'success',
                        BookingStatus::Declined => 'danger',
                        BookingStatus::Expired => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Tiada permintaan tempahan lagi')
            ->emptyStateDescription('Bila pelanggan hantar permintaan dari profil anda, ia akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-inbox')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(BookingStatus::class),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                static::makeSendQuoteTableAction(),
                static::makeDeclineTableAction(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingRequests::route('/'),
            'view' => Pages\ViewBookingRequest::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('profile_id', auth()->user()->profile?->id)
            ->with(['client', 'package']);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function makeSendQuotePageAction(): PageAction
    {
        return static::configureSendQuoteAction(PageAction::make('sendQuote'));
    }

    public static function makeDeclinePageAction(): PageAction
    {
        return static::configureDeclineAction(PageAction::make('decline'));
    }

    protected static function makeSendQuoteTableAction(): TableAction
    {
        return static::configureSendQuoteAction(TableAction::make('sendQuote'));
    }

    protected static function makeDeclineTableAction(): TableAction
    {
        return static::configureDeclineAction(TableAction::make('decline'));
    }

    protected static function configureSendQuoteAction(PageAction|TableAction $action): PageAction|TableAction
    {
        return $action
            ->label('Send Quote')
            ->icon('heroicon-o-currency-dollar')
            ->color('success')
            ->form([
                Forms\Components\TextInput::make('amount')
                    ->label('Quote Amount (RM)')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->prefix('RM'),
                Forms\Components\Textarea::make('message')
                    ->label('Message to Client')
                    ->nullable()
                    ->rows(3),
            ])
            ->action(function (BookingRequest $record, array $data): void {
                app(SendQuote::class)->execute($record, new SendQuoteData(
                    amount: (int) $data['amount'],
                    message: $data['message'] ?? null,
                ));
            })
            ->visible(fn (BookingRequest $record): bool => $record->status === BookingStatus::Pending)
            ->successNotificationTitle('Quote sent');
    }

    protected static function configureDeclineAction(PageAction|TableAction $action): PageAction|TableAction
    {
        return $action
            ->label('Decline')
            ->icon('heroicon-o-x-circle')
            ->color('danger')
            ->requiresConfirmation()
            ->modalDescription('The client will be notified that you declined this booking request.')
            ->action(function (BookingRequest $record): void {
                app(DeclineBookingRequest::class)->execute($record);
            })
            ->visible(fn (BookingRequest $record): bool => $record->status === BookingStatus::Pending)
            ->successNotificationTitle('Booking declined');
    }

    protected static function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Client')
                ->schema([
                    Forms\Components\Placeholder::make('client_name')
                        ->label('Name')
                        ->content(fn (?BookingRequest $record): string => $record ? static::clientName($record) : '—'),
                    Forms\Components\Placeholder::make('client_phone')
                        ->label('Phone')
                        ->content(fn (?BookingRequest $record): string => $record ? (static::clientPhone($record) ?? '—') : '—'),
                    Forms\Components\Placeholder::make('client_email')
                        ->label('Email')
                        ->content(fn (?BookingRequest $record): string => $record ? (static::clientEmail($record) ?? '—') : '—'),
                ])->columns(3),
            Forms\Components\Section::make('Event Details')
                ->schema([
                    Forms\Components\Placeholder::make('event_type')
                        ->label('Event Type')
                        ->content(fn (?BookingRequest $record): string => $record?->event_type?->value ?? '—'),
                    Forms\Components\Placeholder::make('event_date')
                        ->label('Event Date')
                        ->content(fn (?BookingRequest $record): string => $record?->event_date?->format('d/m/Y') ?? '—'),
                    Forms\Components\Placeholder::make('location')
                        ->content(fn (?BookingRequest $record): string => $record?->location ?? '—'),
                    Forms\Components\Placeholder::make('package')
                        ->label('Package')
                        ->content(fn (?BookingRequest $record): string => $record?->package?->name ?? '—'),
                ])->columns(2),
            Forms\Components\Section::make('Budget & Message')
                ->schema([
                    Forms\Components\Placeholder::make('budget')
                        ->label('Budget Range')
                        ->content(fn (?BookingRequest $record): string => $record
                            ? 'RM'.number_format($record->budget_from).' – RM'.number_format($record->budget_to)
                            : '—'),
                    Forms\Components\Placeholder::make('message')
                        ->content(fn (?BookingRequest $record): string => $record?->message ?? '—')
                        ->columnSpanFull(),
                ]),
            Forms\Components\Section::make('Status')
                ->schema([
                    Forms\Components\Placeholder::make('status')
                        ->content(fn (?BookingRequest $record): string => ucfirst($record?->status?->value ?? '—')),
                    Forms\Components\Placeholder::make('responded_at')
                        ->label('Responded At')
                        ->content(fn (?BookingRequest $record): string => $record?->responded_at?->format('d/m/Y H:i') ?? '—'),
                    Forms\Components\Placeholder::make('created_at')
                        ->label('Received At')
                        ->content(fn (?BookingRequest $record): string => $record?->created_at?->format('d/m/Y H:i') ?? '—'),
                ])->columns(3),
        ];
    }

    protected static function getInfolistSchema(): array
    {
        return [
            Infolists\Components\Section::make('Client')
                ->schema([
                    Infolists\Components\TextEntry::make('client_display')
                        ->label('Name')
                        ->getStateUsing(fn (BookingRequest $record): string => static::clientName($record)),
                    Infolists\Components\TextEntry::make('client_phone')
                        ->label('Phone')
                        ->getStateUsing(fn (BookingRequest $record): string => static::clientPhone($record) ?? '—'),
                    Infolists\Components\TextEntry::make('client_email')
                        ->label('Email')
                        ->getStateUsing(fn (BookingRequest $record): string => static::clientEmail($record) ?? '—'),
                ])->columns(3),
            Infolists\Components\Section::make('Event Details')
                ->schema([
                    Infolists\Components\TextEntry::make('event_type')
                        ->badge(),
                    Infolists\Components\TextEntry::make('event_date')
                        ->date('d/m/Y'),
                    Infolists\Components\TextEntry::make('location'),
                    Infolists\Components\TextEntry::make('package.name')
                        ->label('Package')
                        ->placeholder('—'),
                ])->columns(2),
            Infolists\Components\Section::make('Budget & Message')
                ->schema([
                    Infolists\Components\TextEntry::make('budget_range')
                        ->label('Budget')
                        ->getStateUsing(fn (BookingRequest $record): string => 'RM'.number_format($record->budget_from).' – RM'.number_format($record->budget_to)),
                    Infolists\Components\TextEntry::make('message')
                        ->placeholder('No message')
                        ->columnSpanFull(),
                ]),
            Infolists\Components\Section::make('Status')
                ->schema([
                    Infolists\Components\TextEntry::make('status')
                        ->badge()
                        ->color(fn (BookingStatus $state): string => match ($state) {
                            BookingStatus::Pending => 'warning',
                            BookingStatus::Quoted => 'info',
                            BookingStatus::Accepted => 'success',
                            BookingStatus::Declined => 'danger',
                            BookingStatus::Expired => 'gray',
                        }),
                    Infolists\Components\TextEntry::make('responded_at')
                        ->dateTime()
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('created_at')
                        ->label('Received')
                        ->dateTime(),
                ])->columns(3),
        ];
    }

    protected static function clientName(BookingRequest $record): string
    {
        return $record->client_id
            ? ($record->client?->name ?? 'Registered Client')
            : ($record->guest_name ?? 'Guest');
    }

    protected static function clientPhone(BookingRequest $record): ?string
    {
        return $record->client_id
            ? $record->client?->phone
            : $record->guest_phone;
    }

    protected static function clientEmail(BookingRequest $record): ?string
    {
        return $record->client_id
            ? $record->client?->email
            : $record->guest_email;
    }

    protected static function clientContact(BookingRequest $record): ?string
    {
        $phone = static::clientPhone($record);
        $email = static::clientEmail($record);

        return collect([$phone, $email])->filter()->implode(' · ');
    }
}
