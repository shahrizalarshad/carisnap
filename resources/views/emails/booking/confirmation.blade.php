<x-mail::message>
# Permintaan Tempahan Berjaya Dihantar

Hai {{ $bookingRequest->guest_name ?? $bookingRequest->client?->name }},

Terima kasih kerana menggunakan CariSnap! Permintaan tempahan anda kepada **{{ $bookingRequest->profile->business_name }}** telah berjaya dihantar.

Jurugambar akan meneliti permintaan anda dan membalas dalam masa 24 jam. Anda akan dimaklumkan melalui e-mel atau WhatsApp apabila mereka memberi maklum balas.

**Ringkasan Permintaan:**
- **Tarikh:** {{ $bookingRequest->event_date->format('d M Y') }}
- **Lokasi:** {{ $bookingRequest->location }}

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
