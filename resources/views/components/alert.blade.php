@props(['type' => 'success'])

@php
$variants = [
    'success' => 'bg-green-50 text-green-800 border-green-200',
    'error' => 'bg-red-50 text-red-800 border-red-200',
    'warning' => 'bg-amber-50 text-amber-800 border-amber-200',
    'info' => 'bg-blue-50 text-blue-800 border-blue-200',
];
$classes = $variants[$type] ?? $variants['success'];
@endphp

<div {{ $attributes->merge(['class' => "p-4 rounded-lg border text-sm {$classes}"]) }}>
    {{ $slot }}
</div>