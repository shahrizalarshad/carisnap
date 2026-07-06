<?php

namespace App\Filament\Resources;

use App\Actions\ApprovePhotographerProfile;
use App\Actions\FeaturePhotographerProfile;
use App\Actions\FeaturePhotographerProfileData;
use App\Actions\RejectPhotographerProfile;
use App\Actions\UnfeaturePhotographerProfile;
use App\Filament\Resources\PhotographerProfileResource\Pages;
use App\Models\PhotographerProfile;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PhotographerProfileResource extends Resource
{
    protected static ?string $model = PhotographerProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Semakan Profil';

    protected static ?string $modelLabel = 'Profil Jurugambar';

    protected static ?string $navigationGroup = 'Moderasi';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $count = PhotographerProfile::query()->whereNull('verified_at')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([])->disabled();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema(static::getInfolistSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('business_name')
                    ->label('Studio')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pemilik')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('E-mel')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('location_area')
                    ->label('Lokasi'),
                Tables\Columns\TextColumn::make('portfolio_items_count')
                    ->label('Portfolio')
                    ->counts('portfolioItems')
                    ->sortable(),
                Tables\Columns\TextColumn::make('packages_count')
                    ->label('Pakej')
                    ->counts('packages')
                    ->sortable(),
                Tables\Columns\TextColumn::make('verification_status')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(fn (PhotographerProfile $record): string => $record->verified_at ? 'Disahkan' : 'Menunggu')
                    ->color(fn (PhotographerProfile $record): string => $record->verified_at ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('featured_until')
                    ->label('Featured')
                    ->badge()
                    ->formatStateUsing(fn ($state, PhotographerProfile $record): string => $record->isFeatured()
                        ? 'Sehingga '.$record->featured_until->format('d/m/Y')
                        : '—')
                    ->color(fn (PhotographerProfile $record): string => $record->isFeatured() ? 'warning' : 'gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dihantar')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('unverified')
                    ->label('Menunggu Semakan')
                    ->query(fn (Builder $query): Builder => $query->whereNull('verified_at')),
                Tables\Filters\Filter::make('verified')
                    ->label('Disahkan')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('verified_at')),
                Tables\Filters\Filter::make('featured')
                    ->label('Featured Aktif')
                    ->query(fn (Builder $query): Builder => $query
                        ->whereNotNull('featured_until')
                        ->where('featured_until', '>', now())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                static::makeApproveTableAction(),
                static::makeRejectTableAction(),
                static::makeFeatureTableAction(),
                static::makeUnfeatureTableAction(),
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
            'index' => Pages\ListPhotographerProfiles::route('/'),
            'view' => Pages\ViewPhotographerProfile::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    protected static function makeApproveTableAction(): TableAction
    {
        return TableAction::make('approve')
            ->label('Sahkan')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->requiresConfirmation()
            ->visible(fn (PhotographerProfile $record): bool => is_null($record->verified_at))
            ->action(fn (PhotographerProfile $record, ApprovePhotographerProfile $action) => $action->execute($record));
    }

    protected static function makeRejectTableAction(): TableAction
    {
        return TableAction::make('reject')
            ->label('Tolak')
            ->icon('heroicon-o-x-circle')
            ->color('danger')
            ->requiresConfirmation()
            ->form([
                Forms\Components\Textarea::make('reason')
                    ->label('Sebab (opsyenal)')
                    ->rows(3)
                    ->maxLength(500),
            ])
            ->action(fn (PhotographerProfile $record, array $data, RejectPhotographerProfile $action) => $action->execute(
                $record,
                $data['reason'] ?? null,
            ));
    }

    protected static function makeFeatureTableAction(): TableAction
    {
        return static::configureFeatureAction(TableAction::make('feature'));
    }

    protected static function makeUnfeatureTableAction(): TableAction
    {
        return static::configureUnfeatureAction(TableAction::make('unfeature'));
    }

    protected static function configureFeatureAction(TableAction|Actions\Action $action): TableAction|Actions\Action
    {
        return $action
            ->label('Tandakan Featured')
            ->icon('heroicon-o-star')
            ->color('warning')
            ->form(static::getFeatureFormSchema())
            ->visible(fn (PhotographerProfile $record): bool => ! is_null($record->verified_at) && ! $record->isFeatured())
            ->action(function (PhotographerProfile $record, array $data): void {
                app(FeaturePhotographerProfile::class)->execute(
                    $record,
                    new FeaturePhotographerProfileData(durationDays: (int) $data['duration_days']),
                );
            });
    }

    protected static function configureUnfeatureAction(TableAction|Actions\Action $action): TableAction|Actions\Action
    {
        return $action
            ->label('Buang Featured')
            ->icon('heroicon-o-star')
            ->color('gray')
            ->requiresConfirmation()
            ->modalDescription('Studio ini tidak lagi dipaparkan di bahagian utama laman web.')
            ->visible(fn (PhotographerProfile $record): bool => $record->isFeatured())
            ->action(function (PhotographerProfile $record): void {
                app(UnfeaturePhotographerProfile::class)->execute($record);
            });
    }

    /** @return array<int, Forms\Components\Component> */
    public static function getFeatureFormSchema(): array
    {
        return [
            Forms\Components\Select::make('duration_days')
                ->label('Tempoh Featured')
                ->options([
                    7 => '7 hari',
                    30 => '30 hari',
                    90 => '90 hari',
                ])
                ->default(30)
                ->required(),
        ];
    }

    protected static function getInfolistSchema(): array
    {
        return [
            Infolists\Components\Section::make('Status Semakan')
                ->schema([
                    Infolists\Components\TextEntry::make('verification_status')
                        ->label('Status')
                        ->badge()
                        ->getStateUsing(fn (PhotographerProfile $record): string => $record->verified_at ? 'Disahkan' : 'Menunggu Semakan')
                        ->color(fn (PhotographerProfile $record): string => $record->verified_at ? 'success' : 'warning'),
                    Infolists\Components\TextEntry::make('verified_at')
                        ->label('Disahkan Pada')
                        ->dateTime('d/m/Y H:i')
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('featured_until')
                        ->label('Featured Sehingga')
                        ->formatStateUsing(fn ($state, PhotographerProfile $record): string => $record->isFeatured()
                            ? $record->featured_until->format('d/m/Y H:i')
                            : '—'),
                    Infolists\Components\TextEntry::make('created_at')
                        ->label('Dihantar Pada')
                        ->dateTime('d/m/Y H:i'),
                ])->columns(3),
            Infolists\Components\Section::make('Pemilik')
                ->schema([
                    Infolists\Components\TextEntry::make('user.name')
                        ->label('Nama'),
                    Infolists\Components\TextEntry::make('user.email')
                        ->label('E-mel'),
                    Infolists\Components\TextEntry::make('user.phone')
                        ->label('Telefon')
                        ->placeholder('—'),
                ])->columns(3),
            Infolists\Components\Section::make('Profil Studio')
                ->schema([
                    Infolists\Components\TextEntry::make('business_name')
                        ->label('Nama Studio'),
                    Infolists\Components\TextEntry::make('slug')
                        ->label('URL Slug')
                        ->copyable(),
                    Infolists\Components\TextEntry::make('location_area')
                        ->label('Lokasi Utama'),
                    Infolists\Components\TextEntry::make('coverage_areas')
                        ->label('Kawasan Liputan')
                        ->formatStateUsing(fn (?array $state): string => collect($state ?? [])->join(', ') ?: '—'),
                    Infolists\Components\TextEntry::make('whatsapp_number')
                        ->label('WhatsApp'),
                    Infolists\Components\TextEntry::make('instagram_handle')
                        ->label('Instagram')
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('bio')
                        ->columnSpanFull(),
                ])->columns(2),
            Infolists\Components\Section::make('Kandungan')
                ->schema([
                    Infolists\Components\TextEntry::make('portfolio_items_count')
                        ->label('Item Portfolio')
                        ->getStateUsing(fn (PhotographerProfile $record): string => (string) $record->portfolioItems()->count()),
                    Infolists\Components\TextEntry::make('packages_count')
                        ->label('Pakej Aktif')
                        ->getStateUsing(fn (PhotographerProfile $record): string => (string) $record->packages()->where('is_active', true)->count()),
                    Infolists\Components\TextEntry::make('tier')
                        ->badge(),
                ])->columns(3),
        ];
    }
}
