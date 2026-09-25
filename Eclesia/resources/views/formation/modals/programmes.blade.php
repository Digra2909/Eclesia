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

{{-- ============ AJOUTER un programme de formation ============ --}}
<x-ui.modal id="modal-prog-ajouter" title="Ajouter un programme de formation" size="lg">
    <form class="row g-3" id="form-prog-ajouter" action="{{ route('programmes.store') }}" method="POST">
        @csrf
        @if ($errors->any())
            <div class="col-12">
                <div class="alert alert-danger py-2 small mb-0">
                    @foreach ($errors->all() as $error)
                        <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="col-12">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-diagram-3"></i></span>
                <select class="form-select" name="type" id="prog-type" required>
                    <option value="" selected disabled>Type de programme *</option>
                    <option value="NU">Formation des Nouvelles Unités (NU)</option>
                    <option value="Ord">Formation des Ordres</option>
                </select>
            </div>
        </div>
        <div id="prog-fields-nu" class="col-12">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-lines-fill"></i></span>
                <input type="text" class="form-control" name="session" placeholder="Session (programme NU) *">
            </div>
        </div>
        <div id="prog-fields-ord" class="col-12 d-none">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-palette"></i></span>
                <input type="text" class="form-control" name="theme" placeholder="Thème (programme Ord) *">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-cash-coin"></i></span>
                <input type="number" min="0" class="form-control" name="montant" placeholder="Montant (FCFA)">
                <span class="input-group-text">FCFA</span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-flag"></i></span>
                <select class="form-select" name="statut">
                    <option value="créé" selected>créé</option>
                    <option value="programmé">programmé</option>
                    <option value="validé">validé</option>
                    <option value="en cours">en cours</option>
                    <option value="cloturé">cloturé</option>
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-chat-left-text"></i></span>
                <textarea class="form-control" name="commentaire" rows="2" placeholder="Commentaire (optionnel)"></textarea>
            </div>
        </div>
    </form>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" form="form-prog-ajouter" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Ajouter</button>
    </x-slot:footer>
</x-ui.modal>

{{-- ============ CONSULTER les programmes (cards + filtres) ============ --}}
<x-ui.modal id="modal-prog-consulter" title="Consulter les programmes" size="xl">
    <div class="row g-2 mb-3">
        <div class="col-12 col-md-5">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control" placeholder="Filtrer par intitulé..." aria-label="Filtrer les programmes" id="filtre-prog-intitule">
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-diagram-3 text-muted"></i></span>
                <select id="filtre-prog-type" class="form-select" aria-label="Filtrer par type">
                    <option value="">Tous les types</option>
                    <option value="NU">NU</option>
                    <option value="Ord">Ord</option>
                </select>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-funnel text-muted"></i></span>
                <select id="filtre-prog-statut" class="form-select" aria-label="Filtrer par statut">
                    <option value="">Tous les statuts</option>
                    @foreach ($statutVariantsProg as $statut => $variant)
                        <option>{{ $statut }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row g-3" id="prog-cards">
        @forelse ($programmes as $programme)
            <div class="col-12 col-sm-6 col-lg-4"
                 data-filtre-prog
                 data-intitule="{{ $programme['libelle'] }}"
                 data-type="{{ $programme['type'] ?? '' }}"
                 data-statut="{{ $programme['statut'] }}">
                <div class="card card-consulter h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <x-ui.badge :variant="$typeVariantsProg[$programme['type']] ?? 'secondary'">{{ $programme['type'] ?? '—' }}</x-ui.badge>
                        <x-ui.badge :variant="$statutVariantsProg[$programme['statut']] ?? 'secondary'">{{ $programme['statut'] }}</x-ui.badge>
                    </div>
                    <div class="card-body">
                        <h6 class="fw-semibold mb-1">{{ $programme['libelle'] }}</h6>
                        <p class="text-muted small mb-1">
                            <i class="bi bi-collection me-1"></i>{{ $programme['nb_seances'] }} séance(s) ·
                            <i class="bi bi-book me-1"></i>{{ $programme['nb_cours'] }} cours
                        </p>
                        <p class="text-muted small mb-0"><i class="bi bi-cash-coin me-1"></i>{{ number_format((int) $programme['montant'], 0, ',', ' ') }} FCFA</p>
                        @if (! empty($programme['commentaire']))
                            <p class="text-muted small mt-1 mb-0"><i class="bi bi-chat-left-text me-1"></i>{{ $programme['commentaire'] }}</p>
                        @endif
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-end gap-2 flex-wrap">
                        <button type="button" class="btn btn-sm btn-light" title="Éditer"
                                data-bs-toggle="modal" data-bs-target="#modal-prog-editer"
                                data-bs-modal-fill
                                data-action="{{ route('programmes.update', ['programme' => $programme['id']]) }}"
                                data-libelle="{{ $programme['libelle'] }}"
                                data-montant="{{ $programme['montant'] }}"
                                data-statut="{{ $programme['statut'] }}"
                                data-commentaire="{{ $programme['commentaire'] }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-success" title="Valider"
                                data-bs-toggle="modal" data-bs-target="#modal-prog-valider"
                                data-bs-modal-fill data-libelle="{{ $programme['libelle'] }}">
                            <i class="bi bi-check2-circle"></i>
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
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-mortarboard"></i>
                    <p class="mb-0 mt-2">Aucun programme à afficher pour le moment.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center align-items-center gap-2 mt-3">
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-seance-consulter">
            <i class="bi bi-clock-history me-1"></i>Gérer les séances
        </button>
    </div>

    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-prog-ajouter">
            <i class="bi bi-plus-lg me-1"></i>Ajouter un programme
        </button>
    </x-slot:footer>
</x-ui.modal>

{{-- ============ ÉDITER un programme ============ --}}
<x-ui.modal id="modal-prog-editer" title="Éditer le programme" size="lg">
    <form class="row g-3">
        @csrf
        @method('PUT')
        <div class="col-12">
            <div class="alert alert-light border small mb-0">
                <i class="bi bi-mortarboard me-1 text-primary"></i>
                <span data-field="libelle"><em>Programme</em></span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-cash-coin"></i></span>
                <input type="number" min="0" class="form-control" name="montant" placeholder="Montant (FCFA)">
                <span class="input-group-text">FCFA</span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-flag"></i></span>
                <select class="form-select" name="statut">
                    @foreach ($statutVariantsProg as $statut => $variant)
                        <option>{{ $statut }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-chat-left-text"></i></span>
                <textarea class="form-control" name="commentaire" rows="2" placeholder="Commentaire (optionnel)"></textarea>
            </div>
        </div>
    </form>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
    </x-slot:footer>
</x-ui.modal>

{{-- ============ VALIDER un programme (cards + filtre + commentaire) ============ --}}
<x-ui.modal id="modal-prog-valider" title="Valider un programme" size="xl">
    <div class="row g-2 mb-3">
        <div class="col-12 col-md-7">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control" placeholder="Filtrer par intitulé..." aria-label="Filtrer pour validation" id="filtre-validation-intitule">
            </div>
        </div>
        <div class="col-12 col-md-5">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-diagram-3 text-muted"></i></span>
                <select class="form-select" id="filtre-validation-type" aria-label="Filtrer par type">
                    <option value="">Tous les types</option>
                    <option value="NU">NU</option>
                    <option value="Ord">Ord</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row g-3" id="prog-validation-cards">
        @forelse ($programmes as $programme)
            <div class="col-12 col-sm-6 col-lg-4"
                 data-validation-card
                 data-intitule="{{ $programme['libelle'] }}"
                 data-type="{{ $programme['type'] ?? '' }}">
                <div class="card card-consulter h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <x-ui.badge :variant="$typeVariantsProg[$programme['type']] ?? 'secondary'">{{ $programme['type'] ?? '—' }}</x-ui.badge>
                        <x-ui.badge :variant="$statutVariantsProg[$programme['statut']] ?? 'secondary'">{{ $programme['statut'] }}</x-ui.badge>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="fw-semibold mb-1">{{ $programme['libelle'] }}</h6>
                        <div class="text-muted small mb-2">
                            <i class="bi bi-calendar3 me-1"></i>Créé le {{ $programme['date_creation'] }} ·
                            <i class="bi bi-collection ms-1 me-1"></i>{{ $programme['nb_seances'] }} séance(s) ·
                            <i class="bi bi-book ms-1 me-1"></i>{{ $programme['nb_cours'] }} cours
                        </div>

                        <div class="small">
                            <p class="mb-1"><i class="bi bi-cash-coin me-1 text-success"></i>{{ number_format((int) $programme['montant'], 0, ',', ' ') }} FCFA</p>
                            @if (! empty($programme['cours_list']) && $programme['cours_list']->count() > 0)
                                <p class="mb-1"><i class="bi bi-journal-bookmark me-1 text-primary"></i>Cours : {{ $programme['cours_list']->implode(', ') }}</p>
                            @endif
                            @if (! empty($programme['commentaire']))
                                <p class="mb-1"><i class="bi bi-chat-left-text me-1 text-secondary"></i>{{ $programme['commentaire'] }}</p>
                            @endif
                        </div>

                        @if (! empty($programme['seances_list']) && $programme['seances_list']->count() > 0)
                            <div class="mt-2">
                                <div class="small fw-semibold mb-1"><i class="bi bi-calendar2-week text-primary me-1"></i>Séances associées</div>
                                <ul class="list-group list-group-flush small border rounded">
                                    @foreach ($programme['seances_list'] as $seanceProg)
                                        <li class="list-group-item d-flex justify-content-between align-items-center gap-2 px-2 py-1">
                                            <span class="text-nowrap">Séance n°{{ $seanceProg['numero_seance'] }}</span>
                                            <span class="text-muted text-end">
                                                {{ $seanceProg['date_seance'] }}
                                                @if ($seanceProg['heure_debut']) · {{ $seanceProg['heure_debut'] }}@endif
                                                @if ($seanceProg['lieu']) · {{ $seanceProg['lieu'] }}@endif
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <p class="text-muted small mt-2 mb-0"><i class="bi bi-calendar2-week me-1"></i>Aucune séance associée.</p>
                        @endif

                        <div class="mt-auto pt-2">
                            <button type="button" class="btn btn-sm btn-outline-primary w-100" data-afficher>
                                <i class="bi bi-clipboard2-check me-1"></i>Décision de validation
                            </button>
                            <div class="d-none mt-2" data-validation-form>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-chat-left-text"></i></span>
                                    <textarea class="form-control" name="commentaire" rows="2" placeholder="Commentaire"></textarea>
                                </div>
                                <div class="d-flex gap-2 mt-2">
                                    <button type="button" class="btn btn-sm btn-success flex-fill" data-validation>Valider</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary flex-fill" data-modifier>À modifier</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-check2-circle"></i>
                    <p class="mb-0 mt-2">Aucun programme à valider.</p>
                </div>
            </div>
        @endforelse
    </div>

    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
    </x-slot:footer>
</x-ui.modal>

{{-- ============ SUPPRIMER un programme ============ --}}
<x-ui.modal id="modal-prog-supprimer" title="Confirmer la suppression" size="sm">
    <p class="mb-0">
        Voulez-vous vraiment supprimer le programme <strong data-field="libelle"></strong> ?
    </p>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-danger" data-confirm-submit><i class="bi bi-trash me-1"></i>Supprimer</button>
    </x-slot:footer>
</x-ui.modal>