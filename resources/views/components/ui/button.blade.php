@props([
    'variant' => 'primary', // primary, secondary, outline, ghost
    'size' => 'md', // sm, md, lg
    'href' => null,
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500';

$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-sm min-h-[36px]',
    'md' => 'px-4 py-2 text-base min-h-[44px]',
    'lg' => 'px-6 py-3 text-lg min-h-[52px]',
];

$variantClasses = [
    'primary' => 'bg-brand-600 text-white hover:bg-brand-700 shadow-sm',
    'secondary' => 'bg-brand-50 text-brand-700 hover:bg-brand-100',
    'outline' => 'border border-gray-300 bg-transparent text-gray-700 hover:bg-gray-50',
    'ghost' => 'bg-transparent text-gray-700 hover:bg-gray-100',
];

$classes = $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $variantClasses[$variant] . ' ' . ($attributes->get('class') ?? '');
@endphp

@if($href)
    <a href="{{ $href }}" wire:navigate {{ $attributes->except('class')->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->except('class')->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
