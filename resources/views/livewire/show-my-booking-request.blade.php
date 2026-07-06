<div class="space-y-6 max-w-3xl mx-auto">
    <div>
        <a href="{{ route('bookings.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-brand-600 transition-colors">
            ← Kembali ke Tempahan Saya
        </a>
        <div class="mt-3 flex flex-wrap items-center gap-3">
            <h1 class="text-2xl font-heading font-bold text-gray-900">Butiran Tempahan</h1>
            <x-ui.badge :color="$bookingRequest->status->badgeColor()">
                {{ $bookingRequest->status->label() }}
            </x-ui.badge>
        </div>
    </div>

    <x-ui.card class="p-6">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Status Tempahan</h2>
        <x-ui.booking-timeline :status="$bookingRequest->status" />
        @if (in_array($bookingRequest->status, [\App\Enums\BookingStatus::Declined, \App\Enums\BookingStatus::Expired]))
            <p class="mt-4 text-sm text-gray-500">
                @if ($bookingRequest->status === \App\Enums\BookingStatus::Declined)
                    Permintaan ini telah ditolak oleh jurugambar. Anda boleh cari jurugambar lain.
                @else
                    Permintaan ini telah tamat tempoh. Cuba hantar permintaan baharu.
                @endif
            </p>
        @endif
    </x-ui.card>

    <x-ui.card class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Jurugambar</h2>
        <div class="space-y-3">
            <p class="font-medium text-gray-900">{{ $bookingRequest->profile->business_name }}</p>
            <a href="{{ route('photographers.show', $bookingRequest->profile->slug) }}" wire:navigate class="text-sm text-brand-600 hover:text-brand-700">
                Lihat profil jurugambar →
            </a>
        </div>
    </x-ui.card>

    <x-ui.card class="p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Maklumat Majlis</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">Tarikh</dt>
                <dd class="font-medium text-gray-900">{{ $bookingRequest->event_date->format('d/m/Y') }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Lokasi</dt>
                <dd class="font-medium text-gray-900">{{ $bookingRequest->location }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Jenis Acara</dt>
                <dd class="font-medium text-gray-900 capitalize">{{ $bookingRequest->event_type->value }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Bajet</dt>
                <dd class="font-medium text-gray-900">
                    RM{{ number_format($bookingRequest->budget_from) }} – RM{{ number_format($bookingRequest->budget_to) }}
                </dd>
            </div>
            @if ($bookingRequest->package)
                <div class="sm:col-span-2">
                    <dt class="text-gray-500">Pakej</dt>
                    <dd class="font-medium text-gray-900">{{ $bookingRequest->package->name }}</dd>
                </div>
            @endif
            @if ($bookingRequest->message)
                <div class="sm:col-span-2">
                    <dt class="text-gray-500">Mesej Anda</dt>
                    <dd class="font-medium text-gray-900 whitespace-pre-wrap">{{ $bookingRequest->message }}</dd>
                </div>
            @endif
        </dl>
    </x-ui.card>

    @if ($bookingRequest->latestQuote)
        <x-ui.card class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Sebut Harga Terkini</h2>
            <div class="bg-brand-50 rounded-xl p-5 border border-brand-100 space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-brand-600">Jumlah</p>
                        <p class="text-3xl font-bold text-gray-900">RM{{ number_format($bookingRequest->latestQuote->amount) }}</p>
                    </div>
                    <div class="text-sm">
                        <p class="text-gray-500">Sah sehingga</p>
                        <p class="font-medium {{ now()->toDateString() > $bookingRequest->latestQuote->valid_until->toDateString() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $bookingRequest->latestQuote->valid_until->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
                @if ($bookingRequest->latestQuote->message)
                    <p class="text-sm text-gray-700 whitespace-pre-wrap italic border-t border-brand-100 pt-3">
                        "{{ $bookingRequest->latestQuote->message }}"
                    </p>
                @endif
            </div>

            @if ($bookingRequest->status === \App\Enums\BookingStatus::Quoted)
                <div class="mt-4">
                    <x-ui.button variant="primary" :wire-navigate="false" href="{{ URL::signedRoute('quotes.show', $bookingRequest->latestQuote) }}">
                        Semak & Balas Sebut Harga
                    </x-ui.button>
                </div>
            @endif
        </x-ui.card>
    @endif

    @if ($bookingRequest->status === \App\Enums\BookingStatus::Accepted)
        <x-ui.card class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Seterusnya</h2>
            <p class="text-sm text-gray-600 mb-4">
                Tempahan anda telah disahkan. Hubungi jurugambar melalui WhatsApp untuk bincang lanjut.
            </p>
            <a
                href="{{ $bookingRequest->profile->whatsapp_url }}"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center justify-center font-medium rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#25D366] px-4 py-2 text-base min-h-[44px] bg-[#25D366] text-white hover:bg-[#128C7E] shadow-sm"
            >
                WhatsApp Jurugambar
            </a>
        </x-ui.card>
    @endif

    @if ($bookingRequest->status === \App\Enums\BookingStatus::Pending)
        <x-ui.card class="p-6 bg-yellow-50 border-yellow-100">
            <p class="text-sm text-yellow-800">
                Permintaan anda sedang menunggu respons daripada jurugambar. Kami akan maklumkan melalui e-mel bila ada sebut harga.
            </p>
        </x-ui.card>
    @endif
</div>
