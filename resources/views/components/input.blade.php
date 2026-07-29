@props(['name', 'label' => null, 'type' => 'text', 'value' => null])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }}</label>
    @endif
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => 'w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500']) }}
    >
    @error($name)
        <p class="text-red-600 text-sm mt-1.5">{{ $message }}</p>
    @enderror
</div>