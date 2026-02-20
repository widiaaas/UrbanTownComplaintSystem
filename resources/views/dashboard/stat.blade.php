@props([
    'title',
    'value',
    'color' => 'blue'
])

@php
    $colors = [
        'blue' => 'text-blue-600',
        'green' => 'text-green-600',
        'purple' => 'text-purple-600',
        'yellow' => 'text-yellow-600',
        'red' => 'text-red-600',
    ];

    $colorClass = $colors[$color] ?? 'text-gray-600';
@endphp

<div class="bg-white p-6 rounded-lg border shadow-sm">
    <p class="text-sm text-gray-500">{{ $title }}</p>
    <p class="text-3xl font-bold mt-2 {{ $colorClass }}">
        {{ $value }}
    </p>
</div>
