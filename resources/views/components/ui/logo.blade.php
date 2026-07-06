@props([
    'showText' => true,
    'size' => 'md', // sm, md, lg
])

@php
$iconSizes = [
    'sm' => 'w-7 h-7',
    'md' => 'w-8 h-8',
    'lg' => 'w-10 h-10',
];

$textSizes = [
    'sm' => 'text-lg',
    'md' => 'text-2xl',
    'lg' => 'text-3xl',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2']) }}>
    <svg
        class="{{ $iconSizes[$size] }} shrink-0 text-brand-600"
        viewBox="0 0 32 32"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true"
    >
        {{-- Viewfinder frame --}}
        <rect x="3" y="7" width="26" height="20" rx="5" stroke="currentColor" stroke-width="2.25"/>
        {{-- Lens --}}
        <circle cx="16" cy="17" r="6" stroke="currentColor" stroke-width="2.25"/>
        <circle cx="16" cy="17" r="2.75" fill="currentColor"/>
        {{-- Shutter accent (snap) --}}
        <path d="M23.5 3.5 L28.5 7.5 L23.5 11.5 Z" fill="currentColor"/>
    </svg>

    @if ($showText)
        <span class="font-heading font-bold {{ $textSizes[$size] }} tracking-tight text-gray-900">
            Cari<span class="text-brand-600">Snap</span><span class="text-brand-500">.</span>
        </span>
    @endif
</span>
