@props(['title', 'text', 'link', 'color', 'class' => ''])

<div {{ $attributes->merge(['class' => 'card interactive-card p-6 rounded-xl text-center shadow-md transition-transform hover:scale-105 '.$class]) }}
     style="background: {{ $colorMap[$color] ?? '#ffffff' }}">
    <h2 class="card-title text-xl font-bold mb-2">{{ $title }}</h2>
    <p class="card-text mb-4">{{ $text }}</p>
    <a href="{{ $link }}" class="btn inline-block px-4 py-2 rounded-lg font-semibold text-white transition-transform hover:scale-105"
       style="background-color: {{ $colorMap[$color] ?? '#2563eb' }}">Go</a>
</div>
@php
$colorMap = [
    'blue' => '#2563eb',
    'purple' => '#7e22ce',
    'green' => '#16a34a',
    'yellow' => '#ca8a04'
];
@endphp
