

@push('meta')
    <script type="application/ld+json">
        {!! json_encode(array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'ProfessionalService',
            'name' => $profile->business_name,
            'description' => \Illuminate\Support\Str::limit($profile->bio, 200),
            'url' => route('photographers.show', $profile->slug),
            'areaServed' => $profile->coverage_areas,
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $profile->location_area,
                'addressCountry' => 'MY',
            ],
            'aggregateRating' => $profile->reviews_count > 0 ? [
                '@type' => 'AggregateRating',
                'ratingValue' => round($profile->reviews_avg_rating, 1),
                'reviewCount' => $profile->reviews_count,
            ] : null,
        ], fn ($value) => $value !== null), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        .swiper-pagination-bullet-active { background-color: #d946ef !important; }
    </style>
@endpush

@push('head-scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('gallery', () => ({
                init() {
                    new Swiper(this.$refs.swiper, {
                        slidesPerView: 1,
                        spaceBetween: 10,
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                        breakpoints: {
                            640: { slidesPerView: 2, spaceBetween: 20 },
                            1024: { slidesPerView: 3, spaceBetween: 30 },
                        },
                    });
                },
            }));
        });
    </script>
@endpush

<div>
    <div class="space-y-8 pb-24 sm:pb-8">
    <!-- Hero / Gallery -->
    @if($profile->portfolioItems->count() > 0)
        <div class="relative -mx-4 sm:mx-0 sm:rounded-2xl overflow-hidden bg-gray-100" x-data="gallery">
            <div class="swiper h-64 sm:h-96" x-ref="swiper">
                <div class="swiper-wrapper">
                    @foreach($profile->portfolioItems as $item)
                        @php $media = $item->getFirstMedia('portfolio'); @endphp
                        @if($media)
                            <div class="swiper-slide h-full bg-gray-200">
                                <img
                                    src="{{ $media->getUrl('display') }}"
                                    srcset="{{ $media->getUrl('thumbnail') }} 400w, {{ $media->getUrl('display') }} 1200w"
                                    sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                                    alt="{{ $item->caption ?? $profile->business_name }}"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
            
            @if ($profile->featured_until && $profile->featured_until->isFuture())
                <div class="absolute top-4 left-4 z-10">
                    <x-ui.badge color="brand" class="shadow-md bg-white/90 backdrop-blur text-brand-700 py-1 px-3">
                        ✨ Pilihan Utama
                    </x-ui.badge>
                </div>
            @endif
        </div>
    @endif

    <!-- Profile Header -->
    <div class="space-y-4">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-heading font-bold text-gray-900 flex items-center gap-2">
                    {{ $profile->business_name }}
                    <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20" title="Verified"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                </h1>
                
                <p class="text-gray-500 mt-1 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ $profile->location_area }}
                    @if($profile->coverage_areas && count($profile->coverage_areas) > 0)
                        &bull; {{ implode(', ', $profile->coverage_areas) }}
                    @endif
                </p>
            </div>
            
            <div class="hidden sm:flex items-center gap-3">
                <a href="{{ $profile->whatsapp_url }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-5 py-3 text-sm font-medium text-white bg-[#25D366] hover:bg-[#128C7E] rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#25D366]">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp
                </a>
                <x-ui.button variant="primary" size="lg" x-data x-on:click="$dispatch('open-booking-modal')">
                    Hantar Permintaan
                    @if ($profile->packages->isNotEmpty())
                        <span class="font-normal opacity-90">· Dari RM{{ number_format($profile->packages->min('price_from')) }}</span>
                    @endif
                </x-ui.button>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="text-lg font-bold text-gray-900">{{ number_format($profile->reviews_avg_rating ?? 0, 1) }}</span>
                <x-ui.star-rating :rating="$profile->reviews_avg_rating ?? 0" />
            </div>
            <span class="text-gray-400">|</span>
            <span class="text-gray-600 font-medium">{{ $profile->reviews_count }} ulasan</span>
            @if($profile->instagram_handle)
                <span class="text-gray-400">|</span>
                <a href="https://instagram.com/{{ ltrim($profile->instagram_handle, '@') }}" target="_blank" class="text-brand-600 hover:text-brand-700 font-medium">{{ '@' . ltrim($profile->instagram_handle, '@') }}</a>
            @endif
        </div>
    </div>

    <!-- Bio -->
    <div class="prose prose-sm sm:prose-base prose-gray max-w-none bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <h2 class="text-xl font-heading font-semibold text-gray-900 mb-3">Tentang Kami</h2>
        <p class="whitespace-pre-line text-gray-600">{{ $profile->bio }}</p>
    </div>

    <!-- Packages -->
    <div class="space-y-4">
        <h2 class="text-2xl font-heading font-semibold text-gray-900 border-l-4 border-brand-500 pl-3">Pakej Ditawarkan</h2>
        @if($profile->packages->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($profile->packages as $package)
                    <x-ui.card class="p-5 hover:border-brand-300 transition-colors">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-heading font-bold text-lg text-gray-900">{{ $package->name }}</h3>
                                <p class="text-brand-600 font-semibold mt-1">RM {{ number_format($package->price_from) }}</p>
                            </div>
                            <x-ui.badge color="gray">{{ $package->duration_hours }} Jam</x-ui.badge>
                        </div>
                        <div class="text-sm text-gray-600">
                            <h4 class="font-medium text-gray-900 mb-2">Termasuk:</h4>
                            <p class="whitespace-pre-line">{{ $package->deliverables }}</p>
                        </div>
                    </x-ui.card>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 italic">Tiada pakej aktif pada masa ini.</p>
        @endif
    </div>

    <!-- Availability -->
    <div class="space-y-4">
        <h2 class="text-2xl font-heading font-semibold text-gray-900 border-l-4 border-brand-500 pl-3">Jadual Kekosongan (3 Bulan Akan Datang)</h2>
        @if($profile->availabilities->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @php
                    // Group availabilities by Year-Month
                    $groupedAvailabilities = $profile->availabilities->groupBy(function ($a) {
                        return $a->date->locale('ms')->translatedFormat('F Y');
                    });
                @endphp
                
                @foreach($groupedAvailabilities as $month => $dates)
                    <x-ui.card class="p-5">
                        <h3 class="font-heading font-bold text-gray-900 mb-3 border-b border-gray-100 pb-2">{{ $month }}</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($dates as $avail)
                                <x-ui.badge color="green" class="text-sm px-3 py-1">
                                    {{ $avail->date->locale('ms')->translatedFormat('j M') }}
                                </x-ui.badge>
                            @endforeach
                        </div>
                    </x-ui.card>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 italic">Tiada tarikh kosong disetkan untuk 3 bulan akan datang.</p>
        @endif
    </div>

    <!-- Reviews -->
    <div class="space-y-4">
        <h2 class="text-2xl font-heading font-semibold text-gray-900 border-l-4 border-brand-500 pl-3">Ulasan Pelanggan</h2>
        @if($profile->reviews->count() > 0)
            <div class="space-y-4">
                @foreach($profile->reviews as $review)
                    <x-ui.card class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="font-semibold text-gray-900">
                                    {{ $review->bookingRequest->guest_name ?? $review->bookingRequest->client?->name ?? 'Pelanggan' }}
                                </h4>
                                <p class="text-xs text-gray-400">{{ $review->published_at->format('d M Y') }}</p>
                            </div>
                            <x-ui.star-rating :rating="$review->rating" />
                        </div>
                        @if($review->comment)
                            <p class="text-gray-600 text-sm mt-3">{{ $review->comment }}</p>
                        @endif
                    </x-ui.card>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 italic">Belum ada ulasan diterbitkan.</p>
        @endif
    </div>
</div>

    <!-- Mobile Sticky Bottom CTA -->
    <div class="sm:hidden fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-200 z-40 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <div class="flex gap-2">
            <a href="{{ $profile->whatsapp_url }}" target="_blank" class="flex items-center justify-center p-3 text-[#25D366] border-2 border-[#25D366] hover:bg-[#25D366] hover:text-white rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </a>
            <x-ui.button variant="primary" class="w-full" size="lg" x-data x-on:click="$dispatch('open-booking-modal')">
                @if ($profile->packages->isNotEmpty())
                    Hantar Permintaan · RM{{ number_format($profile->packages->min('price_from')) }}+
                @else
                    Hantar Permintaan
                @endif
            </x-ui.button>
        </div>
    </div>

    <!-- Booking Request Modal -->
    <livewire:create-booking-request :profile="$profile" />
</div>
