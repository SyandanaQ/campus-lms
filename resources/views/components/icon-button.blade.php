@props(['variant' => 'default', 'type' => 'button', 'href' => null, 'label' => ''])

@php
$variants = [
    'default' => 'text-blue-700 border-blue-200 bg-white hover:bg-blue-50 hover:border-blue-300',
    'danger' => 'text-red-600 border-red-200 bg-white hover:bg-red-50 hover:border-red-300',
];
$classes = $variants[$variant] ?? $variants['default'];
$base = "inline-flex items-center justify-center w-9 h-9 rounded-lg border transition-colors duration-150 {$classes}";
@endphp

@if ($href)
    <a href="{{ $href }}" title="{{ $label }}" {{ $attributes->merge(['class' => $base]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" title="{{ $label }}" {{ $attributes->merge(['class' => $base]) }}>
        {{ $slot }}
    </button>
@endif