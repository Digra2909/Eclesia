@props([
    'title' => '',
    'footer' => '',
])

<div {{ $attributes->merge(['class' => 'card border-0 shadow-sm']) }}>
    @if ($title || isset($header))
        <div class="card-header bg-white">
            @isset($header)
                {{ $header }}
            @else
                <h5 class="fw-semibold mb-0">{{ $title }}</h5>
            @endisset
        </div>
    @endif

    <div class="card-body">
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="card-footer bg-white">
            {{ $footer }}
        </div>
    @endif
</div>