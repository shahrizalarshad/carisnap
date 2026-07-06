<?php

namespace App\Livewire;

use App\Models\PhotographerProfile;
use App\Models\Review;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class HomePage extends Component
{
    public function render()
    {
        $featuredProfiles = PhotographerProfile::visible()
            ->with(['lowestActivePackage', 'coverPortfolioItem.media'])
            ->withAvg(['reviews' => fn ($q) => $q->whereNotNull('published_at')], 'rating')
            ->withCount(['reviews' => fn ($q) => $q->whereNotNull('published_at')])
            ->orderByRaw('CASE WHEN featured_until IS NOT NULL AND featured_until > NOW() THEN 0 ELSE 1 END')
            ->orderByDesc('verified_at')
            ->limit(6)
            ->get();

        $heroImages = $featuredProfiles
            ->map(fn (PhotographerProfile $profile) => $profile->coverPortfolioItem?->getFirstMedia('portfolio'))
            ->filter()
            ->take(4);

        $testimonials = Review::query()
            ->whereNotNull('published_at')
            ->whereNotNull('comment')
            ->with(['bookingRequest.profile', 'bookingRequest.client'])
            ->latest('published_at')
            ->limit(3)
            ->get();

        $verifiedCount = PhotographerProfile::visible()->count();

        return view('livewire.home-page', [
            'featuredProfiles' => $featuredProfiles,
            'heroImages' => $heroImages,
            'testimonials' => $testimonials,
            'verifiedCount' => $verifiedCount,
        ])
            ->title('CariSnap — Cari Jurugambar Perkahwinan Klang Valley')
            ->layoutData([
                'metaDescription' => 'Platform carian jurugambar & videografer perkahwinan di Lembah Klang. Tapis ikut lokasi, bajet & tarikh. Tempah terus, tanpa komisen.',
            ]);
    }
}
