@props([
    'label' => '',
    'value' => '',
    'icon' => '',
    'trend' => '',
    'trendDirection' => 'up',
    'trendVariant' => 'success',
    'variant' => 'primary',
])

@php
    $trendIcon = $trendDirection === 'down' ? 'bi-arrow-down-right' : 'bi-arrow-up-right';
    $trendClass = $trendVariant === 'danger' ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success';

    $variants = [
        'primary' => ['chip' => 'bg-primary-subtle text-primary', 'accent' => 'border-primary'],
        'success' => ['chip' => 'bg-success-subtle text-success', 'accent' => 'border-success'],
        'warning' => ['chip' => 'bg-warning-subtle text-warning', 'accent' => 'border-warning'],
        'danger' => ['chip' => 'bg-danger-subtle text-danger', 'accent' => 'border-danger'],
        'info' => ['chip' => 'bg-info-subtle text-info', 'accent' => 'border-info'],
    ];
    $variant = $variants[$variant] ?? $variants['primary'];
@endphp

<div {{ $attributes->merge(['class' => 'card kpi-card border-0 shadow-sm p-3 h-100 border-top border-4 '.$variant['accent']]) }}>
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted small">{{ $label }}</span>
        @if ($icon)
            <span class="d-inline-flex align-items-center justify-content-center rounded-3 {{ $variant['chip'] }}" style="width: 42px; height: 42px;">
                <i class="bi {{ $icon }} fs-5"></i>
            </span>
        @endif
    </div>
    <h3 class="fw-bold mb-1">{{ $value }}</h3>
    @if ($trend)
        <span class="badge {{ $trendClass }} w-auto align-self-start">
            <i class="bi {{ $trendIcon }}"></i> {{ $trend }}
        </span>
    @else
        <span class="small text-muted mt-1"><i class="bi bi-mouse2 me-1"></i>Cliquer pour le détail</span>
    @endif
</div>