{{-- Card programme réutilisée dans les modales de détail KPI (attente / validés).
     Attend : $programme (map), $statutVariantsProg, $typeVariantsProg. --}}
<div class="col-12 col-sm-6 col-lg-4">
    <div class="card card-consulter h-100">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <x-ui.badge :variant="$typeVariantsProg[$programme['type']] ?? 'secondary'">{{ $programme['type'] ?? '—' }}</x-ui.badge>
        </div>
        <div class="card-body">
            <h6 class="fw-semibold mb-1">{{ $programme['libelle'] }}</h6>
            <p class="text-muted small mb-1">
                <i class="bi bi-collection me-1"></i>{{ $programme['nb_seances'] }} séance(s) ·
                <i class="bi bi-book me-1"></i>{{ $programme['nb_cours'] }} cours
            </p>
            @if (! empty($programme['commentaire']))
                <p class="text-muted small mt-1 mb-0"><i class="bi bi-chat-left-text me-1"></i>{{ $programme['commentaire'] }}</p>
            @endif
        </div>
        <div class="card-footer bg-white d-flex justify-content-end gap-2 flex-wrap">
            <button type="button" class="btn btn-sm btn-light" title="Modifier"
                    data-bs-toggle="modal" data-bs-target="#modal-prog-editer"
                    data-bs-modal-fill
                    data-action="{{ route('programmes.update', ['programme' => $programme['id']]) }}"
                    data-libelle="{{ $programme['libelle'] }}"
                    data-commentaire="{{ $programme['commentaire'] ?? '' }}">
                <i class="bi bi-pencil"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer"
                    data-bs-toggle="modal" data-bs-target="#modal-prog-supprimer"
                    data-bs-modal-fill
                    data-action="{{ route('programmes.destroy', ['programme' => $programme['id']]) }}"
                    data-libelle="{{ $programme['libelle'] }}">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>
</div>