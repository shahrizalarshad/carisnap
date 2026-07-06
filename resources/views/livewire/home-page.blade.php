<div class="space-y-12 md:space-y-16 pb-12 md:pb-16">
    {{-- Hero --}}
    <section>
        <div class="md:grid md:grid-cols-2 md:gap-12 lg:gap-16 md:items-center">
            <div class="space-y-6 text-center md:text-left">
                <x-ui.badge color="brand" class="inline-flex">Perkahwinan · Klang Valley</x-ui.badge>

                <h1 class="text-3xl md:text-4xl lg:text-5xl font-heading font-bold text-gray-900 leading-tight">
                    Cari jurugambar perkahwinan yang ngam dengan bajet & tarikh anda.
                </h1>

                <p class="text-base md:text-lg text-gray-600 leading-relaxed max-w-xl md:max-w-none mx-auto md:mx-0">
                    CariSnap hubungkan anda dengan jurugambar & videografer perkahwinan yang disahkan di Lembah Klang.
                    Tapis ikut lokasi, bajet, dan tarikh — kemudian hantar permohonan terus, tanpa komisen platform.
                </p>

                @if ($verifiedCount > 0)
                    <p class="text-sm font-medium text-brand-700 bg-brand-50 inline-flex px-4 py-2 rounded-full">
                        {{ $verifiedCount }}+ studio disahkan · Klang Valley
                    </p>
                @endif

                <div class="flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
                    <x-ui.button href="{{ route('photographers.index') }}" size="lg">
                        Mula Cari
                    </x-ui.button>
                    <x-ui.button href="{{ route('register.photographer') }}" variant="outline" size="lg">
                        Saya Jurugambar
                    </x-ui.button>
                </div>

                <div class="pt-6 border-t border-gray-100 space-y-3">
                    <p class="text-sm font-medium text-gray-700">Popular sekarang:</p>
                    <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                        <a href="{{ route('photographers.index', ['loc' => 'Kuala Lumpur']) }}" wire:navigate class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-colors min-h-[44px] whitespace-nowrap bg-white text-gray-700 border border-gray-200 hover:bg-gray-50">Kuala Lumpur</a>
                        <a href="{{ route('photographers.index', ['loc' => 'Selangor']) }}" wire:navigate class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-colors min-h-[44px] whitespace-nowrap bg-white text-gray-700 border border-gray-200 hover:bg-gray-50">Selangor</a>
                        <a href="{{ route('photographers.index', ['b' => '1000-3000']) }}" wire:navigate class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-colors min-h-[44px] whitespace-nowrap bg-white text-gray-700 border border-gray-200 hover:bg-gray-50">Bajet RM1K–3K</a>
                    </div>
                </div>
            </div>

            {{-- Hero visual: portfolio mosaic --}}
            <div class="hidden md:block">
                @if ($heroImages->isNotEmpty())
                    <div class="grid grid-cols-2 gap-3 rounded-2xl overflow-hidden shadow-lg">
                        @foreach ($heroImages as $index => $media)
                            <div @class([
                                'relative overflow-hidden bg-gray-100',
                                'col-span-2 h-48' => $index === 0,
                                'h-36' => $index > 0,
                            ])>
                                <img
                                    src="{{ $media->getUrl('display') }}"
                                    alt="Portfolio jurugambar perkahwinan"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                >
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-ui.card class="p-8 bg-gradient-to-br from-brand-50 to-white border-brand-100 h-full flex flex-col justify-center">
                        <p class="text-sm font-semibold text-brand-700 uppercase tracking-wider mb-4">Macam mana ia berfungsi</p>
                        <div class="space-y-4 text-sm text-gray-600">
                            <p>① Cari & tapis ikut lokasi, bajet, tarikh</p>
                            <p>② Hantar permohonan (guest pun boleh)</p>
                            <p>③ Terima sebut harga & deal via WhatsApp</p>
                        </div>
                    </x-ui.card>
                @endif
            </div>
        </div>
    </section>

    {{-- Featured photographers --}}
    @if ($featuredProfiles->isNotEmpty())
        <section class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
                <div>
                    <h2 class="text-2xl font-heading font-bold text-gray-900">Studio Pilihan</h2>
                    <p class="mt-1 text-sm text-gray-500">Jurugambar disahkan dengan portfolio & pakej aktif.</p>
                </div>
                <a href="{{ route('photographers.index') }}" wire:navigate class="text-sm font-medium text-brand-600 hover:text-brand-700">
                    Lihat semua →
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($featuredProfiles as $profile)
                    <x-ui.photographer-card :profile="$profile" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- How it works — mobile --}}
    <section class="space-y-4 md:hidden">
        <h2 class="text-2xl font-heading font-bold text-gray-900">Macam mana ia berfungsi?</h2>
        <div class="space-y-3">
            @foreach ([
                ['1', 'Cari & tapis', 'Cari jurugambar ikut lokasi, bajet, dan tarikh majlis anda.'],
                ['2', 'Hantar permohonan', 'Isi borang tempahan — guest pun boleh, tak perlu daftar akaun dulu.'],
                ['3', 'Terima quote & deal', 'Jurugambar hantar sebut harga, anda terima via link, then deal terus di WhatsApp.'],
            ] as [$num, $title, $desc])
                <x-ui.card class="p-5">
                    <div class="flex gap-4">
                        <span class="shrink-0 w-8 h-8 rounded-full bg-brand-600 text-white text-sm font-bold flex items-center justify-center">{{ $num }}</span>
                        <div>
                            <h3 class="font-heading font-semibold text-gray-900">{{ $title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $desc }}</p>
                        </div>
                    </div>
                </x-ui.card>
            @endforeach
        </div>
    </section>

    {{-- Testimonials --}}
    @if ($testimonials->isNotEmpty())
        <section class="space-y-6">
            <h2 class="text-2xl font-heading font-bold text-gray-900 text-center md:text-left">Apa Kata Pelanggan</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($testimonials as $review)
                    <x-ui.card class="p-5 flex flex-col">
                        <x-ui.star-rating :rating="$review->rating" />
                        @if ($review->comment)
                            <p class="text-sm text-gray-600 mt-3 flex-grow italic">"{{ \Illuminate\Support\Str::limit($review->comment, 120) }}"</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-4 pt-3 border-t border-gray-100">
                            — {{ $review->bookingRequest->guest_name ?? $review->bookingRequest->client?->name ?? 'Pelanggan' }},
                            {{ $review->bookingRequest->profile->business_name }}
                        </p>
                    </x-ui.card>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Trust signals --}}
    <section class="space-y-6">
        <h2 class="text-2xl font-heading font-bold text-gray-900 text-center md:text-left">Kenapa CariSnap?</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
            <x-ui.card class="p-5 md:p-6 text-center sm:text-left">
                <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center mx-auto sm:mx-0 mb-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                </div>
                <h3 class="font-heading font-semibold text-gray-900">Jurugambar disahkan</h3>
                <p class="text-sm text-gray-500 mt-2">Setiap profil dalam listing telah disemak admin sebelum diterbitkan.</p>
            </x-ui.card>
            <x-ui.card class="p-5 md:p-6 text-center sm:text-left">
                <div class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center mx-auto sm:mx-0 mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="font-heading font-semibold text-gray-900">Tiada komisen platform</h3>
                <p class="text-sm text-gray-500 mt-2">Bayar terus kepada jurugambar. Kami tak potong dari deal anda.</p>
            </x-ui.card>
            <x-ui.card class="p-5 md:p-6 text-center sm:text-left">
                <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center mx-auto sm:mx-0 mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <h3 class="font-heading font-semibold text-gray-900">Klang Valley sahaja (MVP)</h3>
                <p class="text-sm text-gray-500 mt-2">Fokus KL, Selangor & Putrajaya — kawasan yang kami cover buat masa ini.</p>
            </x-ui.card>
        </div>
    </section>
</div>
