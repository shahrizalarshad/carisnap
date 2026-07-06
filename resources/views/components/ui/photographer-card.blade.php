@props(['profile'])

<x-ui.card {{ $attributes->merge(['class' => 'flex flex-col group hover:shadow-md transition-shadow relative overflow-hidden']) }}>
    <a href="{{ route('photographers.show', $profile->slug) }}" wire:navigate class="block relative h-56 overflow-hidden bg-gray-100">
        @php
            $coverMedia = $profile->coverPortfolioItem?->getFirstMedia('portfolio');
        @endphp
        @if ($coverMedia)
            <img
                src="{{ $coverMedia->getUrl('display') }}"
                srcset="{{ $coverMedia->getUrl('thumbnail') }} 400w, {{ $coverMedia->getUrl('display') }} 1200w"
                sizes="(max-width: 640px) 100vw, 33vw"
                alt="{{ $profile->business_name }}"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                loading="lazy"
                decoding="async"
            >
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gradient-to-br from-brand-50 to-gray-50">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        @endif

        @if ($profile->featured_until && $profile->featured_until->isFuture())
            <div class="absolute top-3 left-3">
                <x-ui.badge color="brand" class="shadow-sm bg-white/90 backdrop-blur text-brand-700">
                    ✨ Pilihan Utama
                </x-ui.badge>
            </div>
        @endif
    </a>

    <div class="p-4 flex flex-col flex-grow">
        <a href="{{ route('photographers.show', $profile->slug) }}" wire:navigate class="font-heading font-semibold text-lg text-gray-900 group-hover:text-brand-600 transition-colors line-clamp-1 flex items-center gap-1">
            {{ $profile->business_name }}
            <svg class="w-4 h-4 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20" title="Disahkan"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        </a>

        <p class="text-sm text-gray-500 mt-1 flex items-center gap-1">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            {{ $profile->location_area }}
        </p>

        <div class="mt-auto pt-3 border-t border-gray-100 flex justify-between items-center mt-3">
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
