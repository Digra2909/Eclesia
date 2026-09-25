@props([
    'id' => 'exampleModal',
    'title' => '',
    'size' => '',
])

@php
    $sizeClass = match ($size) {
        'sm' => 'modal-sm',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
        default => '',
    };

    // Icône d'en-tête automatique selon le type de modale.
    $titleIcon = match (true) {
        str_contains($id, 'supprimer') => 'bi-trash',
        str_contains($id, 'ajouter') => 'bi-plus-lg',
        str_contains($id, 'valider') => 'bi-check2-circle',
        str_contains($id, 'consulter') => 'bi-eye',
        str_contains($id, 'editer') => 'bi-pencil',
        str_contains($id, 'saisie') => 'bi-person-check-fill',
        str_contains($id, 'stats') => 'bi-bar-chart-line',
        str_contains($id, 'cotation') => 'bi-pencil-square',
        str_contains($id, 'seance') => 'bi-clock-history',
        str_contains($id, 'parametres') => 'bi-sliders2',
        str_contains($id, 'kpi') => 'bi-graph-up-arrow',
        default => '',
    };
@endphp

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable {{ $sizeClass }}">
        <div class="modal-content">
            @if ($title)
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($titleIcon)
                            <span class="modal-title-icon"><i class="bi {{ $titleIcon }}"></i></span>
                        @endif
                        {{ $title }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
            @endif

            <div class="modal-body">
                {{ $slot }}
            </div>

            @isset($footer)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>