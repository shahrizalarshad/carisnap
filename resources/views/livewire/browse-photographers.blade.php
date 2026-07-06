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
            <label class="block text-xs font-medium text-gray-700 mb-1">Kawasan</label>
            <select wire:model.live="location" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm">
                <option value="">Semua Kawasan</option>
                <option value="Kuala Lumpur">Kuala Lumpur</option>
                <option value="Selangor">Selangor</option>
                <option value="Putrajaya">Putrajaya</option>
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-gray-700 mb-1">Bajet</label>
            <select wire:model.live="budget" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm">
                <option value="">Semua Bajet</option>
                <option value="0-1000">Bawah RM1,000</option>
                <option value="1000-3000">RM1,000 - RM3,000</option>
                <option value="3000-">RM3,000 Ke atas</option>
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Majlis</label>
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
                <x-ui.button variant="ghost" wire:click="resetFilters" @click="$dispatch('close-sheet-filters')">Set Semula</x-ui.button>
                <x-ui.button variant="primary" @click="$dispatch('close-sheet-filters')">Lihat Hasil</x-ui.button>
            </div>
        </div>
    </x-ui.bottom-sheet>

    @php
        $budgetLabels = [
            '0-1000' => 'Bawah RM1,000',
            '1000-3000' => 'RM1,000 – RM3,000',
            '3000-' => 'RM3,000 ke atas',
        ];
        $hasActiveFilters = $location || $budget || $date;
    @endphp

    <!-- Active filter chips & result count -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        @if ($hasActiveFilters)
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-medium text-gray-500">Filter aktif:</span>
                @if ($location)
                    <button type="button" wire:click="clearFilter('location')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-brand-50 text-brand-700 border border-brand-200 hover:bg-brand-100 transition-colors">
                        {{ $location }}
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                @endif
                @if ($budget)
                    <button type="button" wire:click="clearFilter('budget')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-brand-50 text-brand-700 border border-brand-200 hover:bg-brand-100 transition-colors">
                        {{ $budgetLabels[$budget] ?? $budget }}
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                @endif
                @if ($date)
                    <button type="button" wire:click="clearFilter('date')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-brand-50 text-brand-700 border border-brand-200 hover:bg-brand-100 transition-colors">
                        {{ \Carbon\Carbon::parse($date)->locale('ms')->translatedFormat('j M Y') }}
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                @endif
                <button type="button" wire:click="resetFilters" class="text-xs font-medium text-gray-500 hover:text-brand-600 underline">Kosongkan semua</button>
            </div>
        @endif

        <p class="text-sm text-gray-500 sm:ml-auto" wire:loading.remove wire:target="location,budget,date">
            @if ($profiles->total() > 0)
                {{ $profiles->total() }} jurugambar dijumpai
            @else
                Tiada hasil
            @endif
        </p>
    </div>

    <!-- Skeleton loading -->
    <div wire:loading wire:target="location,budget,date" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
        @for ($i = 0; $i < 6; $i++)
            <x-ui.card class="overflow-hidden animate-pulse">
                <div class="h-56 bg-gray-200"></div>
                <div class="p-4 space-y-3">
                    <div class="h-5 bg-gray-200 rounded w-3/4"></div>
                    <div class="h-4 bg-gray-100 rounded w-1/2"></div>
                    <div class="pt-3 border-t border-gray-100 flex justify-between">
                        <div class="h-4 bg-gray-100 rounded w-20"></div>
                        <div class="h-4 bg-gray-200 rounded w-24"></div>
                    </div>
                </div>
            </x-ui.card>
        @endfor
    </div>

    <!-- Results Grid -->
    <div class="transition-opacity duration-200" wire:loading.class="opacity-0 pointer-events-none" wire:target="location,budget,date">
        @if ($profiles->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($profiles as $profile)
                    <x-ui.photographer-card :profile="$profile" />
                @endforeach
            </div>

            <div class="mt-8">
                {{ $profiles->links() }}
            </div>
        @else
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
