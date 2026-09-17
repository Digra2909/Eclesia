@props([
    'headings' => [],
    'emptyMessage' => 'Aucune donnée disponible',
])

<div class="table-responsive">
    <table {{ $attributes->merge(['class' => 'table table-hover align-middle mb-0']) }}>
        <thead class="table-light">
            <tr>
                @foreach ($headings as $heading)
                    <th>{{ $heading }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
            @if (trim((string) $slot) === '')
                <tr>
                    <td colspan="{{ max(count($headings), 1) }}" class="text-center py-4 text-muted">
                        {{ $emptyMessage }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>