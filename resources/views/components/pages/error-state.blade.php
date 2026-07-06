@props([
    'code',
    'title',
    'message',
])

<div class="flex flex-col items-center justify-center text-center py-16 md:py-24 space-y-6">
    <p class="text-6xl md:text-7xl font-heading font-bold text-brand-600 leading-none">{{ $code }}</p>

    <div class="space-y-3 max-w-lg">
        <h1 class="text-2xl md:text-3xl font-heading font-bold text-gray-900">{{ $title }}</h1>
        <p class="text-gray-600 leading-relaxed">{{ $message }}</p>
    </div>

    <div class="flex flex-col sm:flex-row gap-3 pt-2">
        <x-ui.button href="{{ route('home') }}" size="lg">
            Kembali ke Laman Utama
        </x-ui.button>
        <x-ui.button href="{{ route('photographers.index') }}" variant="outline" size="lg">
            Cari Jurugambar
        </x-ui.button>
    </div>
</div>
