@php($profile = $this->getProfile())

<x-filament-widgets::widget>
    <x-filament::section>
        @if (! $profile)
            <p class="text-sm text-gray-600">Profil belum dicipta. Sila lengkapkan onboarding terlebih dahulu.</p>
        @elseif (is_null($profile->verified_at))
            <div class="flex items-start gap-3">
                <x-filament::icon icon="heroicon-o-clock" class="h-6 w-6 text-warning-500 shrink-0" />
                <div>
                    <p class="font-semibold text-gray-950">Profil sedang menunggu semakan</p>
                    <p class="mt-1 text-sm text-gray-600">
                        Pasukan CariSnap akan semak profil <strong>{{ $profile->business_name }}</strong> tidak lama lagi.
                        Sementara itu, anda boleh tambah portfolio, pakej, dan tarikh kekosongan.
                    </p>
                </div>
            </div>
        @else
            <div class="flex items-start gap-3">
                <x-filament::icon icon="heroicon-o-check-badge" class="h-6 w-6 text-success-500 shrink-0" />
                <div>
                    <p class="font-semibold text-gray-950">Profil anda telah disahkan</p>
                    <p class="mt-1 text-sm text-gray-600">
                        <a href="{{ route('photographers.show', $profile->slug) }}" target="_blank" class="text-primary-600 hover:underline">
                            Lihat profil public →
                        </a>
                    </p>
                </div>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
