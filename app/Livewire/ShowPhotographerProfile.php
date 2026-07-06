<?php

namespace App\Livewire;

use App\Enums\AvailabilityStatus;
use App\Models\PhotographerProfile;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class ShowPhotographerProfile extends Component
{
    public PhotographerProfile $profile;

    public function mount(string $slug)
    {
        $this->profile = PhotographerProfile::visible()
            ->where('slug', $slug)
            ->with([
                'portfolioItems' => fn ($q) => $q->orderBy('sort_order')->with('media'),
                'packages' => fn ($q) => $q->where('is_active', true)->orderBy('price_from'),
                'availabilities' => fn ($q) => $q->where('date', '>=', today())
                    ->where('date', '<=', today()->addMonths(3))
                    ->where('status', AvailabilityStatus::Available)
                    ->orderBy('date'),
                'reviews' => fn ($q) => $q->whereNotNull('published_at')->latest('published_at')->with('bookingRequest.client'),
            ])
            ->withAvg(['reviews' => fn ($q) => $q->whereNotNull('published_at')], 'rating')
            ->withCount(['reviews' => fn ($q) => $q->whereNotNull('published_at')])
            ->firstOrFail();
    }

    public function render()
    {
        $description = $this->profile->bio
            ? Str::limit($this->profile->bio, 155)
            : "Jurugambar perkahwinan di {$this->profile->location_area}. Lihat portfolio, pakej, dan tarikh kekosongan di CariSnap.";

        $ogImage = $this->profile->portfolioItems->first()?->getFirstMediaUrl('portfolio', 'display')
            ?: asset('images/og-default.svg');

        return view('livewire.show-photographer-profile')
            ->title($this->profile->business_name.' — Jurugambar Perkahwinan | CariSnap')
            ->layoutData([
                'metaDescription' => $description,
                'ogImage' => $ogImage,
                'ogType' => 'profile',
                'canonical' => route('photographers.show', $this->profile->slug),
            ]);
    }
}
