<?php

namespace App\Filament\Resources\PhotographerProfileResource\Pages;

use App\Actions\ApprovePhotographerProfile;
use App\Actions\FeaturePhotographerProfile;
use App\Actions\FeaturePhotographerProfileData;
use App\Actions\RejectPhotographerProfile;
use App\Actions\UnfeaturePhotographerProfile;
use App\Filament\Resources\PhotographerProfileResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ViewRecord;

class ViewPhotographerProfile extends ViewRecord
{
    protected static string $resource = PhotographerProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Sahkan Profil')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Sahkan profil jurugambar?')
                ->modalDescription('Profil akan dipaparkan kepada pelanggan dan jurugambar akan dimaklumkan melalui e-mel.')
                ->visible(fn (): bool => is_null($this->record->verified_at))
                ->action(fn (ApprovePhotographerProfile $action) => $action->execute($this->record)),
            Actions\Action::make('reject')
                ->label('Tolak Profil')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Tolak profil jurugambar?')
                ->form([
                    Forms\Components\Textarea::make('reason')
                        ->label('Sebab (opsyenal)')
                        ->rows(3)
                        ->maxLength(500),
                ])
                ->visible(fn (): bool => is_null($this->record->verified_at))
                ->action(fn (array $data, RejectPhotographerProfile $action) => $action->execute(
                    $this->record,
                    $data['reason'] ?? null,
                )),
            Actions\Action::make('revoke')
                ->label('Tarik Balik Kelulusan')
                ->icon('heroicon-o-no-symbol')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (): bool => ! is_null($this->record->verified_at))
                ->action(fn (RejectPhotographerProfile $action) => $action->execute($this->record)),
            Actions\Action::make('feature')
                ->label('Tandakan Featured')
                ->icon('heroicon-o-star')
                ->color('warning')
                ->form(PhotographerProfileResource::getFeatureFormSchema())
                ->visible(fn (): bool => ! is_null($this->record->verified_at) && ! $this->record->isFeatured())
                ->action(function (array $data): void {
                    app(FeaturePhotographerProfile::class)->execute(
                        $this->record,
                        new FeaturePhotographerProfileData(durationDays: (int) $data['duration_days']),
                    );
                }),
            Actions\Action::make('unfeature')
                ->label('Buang Featured')
                ->icon('heroicon-o-star')
                ->color('gray')
                ->requiresConfirmation()
                ->modalDescription('Studio ini tidak lagi dipaparkan di bahagian utama laman web.')
                ->visible(fn (): bool => $this->record->isFeatured())
                ->action(function (): void {
                    app(UnfeaturePhotographerProfile::class)->execute($this->record);
                }),
        ];
    }
}
