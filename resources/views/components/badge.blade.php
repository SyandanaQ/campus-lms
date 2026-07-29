@props(['color' => 'gray'])

@php
$variants = [
    'gray' => 'bg-gray-100 text-gray-700',
    'green' => 'bg-green-100 text-green-700',
    'red' => 'bg-red-100 text-red-700',
    'blue' => 'bg-blue-100 text-blue-700',
    'amber' => 'bg-amber-100 text-amber-700',
];
$classes = $variants[$color] ?? $variants['gray'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {$classes}"]) }}>
    {{ $slot }}
</span>