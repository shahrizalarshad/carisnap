<div class="space-y-6">
    <!-- Header & Mobile Filter Trigger -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-gray-100 pb-4">
        <div>
            <h1 class="text-3xl font-heading font-bold text-gray-900">Cari Jurugambar</h1>
            <p class="mt-2 text-sm text-gray-500">Temui jurugambar dan videografer terbaik untuk majlis anda.</p>
        </div>
        
        <!-- Mobile Filter Button -->
        <div class="sm:hidden" x-data>
            <x-ui.button variant="outline" class="w-full" @click="$dispatch('open-sheet-filters')">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Tapis Carian
            </x-ui.button>
        </div>
    </div>

    <!-- Desktop Filters (Hidden on Mobile) -->
    <div class="hidden sm:flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-gray-700 mb-1">Kawasan (Location)</label>
            <select wire:model.live="location" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm">
                <option value="">Semua Kawasan</option>
                <option value="Kuala Lumpur">Kuala Lumpur</option>
                <option value="Selangor">Selangor</option>
                <option value="Putrajaya">Putrajaya</option>
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-gray-700 mb-1">Bajet (Budget)</label>
            <select wire:model.live="budget" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm">
                <option value="">Semua Bajet</option>
                <option value="0-1000">Bawah RM1,000</option>
                <option value="1000-3000">RM1,000 - RM3,000</option>
                <option value="3000-">RM3,000 Ke atas</option>
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Majlis (Date)</label>
            <input type="date" wire:model.live="date" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm">
        </div>
    </div>

    <!-- Mobile Bottom Sheet Filters -->
    <x-ui.bottom-sheet id="filters" title="Tapis Carian">
        <div class="space-y-6">
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">Kawasan</h4>
                <div class="flex flex-wrap gap-2">
                    <x-ui.filter-pill wire:click="$set('location', '')" :active="$location === ''">Semua</x-ui.filter-pill>
                    <x-ui.filter-pill wire:click="$set('location', 'Kuala Lumpur')" :active="$location === 'Kuala Lumpur'">Kuala Lumpur</x-ui.filter-pill>
                    <x-ui.filter-pill wire:click="$set('location', 'Selangor')" :active="$location === 'Selangor'">Selangor</x-ui.filter-pill>
                    <x-ui.filter-pill wire:click="$set('location', 'Putrajaya')" :active="$location === 'Putrajaya'">Putrajaya</x-ui.filter-pill>
                </div>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">Bajet</h4>
                <div class="flex flex-wrap gap-2">
                    <x-ui.filter-pill wire:click="$set('budget', '')" :active="$budget === ''">Semua</x-ui.filter-pill>
                    <x-ui.filter-pill wire:click="$set('budget', '0-1000')" :active="$budget === '0-1000'">Bawah RM1K</x-ui.filter-pill>
                    <x-ui.filter-pill wire:click="$set('budget', '1000-3000')" :active="$budget === '1000-3000'">RM1K - RM3K</x-ui.filter-pill>
                    <x-ui.filter-pill wire:click="$set('budget', '3000-')" :active="$budget === '3000-'">RM3K+</x-ui.filter-pill>
                </div>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-900 mb-3">Tarikh Majlis</h4>
                <input type="date" wire:model.live="date" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm">
            </div>
            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3" x-data>
                <x-ui.button variant="ghost" wire:click="resetFilters" @click="$dispatch('close-sheet-filters')">Reset</x-ui.button>
                <x-ui.button variant="primary" @click="$dispatch('close-sheet-filters')">Lihat Hasil</x-ui.button>
            </div>
        </div>
    </x-ui.bottom-sheet>

    <!-- Loading State (Overlay or hidden) -->
    <div class="hidden justify-center py-12 w-full absolute z-10">
        <svg class="animate-spin h-8 w-8 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
    </div>

    <!-- Results Grid -->
    <div class="transition-opacity duration-200 mt-8">
        @if ($profiles->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($profiles as $profile)
                    <x-ui.card class="flex flex-col group hover:shadow-md transition-shadow relative">
                        <!-- Cover Image -->
                        <a href="/{{ $profile->slug }}" wire:navigate class="block relative h-56 overflow-hidden bg-gray-100">
                            @php
                                $coverMedia = $profile->coverPortfolioItem?->getFirstMedia('portfolio');
                            @endphp
                            @if ($coverMedia)
                                <img src="{{ $coverMedia->getUrl('display') }}" alt="{{ $profile->business_name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-50">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif

                            <!-- Featured Badge -->
                            @if ($profile->featured_until && $profile->featured_until->isFuture())
                                <div class="absolute top-3 left-3">
                                    <x-ui.badge color="brand" class="shadow-sm bg-white/90 backdrop-blur text-brand-700">
                                        ✨ Featured
                                    </x-ui.badge>
                                </div>
                            @endif
                        </a>

                        <!-- Card Body -->
                        <div class="p-4 flex flex-col flex-grow">
                            <div class="flex justify-between items-start mb-1">
                                <a href="/{{ $profile->slug }}" wire:navigate class="font-heading font-semibold text-lg text-gray-900 group-hover:text-brand-600 transition-colors line-clamp-1 flex items-center gap-1">
                                    {{ $profile->business_name }}
                                    <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" title="Verified"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </a>
                            </div>
                            
                            <p class="text-sm text-gray-500 mb-3 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $profile->location_area }}
                            </p>
                            
                            <div class="mt-auto pt-3 border-t border-gray-100 flex justify-between items-center">
                                <x-ui.star-rating :rating="$profile->reviews_avg_rating ?? 0" :count="$profile->reviews_count" />
                                
                                @if ($profile->lowestActivePackage)
                                    <span class="text-sm font-semibold text-gray-900">
                                        Dari RM{{ number_format($profile->lowestActivePackage->price_from) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">Tiada pakej</span>
                                @endif
                            </div>
                        </div>
                    </x-ui.card>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $profiles->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16 px-4 bg-white rounded-2xl border border-gray-100 border-dashed">
                <div class="w-16 h-16 bg-brand-50 text-brand-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-heading font-semibold text-gray-900 mb-1">Alamak, tiada hasil carian!</h3>
                <p class="text-gray-500 text-sm mb-6 max-w-md mx-auto">Kami tak dapat cari mana-mana jurugambar yang menepati kriteria carian anda. Cuba ubah atau buang filter untuk lihat lebih banyak pilihan.</p>
                <x-ui.button variant="primary" wire:click="resetFilters">
                    Reset Carian
                </x-ui.button>
            </div>
        @endif
    </div>
</div>
