@props(['type' => 'primary'])

@php
    $base = "inline-flex items-center justify-center px-4 py-2 rounded-xl shadow transition duration-300";
    $styles = [
        'primary' => 'bg-green-600 hover:bg-green-700 text-white',
        'edit' => 'bg-yellow-400 hover:bg-yellow-500 text-white',
        'delete' => 'bg-red-500 hover:bg-red-600 text-white',
    ];
@endphp

<button {{ $attributes->merge(['class' => "$base " . ($styles[$type] ?? $styles['primary'])]) }}>
    {{ $slot }}
</button>
