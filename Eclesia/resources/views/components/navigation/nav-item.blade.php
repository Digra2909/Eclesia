@props([
    'href' => '#',
    'icon' => '',
    'active' => false,
    'label' => '',
])

@php
    $classes = $active
        ? 'nav-link text-white active bg-primary'
        : 'nav-link text-white-50 hover-bg-secondary';
@endphp

<li class="nav-item">
    <a href="{{ $href }}" class="{{ $classes }}">
        @if ($icon)
            <i class="bi {{ $icon }} me-2"></i>
        @endif
        {{ $label }}
    </a>
</li>