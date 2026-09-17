@props([
    'label' => '',
    'value' => '',
    'icon' => '',
    'trend' => '',
    'trendDirection' => 'up',
    'trendVariant' => 'success',
])

@php
    $trendIcon = $trendDirection === 'down' ? 'bi-arrow-down-right' : 'bi-arrow-up-right';
    $trendClass = $trendVariant === 'danger' ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success';
@endphp

<div {{ $attributes->merge(['class' => 'card border-0 shadow-sm p-3 h-100']) }}>
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted small">{{ $label }}</span>
        @if ($icon)
            <i class="bi {{ $icon }} fs-4 text-primary"></i>
        @endif
    </div>
    <h3 class="fw-bold mb-1">{{ $value }}</h3>
    @if ($trend)
        <span class="badge {{ $trendClass }} w-auto align-self-start">
            <i class="bi {{ $trendIcon }}"></i> {{ $trend }}
        </span>
    @endif
</div>