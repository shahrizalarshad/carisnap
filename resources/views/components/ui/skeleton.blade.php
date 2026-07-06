@props(['type' => 'text'])

@php
$classes = 'animate-pulse bg-gray-200 ';
if ($type === 'text') $classes .= 'h-4 rounded w-full';
if ($type === 'title') $classes .= 'h-6 rounded w-3/4';
if ($type === 'image') $classes .= 'w-full h-48 rounded-xl';
if ($type === 'avatar') $classes .= 'h-12 w-12 rounded-full';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}></div>
