@props([
    'text' => '',
    'variant' => 'success',
])

<span {{ $attributes->merge(['class' => "badge bg-{$variant}"]) }}>{{ $text ?? $slot }}</span>