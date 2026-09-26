@php
    $statutVariants = [
        'créé' => 'secondary',
        'programmé' => 'info',
        'validé' => 'primary',
        'en cours' => 'warning',
        'clôturé' => 'success',
    ];
@endphp

<div class="row g-3">
    <div class="col-12 col-lg-4">
        <x-ui.card :title="$editeId ? 'Modifier le programme' : 'Nouveau programme'">
            @if ($message)
                <div class="alert alert-success py-2 small mb-3">
                    <i class="bi bi-check-circle me-1"></i>{{ $message }}
                    @if ($dernierId)
                        <a href="{{ route('programmes.show', $dernierId) }}" class="alert-link ms-1">Continuer sur ce programme →</a>
                    @endif
                </div>
            @endif
            @if ($erreur)
                <div class="alert alert-danger py-2 small mb-3">
                    <i class="bi bi-exclamation-circle me-1"></i>{{ $erreur }}
                </div>
            @endif

            <form wire:submit.prevent="enregistrer" class="row g-3">
                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-diagram-3"></i></span>
                        <select class="form-select" wire:model.live="type">
                            <option value="NU">Formation unités</option>
                            <option value="Ord">Formation ordinaire</option>
                        </select>
                    </div>
                    @error('type')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                @if ($type === 'NU')
                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                            <input type="text" class="form-control" wire:model.defer="session" placeholder="Session (Ex : Avrill 2026) *" autocomplete="off">
                        </div>
                        @error('session')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                @else
                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-easel"></i></span>
                            <input type="text" class="form-control" wire:model.defer="theme" placeholder="Thème de la formation *" autocomplete="off">
                        </div>
                        @error('theme')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-sticky"></i></span>
                        <textarea class="form-control" wire:model.defer="commentaire" rows="2" placeholder="Commentaire"></textarea>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    @if ($editeId)
                        <button type="button" class="btn btn-light" wire:click="annulerEdition">Annuler</button>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-{{ $editeId ? 'check-lg' : 'plus-lg' }} me-1"></i>{{ $editeId ? 'Enregistrer' : 'Créer' }}
                    </button>
                </div>
            </form>
        </x-ui.card>
    </div>

    <div class="col-12 col-lg-8">
        <x-ui.card title="Consulter les programmes" :footer="count($programmes).' programme(s) au total'">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="filtre-prog-intitule" class="form-control" placeholder="Filtrer par intitulé..." aria-label="Filtrer par intitulé">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-funnel text-muted"></i></span>
                        <select id="filtre-prog-type" class="form-select" aria-label="Filtrer par type">
                            <option value="">Tous les types</option>
                            <option value="NU">Formation des NUs</option>
                            <option value="Ord">Formation des Ordination</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-check2-circle text-muted"></i></span>
                        <select id="filtre-prog-statut" class="form-select" aria-label="Filtrer par statut">
                            <option value="">Tous les statuts</option>
                            @foreach (array_keys($statutVariants) as $statut)
                                <option value="{{ $statut }}">{{ $statut }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row g-3" id="prog-cards">
                @forelse ($programmes as $programme)
                    <div class="col-12 col-md-6" wire:key="programme-{{ $programme['id'] }}"
                         data-filtre-prog
                         data-intitule="{{ $programme['libelle'] }}"
                         data-type="{{ $programme['type'] }}"
                         data-statut="{{ $programme['statut'] }}">
                        <div class="card card-consulter h-100 p-2">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <span class="badge bg-info-subtle text-info">{{ $programme['type'] }}</span>
                                <x-ui.badge :variant="$statutVariants[$programme['statut']] ?? 'secondary'">{{ $programme['statut'] }}</x-ui.badge>
                            </div>
                            <div class="card-body">
                                <h6 class="fw-semibold mb-1">{{ $programme['libelle'] }}</h6>
                                <p class="text-muted small mb-1">
                                    <i class="bi bi-calendar-week me-1"></i>{{ $programme['nb_seances'] }} séance(s)
                                    <span class="ms-2"><i class="bi bi-book me-1"></i>{{ $programme['nb_cours'] }} cours</span>
                                </p>
                                @if (! empty($programme['commentaire']))
                                    <p class="text-muted small mb-0 mt-1"><i class="bi bi-sticky me-1"></i>{{ $programme['commentaire'] }}</p>
                                @endif
                            </div>
                            <div class="card-footer bg-white d-flex justify-content-end gap-2">
                                <a href="{{ route('programmes.show', $programme['id']) }}" class="btn btn-sm btn-outline-info" title="Continuer">
                                    <i class="bi bi-arrow-right-circle me-1"></i>Détails
                                </a>
                                <button type="button" class="btn btn-sm btn-light" title="Éditer" wire:click="editer({{ $programme['id'] }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Supprimer"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-supprimer"
                                        data-confirm-delete="{{ route('programmes.destroy', ['programme' => $programme['id']]) }}"
                                        data-confirm-label="{{ $programme['libelle'] }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p class="mb-0 mt-2">Aucun programme à afficher pour le moment.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </x-ui.card>
    </div>
</div>