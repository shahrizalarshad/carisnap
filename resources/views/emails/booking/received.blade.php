<x-mail::message>
# Permintaan Tempahan Baru

Satu permintaan tempahan baru telah diterima daripada **{{ $bookingRequest->guest_name ?? $bookingRequest->client?->name }}**.

**Butiran Permintaan:**
- **Tarikh:** {{ $bookingRequest->event_date->format('d M Y') }}
- **Lokasi:** {{ $bookingRequest->location }}
- **Bajet:** RM {{ number_format($bookingRequest->budget_from) }} - RM {{ number_format($bookingRequest->budget_to) }}
- **No. Telefon:** {{ $bookingRequest->guest_phone ?? $bookingRequest->client?->phone }}

@if($bookingRequest->message)
**Mesej Tambahan:**
> {{ $bookingRequest->message }}
@endif

Sila log masuk ke panel anda untuk menghantar sebut harga (quote) atau menolak permintaan ini.

<x-mail::button :url="url('/photographer/booking-requests/' . $bookingRequest->id)">
Lihat Permintaan
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
