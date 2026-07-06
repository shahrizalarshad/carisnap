<?php

namespace App\Livewire;

use App\Enums\AvailabilityStatus;
use App\Models\PhotographerProfile;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.public')]
class BrowsePhotographers extends Component
{
    use WithPagination;

    #[Url(as: 'loc')]
    public $location = '';

    #[Url(as: 'b')]
    public $budget = '';

    #[Url(as: 'd')]
    public $date = '';

    public function resetFilters(): void
    {
        $this->reset(['location', 'budget', 'date']);
        $this->resetPage();
    }

    public function clearFilter(string $filter): void
    {
        if (! in_array($filter, ['location', 'budget', 'date'], true)) {
            return;
        }

        $this->$filter = '';
        $this->resetPage();
    }

    public function updating($property)
    {
        if (in_array($property, ['location', 'budget', 'date'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $query = PhotographerProfile::visible()
            ->with(['lowestActivePackage', 'coverPortfolioItem.media'])
            ->withAvg(['reviews' => fn ($q) => $q->whereNotNull('published_at')], 'rating')
            ->withCount(['reviews' => fn ($q) => $q->whereNotNull('published_at')]);

        if ($this->location) {
            // Using whereJsonContains for JSON array column 'coverage_areas'
            $query->whereJsonContains('coverage_areas', $this->location);
        }

        if ($this->budget) {
            $parts = explode('-', $this->budget.'-');
            $min = $parts[0];
            $max = $parts[1];

            $query->whereHas('packages', function ($q) use ($min, $max) {
                $q->where('is_active', true)->where('price_from', '>=', (int) $min);
                if ($max) {
                    $q->where('price_from', '<=', (int) $max);
                }
            });
        }

        if ($this->date) {
            $query->whereHas('availabilities', function ($q) {
                $q->where('date', $this->date)
                    ->where('status', AvailabilityStatus::Available);
            });
        }

        // Order by featured (active) first, then newest verified
        $query->orderByRaw('CASE WHEN featured_until IS NOT NULL AND featured_until > NOW() THEN 0 ELSE 1 END')
            ->orderBy('verified_at', 'desc');

        return view('livewire.browse-photographers', [
            'profiles' => $query->paginate(12),
        ])
            ->title('Cari Jurugambar - CariSnap')
            ->layoutData([
                'metaDescription' => 'Cari dan tempah jurugambar atau juruvideo perkahwinan terbaik di Malaysia. Tapis ikut lokasi, bajet, dan tarikh kekosongan.',
            ]);
    }
}
