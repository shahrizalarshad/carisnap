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
        <x-ui.card class="p-6 text-center py-12">
            <p class="text-gray-500 mb-4">Belum ada permintaan tempahan lagi.</p>
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
