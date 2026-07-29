@props(['variant' => 'primary', 'type' => 'submit', 'href' => null, 'size' => 'md', 'icon' => null])

@php
$variants = [
    'primary' => 'bg-blue-700 hover:bg-blue-800 text-white focus:ring-blue-300 border border-transparent',
    'secondary' => 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 focus:ring-gray-200',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-300 border border-transparent',
    'outline' => 'bg-white hover:bg-blue-50 text-blue-700 border border-blue-200 focus:ring-blue-200',
    'outline-danger' => 'bg-white hover:bg-red-50 text-red-600 border border-red-200 focus:ring-red-200',
];
$sizes = [
    'sm' => 'px-3 py-1.5 text-sm gap-1.5',
    'md' => 'px-5 py-2.5 text-base gap-2',
];
$iconSizes = [
    'sm' => 'w-4 h-4',
    'md' => 'w-5 h-5',
];
$classes = $variants[$variant] ?? $variants['primary'];
$sizeClasses = $sizes[$size] ?? $sizes['md'];
$iconSize = $iconSizes[$size] ?? $iconSizes['md'];
$base = "inline-flex items-center justify-center font-medium rounded-lg shadow-sm transition-colors duration-150 focus:outline-none focus:ring-4 {$classes} {$sizeClasses}";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $base]) }}>
        @if ($icon)
            <span class="{{ $iconSize }}">{!! $icon !!}</span>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $base]) }}>
        @if ($icon)
            <span class="{{ $iconSize }}">{!! $icon !!}</span>
        @endif
        {{ $slot }}
    </button>
@endif