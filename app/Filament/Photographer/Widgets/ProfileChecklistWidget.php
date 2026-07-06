<?php

namespace App\Filament\Photographer\Widgets;

use App\Filament\Photographer\Resources\AvailabilityResource;
use App\Filament\Photographer\Resources\PackageResource;
use App\Filament\Photographer\Resources\PhotographerProfileResource;
use App\Filament\Photographer\Resources\PortfolioItemResource;
use App\Models\PhotographerProfile;
use Filament\Widgets\Widget;

class ProfileChecklistWidget extends Widget
{
    protected static string $view = 'filament.photographer.widgets.profile-checklist';

    protected int|string|array $columnSpan = 'full';

    public function getProfile(): ?PhotographerProfile
    {
        return auth()->user()->profile;
    }

    /** @return list<array{label: string, done: bool, url: ?string, hint: string}> */
    public function getSteps(): array
    {
        $profile = $this->getProfile();

        if (! $profile) {
            return [];
        }

        $activePackages = $profile->packages()->where('is_active', true)->count();
        $portfolioWithMedia = $profile->portfolioItems()->whereHas('media')->count();
        $futureAvailability = $profile->availabilities()->where('date', '>=', today())->count();
        $profileComplete = filled($profile->bio)
            && filled($profile->whatsapp_number)
            && filled($profile->location_area)
            && ! empty($profile->coverage_areas);

        return [
            [
                'label' => 'Lengkapkan maklumat profil',
                'done' => $profileComplete,
                'url' => PhotographerProfileResource::getUrl('edit', ['record' => $profile]),
                'hint' => 'Bio, lokasi, kawasan liputan & WhatsApp',
            ],
            [
                'label' => 'Tambah sekurang-kurangnya 1 pakej aktif',
                'done' => $activePackages > 0,
                'url' => PackageResource::getUrl('index'),
                'hint' => $activePackages > 0 ? "{$activePackages} pakej aktif" : 'Pelanggan cari harga di profil anda',
            ],
            [
                'label' => 'Upload portfolio (min. 3 gambar)',
                'done' => $portfolioWithMedia >= 3,
                'url' => PortfolioItemResource::getUrl('index'),
                'hint' => $portfolioWithMedia > 0 ? "{$portfolioWithMedia} gambar dimuat naik" : 'Portfolio kosong kurangkan keyakinan pelanggan',
            ],
            [
                'label' => 'Set tarikh kekosongan',
                'done' => $futureAvailability > 0,
                'url' => AvailabilityResource::getUrl('index'),
                'hint' => $futureAvailability > 0 ? "{$futureAvailability} tarikh akan datang" : 'Bantu pelanggan tapis ikut tarikh majlis',
            ],
        ];
    }

    public function isComplete(): bool
    {
        return collect($this->getSteps())->every(fn (array $step) => $step['done']);
    }
}
