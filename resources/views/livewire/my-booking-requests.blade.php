<div class="space-y-6">
    <div class="border-b border-gray-100 pb-4">
        <h1 class="text-3xl font-heading font-bold text-gray-900">Tempahan Saya</h1>
        <p class="mt-2 text-sm text-gray-500">Semak status permintaan tempahan jurugambar anda.</p>
    </div>

    <div class="flex flex-wrap gap-2">
        <x-ui.filter-pill wire:click="$set('status', '')" :active="$status === ''">Semua</x-ui.filter-pill>
        @foreach ($statuses as $bookingStatus)
            <x-ui.filter-pill wire:click="$set('status', '{{ $bookingStatus->value }}')" :active="$status === $bookingStatus->value">
                {{ $bookingStatus->label() }}
            </x-ui.filter-pill>
        @endforeach
    </div>

    @if ($bookings->isEmpty())
        <x-ui.card class="p-8 text-center py-16 border-dashed">
            <div class="w-16 h-16 bg-brand-50 text-brand-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h2 class="text-lg font-heading font-semibold text-gray-900 mb-2">Belum ada tempahan lagi</h2>
            <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">
                @if ($status)
                    Tiada permintaan dengan status ini. Cuba tukar filter atau cari jurugambar baharu.
                @else
                    Mula dengan cari jurugambar, hantar permintaan, dan semak sebut harga di sini.
                @endif
            </p>
            <x-ui.button variant="primary" href="{{ route('photographers.index') }}" wire:navigate>
                Cari Jurugambar
            </x-ui.button>
        </x-ui.card>
    @else
        <div class="space-y-4">
            @foreach ($bookings as $booking)
                <x-ui.card class="p-6 hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-2 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-lg font-semibold text-gray-900 truncate">
                                    {{ $booking->profile->business_name }}
                                </h2>
                                <x-ui.badge :color="$booking->status->badgeColor()">
                                    {{ $booking->status->label() }}
                                </x-ui.badge>
                            </div>
                            <p class="text-sm text-gray-500">
                                {{ $booking->event_date->format('d/m/Y') }} · {{ $booking->location }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Bajet: RM{{ number_format($booking->budget_from) }} – RM{{ number_format($booking->budget_to) }}
                            </p>
                            @if ($booking->latestQuote)
                                <p class="text-sm font-medium text-brand-700">
                                    Sebut harga: RM{{ number_format($booking->latestQuote->amount) }}
                                </p>
                            @endif
                        </div>

                        <div class="flex flex-col sm:items-end gap-2 shrink-0">
                            <x-ui.button variant="outline" href="{{ route('bookings.show', $booking) }}" wire:navigate>
                                Lihat Butiran
                            </x-ui.button>
                            @if ($booking->status === \App\Enums\BookingStatus::Quoted && $booking->latestQuote)
                                <a
                                    href="{{ URL::signedRoute('quotes.show', $booking->latestQuote) }}"
                                    class="text-sm font-medium text-brand-600 hover:text-brand-700"
                                >
                                    Balas Sebut Harga →
                                </a>
                            @endif
                        </div>
                    </div>
                </x-ui.card>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
