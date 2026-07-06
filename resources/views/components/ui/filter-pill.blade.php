@props(['active' => false])

@php
$classes = 'inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-colors cursor-pointer min-h-[44px] whitespace-nowrap ';
$classes .= $active 
    ? 'bg-brand-600 text-white shadow-sm' 
    : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50';
@endphp

<button type="button" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
