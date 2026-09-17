@props([
    'variant' => 'primary',
    'size' => '',
    'icon' => '',
    'type' => 'button',
])

@php
    $sizeClass = match ($size) {
        'sm' => 'btn-sm',
        'lg' => 'btn-lg',
        default => '',
    };
    $classes = trim("btn btn-{$variant} {$sizeClass} d-inline-flex align-items-center gap-1");
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if ($icon)
        <i class="bi {{ $icon }}"></i>
    @endif
    {{ $slot }}
</button>