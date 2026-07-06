<x-layouts.public
    title="CariSnap — Cari Jurugambar Perkahwinan Klang Valley"
    metaDescription="Platform carian jurugambar & videografer perkahwinan di Lembah Klang. Tapis ikut lokasi, bajet & tarikh. Tempah terus, tanpa komisen."
>
    <div class="space-y-16 md:space-y-20 pb-8">
        {{-- Hero --}}
        <section class="text-center md:text-left">
            <div class="md:grid md:grid-cols-2 md:gap-12 md:items-center">
                <div class="space-y-6">
                    <x-ui.badge color="brand" class="inline-flex">Perkahwinan · Klang Valley</x-ui.badge>

                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-heading font-bold text-gray-900 leading-tight">
                        Cari jurugambar perkahwinan yang ngam dengan bajet & tarikh anda.
                    </h1>

                    <p class="text-base md:text-lg text-gray-600 leading-relaxed max-w-xl md:max-w-none mx-auto md:mx-0">
                        CariSnap hubungkan anda dengan jurugambar & videografer perkahwinan yang disahkan di Lembah Klang.
                        Tapis ikut lokasi, bajet, dan tarikh — kemudian hantar permohonan terus, tanpa komisen platform.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
                        <x-ui.button href="{{ route('photographers.index') }}" size="lg">
                            Mula Cari
                        </x-ui.button>
                        <x-ui.button href="{{ route('register.photographer') }}" variant="outline" size="lg">
                            Saya Jurugambar
                        </x-ui.button>
                    </div>
                </div>

                <div class="hidden md:block">
                    <x-ui.card class="p-8 bg-gradient-to-br from-brand-50 to-white border-brand-100">
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center text-brand-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-heading font-semibold text-gray-900">Cari & tapis</p>
                                    <p class="text-sm text-gray-500">Lokasi, bajet, tarikh majlis</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center text-brand-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-heading font-semibold text-gray-900">Hantar permohonan</p>
                                    <p class="text-sm text-gray-500">Guest pun boleh, tak perlu login</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center text-brand-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <p class="font-heading font-semibold text-gray-900">Confirm & shoot</p>
                                    <p class="text-sm text-gray-500">Terima sebut harga, deal terus via WhatsApp</p>
                                </div>
                            </div>
                        </div>
                    </x-ui.card>
                </div>
            </div>
        </section>

        {{-- Quick filters --}}
        <section class="space-y-4">
            <p class="text-sm font-medium text-gray-700 text-center md:text-left">Popular sekarang:</p>
            <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                <a
                    href="{{ route('photographers.index', ['loc' => 'Kuala Lumpur']) }}"
                    wire:navigate
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-colors min-h-[44px] whitespace-nowrap bg-white text-gray-700 border border-gray-200 hover:bg-gray-50"
                >
                    Kuala Lumpur
                </a>
                <a
                    href="{{ route('photographers.index', ['loc' => 'Selangor']) }}"
                    wire:navigate
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-colors min-h-[44px] whitespace-nowrap bg-white text-gray-700 border border-gray-200 hover:bg-gray-50"
                >
                    Selangor
                </a>
                <a
                    href="{{ route('photographers.index', ['b' => '1000-3000']) }}"
                    wire:navigate
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-colors min-h-[44px] whitespace-nowrap bg-white text-gray-700 border border-gray-200 hover:bg-gray-50"
                >
                    Bajet RM1K–3K
                </a>
            </div>
        </section>

        {{-- How it works (mobile-visible; desktop preview in hero) --}}
        <section class="space-y-6 md:hidden">
            <h2 class="text-2xl font-heading font-bold text-gray-900">Macam mana ia berfungsi?</h2>
            <div class="space-y-4">
                <x-ui.card class="p-5">
                    <div class="flex gap-4">
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-brand-600 text-white text-sm font-bold flex items-center justify-center">1</span>
                        <div>
                            <h3 class="font-heading font-semibold text-gray-900">Cari & tapis</h3>
                            <p class="text-sm text-gray-500 mt-1">Browse jurugambar ikut lokasi, bajet, dan tarikh majlis anda.</p>
                        </div>
                    </div>
                </x-ui.card>
                <x-ui.card class="p-5">
                    <div class="flex gap-4">
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-brand-600 text-white text-sm font-bold flex items-center justify-center">2</span>
                        <div>
                            <h3 class="font-heading font-semibold text-gray-900">Hantar permohonan</h3>
                            <p class="text-sm text-gray-500 mt-1">Isi borang tempahan — guest pun boleh, tak perlu daftar akaun dulu.</p>
                        </div>
                    </div>
                </x-ui.card>
                <x-ui.card class="p-5">
                    <div class="flex gap-4">
                        <span class="flex-shrink-0 w-8 h-8 rounded-full bg-brand-600 text-white text-sm font-bold flex items-center justify-center">3</span>
                        <div>
                            <h3 class="font-heading font-semibold text-gray-900">Confirm & shoot</h3>
                            <p class="text-sm text-gray-500 mt-1">Jurugambar hantar sebut harga, anda terima via link, then deal terus di WhatsApp.</p>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </section>

        {{-- How it works desktop --}}
        <section class="space-y-6 hidden md:block">
            <h2 class="text-2xl font-heading font-bold text-gray-900">Macam mana ia berfungsi?</h2>
            <div class="grid grid-cols-3 gap-6">
                <x-ui.card class="p-6">
                    <span class="w-8 h-8 rounded-full bg-brand-600 text-white text-sm font-bold flex items-center justify-center mb-4">1</span>
                    <h3 class="font-heading font-semibold text-gray-900">Cari & tapis</h3>
                    <p class="text-sm text-gray-500 mt-2">Browse jurugambar ikut lokasi, bajet, dan tarikh majlis anda.</p>
                </x-ui.card>
                <x-ui.card class="p-6">
                    <span class="w-8 h-8 rounded-full bg-brand-600 text-white text-sm font-bold flex items-center justify-center mb-4">2</span>
                    <h3 class="font-heading font-semibold text-gray-900">Hantar permohonan</h3>
                    <p class="text-sm text-gray-500 mt-2">Isi borang tempahan — guest pun boleh, tak perlu daftar akaun dulu.</p>
                </x-ui.card>
                <x-ui.card class="p-6">
                    <span class="w-8 h-8 rounded-full bg-brand-600 text-white text-sm font-bold flex items-center justify-center mb-4">3</span>
                    <h3 class="font-heading font-semibold text-gray-900">Confirm & shoot</h3>
                    <p class="text-sm text-gray-500 mt-2">Jurugambar hantar sebut harga, anda terima via link, then deal terus di WhatsApp.</p>
                </x-ui.card>
            </div>
        </section>

        {{-- Trust signals --}}
        <section class="space-y-6">
            <h2 class="text-2xl font-heading font-bold text-gray-900 text-center md:text-left">Kenapa CariSnap?</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <x-ui.card class="p-5 text-center sm:text-left">
                    <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center mx-auto sm:mx-0 mb-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    </div>
                    <h3 class="font-heading font-semibold text-gray-900">Jurugambar disahkan</h3>
                    <p class="text-sm text-gray-500 mt-1">Setiap profil dalam listing telah disemak admin sebelum diterbitkan.</p>
                </x-ui.card>
                <x-ui.card class="p-5 text-center sm:text-left">
                    <div class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center mx-auto sm:mx-0 mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-heading font-semibold text-gray-900">Tiada komisen platform</h3>
                    <p class="text-sm text-gray-500 mt-1">Bayar terus kepada jurugambar. Kami tak potong dari deal anda.</p>
                </x-ui.card>
                <x-ui.card class="p-5 text-center sm:text-left">
                    <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center mx-auto sm:mx-0 mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <h3 class="font-heading font-semibold text-gray-900">Klang Valley sahaja (MVP)</h3>
                    <p class="text-sm text-gray-500 mt-1">Fokus KL, Selangor & Putrajaya — kawasan yang kami cover buat masa ini.</p>
                </x-ui.card>
            </div>
        </section>

        {{-- Final CTA --}}
        <section class="rounded-2xl bg-gradient-to-r from-brand-600 to-brand-700 px-6 py-10 md:px-12 md:py-14 text-center text-white shadow-sm">
            <h2 class="text-2xl md:text-3xl font-heading font-bold">Ready untuk cari jurugambar?</h2>
            <p class="mt-3 text-brand-100 max-w-lg mx-auto">
                Ribuan pasangan di Lembah Klang mula dengan satu carian. Anda pun boleh.
            </p>
            <div class="mt-6">
                <x-ui.button
                    href="{{ route('photographers.index') }}"
                    size="lg"
                    class="!bg-white !text-brand-700 hover:!bg-brand-50 !shadow-md"
                >
                    Mula Cari
                </x-ui.button>
            </div>
        </section>
    </div>
</x-layouts.public>
