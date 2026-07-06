@props(['title', 'updatedAt' => 'Julai 2026'])

<div class="max-w-3xl mx-auto space-y-8 pb-12">
    <header class="border-b border-gray-200 pb-6">
        <h1 class="text-3xl font-heading font-bold text-gray-900">{{ $title }}</h1>
        <p class="mt-2 text-sm text-gray-500">Dikemaskini: {{ $updatedAt }}</p>
    </header>

    <div class="space-y-6 text-gray-700 text-sm leading-relaxed">
        {{ $slot }}
    </div>
</div>
