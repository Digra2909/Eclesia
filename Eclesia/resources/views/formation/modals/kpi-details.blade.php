{{-- ===== Modales de détail des KPI (au clic sur une carte) ===== --}}
{{-- Cartes avec boutons « Modifier » / « Supprimer », comme dans Consulter. --}}
@php
    $statutVariantsProg = [
        'créé' => 'secondary',
        'programmé' => 'info',
        'validé' => 'success',
        'en cours' => 'warning',
        'cloturé' => 'dark',
    ];
    $typeVariantsProg = [
        'NU' => 'primary',
        'Ord' => 'success',
    ];
@endphp

{{-- Fidèles ouvriers --}}
<x-ui.modal id="modal-kpi-ouvriers" title="Fidèles ouvriers" size="xl">
    <div class="row g-3">
        @forelse ($ouvriers as $ouvrier)
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card card-consulter h-100 p-2">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">{{ $ouvrier['code_ouvrier'] }}</span>
                        <x-ui.badge variant="primary">{{ $ouvrier['poste'] ?? '—' }}</x-ui.badge>
                    </div>
                    <div class="card-body">
                        <h6 class="fw-semibold mb-1">{{ $ouvrier['nom'] }}</h6>
                        <p class="text-muted small mb-1"><i class="bi bi-person-vcard me-1"></i>{{ $ouvrier['code_fidele'] }}</p>
                        @if (! empty($ouvrier['telephone']))
                            <p class="text-muted small mb-1"><i class="bi bi-telephone me-1"></i>{{ $ouvrier['telephone'] }}</p>
                        @endif
                        @if (! empty($ouvrier['adresse']))
                            <p class="text-muted small mb-0"><i class="bi bi-geo-alt me-1"></i>{{ $ouvrier['adresse'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-people"></i>
                    <p class="mb-0 mt-2">Aucun ouvrier enregistré pour le moment.</p>
                </div>
            </div>
        @endforelse
    </div>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
    </x-slot:footer>
</x-ui.modal>

{{-- Nouvelles Unités (NU) --}}
<x-ui.modal id="modal-kpi-nus" title="Nouvelles Unités (NU)" size="xl">
    <div class="row g-3">
        @forelse ($nuCotations as $nu)
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card card-consulter h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">{{ $nu['code_nu'] }}</span>
                        <x-ui.badge :variant="($nu['pourcentage'] ?? 0) >= 50 ? 'success' : 'warning'">{{ $nu['pourcentage'] ?? '—' }}%</x-ui.badge>
                    </div>
                    <div class="card-body">
                        <h6 class="fw-semibold mb-1">{{ $nu['fidele'] }}</h6>
                        <p class="text-muted small mb-1"><i class="bi bi-person-vcard me-1"></i>{{ $nu['code_fidele'] }}</p>
                        <div class="d-flex gap-3 small text-muted">
                            <span><i class="bi bi-mic me-1"></i>Oral : <strong>{{ $nu['note_oral'] ?? '—' }}</strong></span>
                            <span><i class="bi bi-pencil me-1"></i>Écrit : <strong>{{ $nu['note_ecrite'] ?? '—' }}</strong></span>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-light" title="Modifier"
                                data-bs-toggle="modal" data-bs-target="#modal-unites-editer"
                                data-bs-modal-fill
                                data-action="{{ route('fideles.update', ['fidele' => $nu['fidele_id']]) }}"
                                data-nom="{{ $nu['surname'] }}"
                                data-prenom="{{ $nu['prenom'] }}"
                                data-postnom="{{ $nu['postnom'] }}"
                                data-code_fidele="{{ $nu['code_fidele'] }}"
                                data-genre="{{ $nu['genre'] }}"
                                data-telephone="{{ $nu['telephone'] ?? '' }}"
                                data-adresse="{{ $nu['adresse'] ?? '' }}"
                                data-statut_nu="{{ $nu['statut_nu'] }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer"
                                data-bs-toggle="modal" data-bs-target="#modal-unites-supprimer"
                                data-bs-modal-fill
                                data-action="{{ route('fideles.destroy', ['fidele' => $nu['fidele_id']]) }}"
                                data-nom="{{ $nu['surname'] }}"
                                data-code="{{ $nu['code_fidele'] }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-journal-text"></i>
                    <p class="mb-0 mt-2">Aucune nouvelle unité pour le moment.</p>
                </div>
            </div>
        @endforelse
    </div>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
    </x-slot:footer>
</x-ui.modal>

{{-- Programmes en attente de validation (statut « créé ») --}}
<x-ui.modal id="modal-kpi-attente" title="Programmes en attente de validation" size="xl">
    <div class="row g-3">
        @forelse ($programmesAttente as $programme)
            @include('formation.modals._programme-card', ['programme' => $programme, 'statutVariantsProg' => $statutVariantsProg, 'typeVariantsProg' => $typeVariantsProg])
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-hourglass-split"></i>
                    <p class="mb-0 mt-2">Aucun programme en attente de validation.</p>
                </div>
            </div>
        @endforelse
    </div>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
    </x-slot:footer>
</x-ui.modal>

{{-- Programmes validés --}}
<x-ui.modal id="modal-kpi-valides" title="Programmes validés" size="xl">
    <div class="row g-3">
        @forelse ($programmesValidesListe as $programme)
            @include('formation.modals._programme-card', ['programme' => $programme, 'statutVariantsProg' => $statutVariantsProg, 'typeVariantsProg' => $typeVariantsProg])
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-check2-circle"></i>
                    <p class="mb-0 mt-2">Aucun programme validé pour le moment.</p>
                </div>
            </div>
        @endforelse
    </div>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
    </x-slot:footer>
</x-ui.modal>