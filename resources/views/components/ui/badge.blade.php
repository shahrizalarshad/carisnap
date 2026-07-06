@props(['color' => 'gray'])

@php
$colorClasses = [
    'gray' => 'bg-gray-100 text-gray-700',
    'brand' => 'bg-brand-100 text-brand-700',
    'green' => 'bg-green-100 text-green-700',
    'red' => 'bg-red-100 text-red-700',
    'yellow' => 'bg-yellow-100 text-yellow-700',
];
$classes = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $colorClasses[$color];
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
