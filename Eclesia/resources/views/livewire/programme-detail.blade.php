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
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <a href="{{ route('programmes.index') }}" class="btn btn-sm btn-light mb-2">
                    <i class="bi bi-arrow-left me-1"></i>Retour aux programmes
                </a>
                <h4 class="mb-0">
                    {{ $infos['libelle'] ?? 'Programme #'.$programme->id }}
                    <span class="badge bg-info-subtle text-info align-middle">{{ $infos['type'] }}</span>
                    <x-ui.badge :variant="$statutVariants[$infos['statut']] ?? 'secondary'" class="align-middle">{{ $infos['statut'] }}</x-ui.badge>
                </h4>
            </div>
        </div>
    </div>

    @if ($message)
        <div class="col-12">
            <div class="alert alert-success py-2 small mb-0">
                <i class="bi bi-check-circle me-1"></i>{{ $message }}
            </div>
        </div>
    @endif
    @if ($erreur)
        <div class="col-12">
            <div class="alert alert-danger py-2 small mb-0">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $erreur }}
            </div>
        </div>
    @endif

    {{-- ============ Informations / statut du programme ============ --}}
    <div class="col-12 col-lg-5">
        <x-ui.card title="Programme">
            <form wire:submit.prevent="enregistrerStatut" class="row g-3">
                <div class="col-12">
                    <label class="form-label">Statut</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-check2-circle"></i></span>
                        <select class="form-select" wire:model="statut">
                            @foreach (array_keys($statutVariants) as $statut)
                                <option value="{{ $statut }}">{{ $statut }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('statut')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Commentaire</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-sticky"></i></span>
                        <textarea class="form-control" wire:model.defer="commentaire" rows="3" placeholder="Commentaire"></textarea>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Mettre à jour</button>
                </div>
            </form>
        </x-ui.card>
    </div>

    {{-- ============ Association des cours ============ --}}
    <div class="col-12 col-lg-7">
        <x-ui.card title="Cours associés" :footer="$programme->cours()->count().' cours associé(s)'">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Cocher les cours de ce programme</label>
                    <div wire:key="cours-associes" class="d-flex flex-column gap-2">
                        @forelse ($cours as $entree)
                            <div class="form-check border rounded-2 px-3 py-2 mb-0" wire:key="cours-{{ $entree['id'] }}">
                                <input type="checkbox" class="form-check-input" value="{{ $entree['id'] }}" wire:model.live="coursIds">
                                <label class="form-check-label">{{ $entree['passages'] }}</label>
                            </div>
                        @empty
                            <div class="empty-state py-3">
                                <i class="bi bi-inbox"></i>
                                <p class="mb-0 mt-2">Aucun cours n'existe encore. Ajoutez-en un ci-dessous.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Ou créer un cours et l'associer immédiatement</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-book"></i></span>
                        <input type="text" class="form-control" wire:model.defer="nouveauPassages" placeholder="Passages (thème ou versets)" autocomplete="off">
                        <button type="button" class="btn btn-outline-primary" wire:click="ajouterEtAssocierCours">
                            <i class="bi bi-plus-lg me-1"></i>Créer & associer
                        </button>
                    </div>
                    @error('nouveauPassages')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" wire:click="associerCours">
                        <i class="bi bi-link me-1"></i>Enregistrer l'association
                    </button>
                </div>
            </div>
        </x-ui.card>
    </div>

    {{-- ============ Ajout de séances ============ --}}
    <div class="col-12">
        <x-ui.card title="Ajouter des séances à ce programme" :footer="$seances->count().' séance(s) au total'">
            <form wire:submit.prevent="enregistrerSeances">
                @error('lignes')
                    <div class="text-danger small mb-2">{{ $message }}</div>
                @enderror
                @error('lignes.*')
                    <div class="text-danger small mb-2">{{ $message }}</div>
                @enderror

                @foreach ($lignes as $index => $ligne)
                    <div class="border rounded-3 p-3 mb-3" wire:key="ligne-{{ $index }}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold small">
                                Séance n°<span class="text-primary">{{ $this->numeroDe($index) }}</span>
                            </span>
                            <button type="button" class="btn btn-sm btn-light" wire:click="retirerLigne({{ $index }})">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <div class="row g-2">
                            <div class="col-12 col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                    <input type="date" class="form-control" wire:model="lignes.{{ $index }}.date_seance" placeholder="Date">
                                </div>
                                @error('lignes.'.$index.'.date_seance')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                    <input type="time" class="form-control" wire:model="lignes.{{ $index }}.heure_debut" placeholder="Début">
                                </div>
                                @error('lignes.'.$index.'.heure_debut')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-clock-history"></i></span>
                                    <input type="time" class="form-control" wire:model="lignes.{{ $index }}.heure_fin" placeholder="Fin">
                                </div>
                                @error('lignes.'.$index.'.heure_fin')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-bell"></i></span>
                                    <input type="number" min="0" class="form-control" wire:model="lignes.{{ $index }}.delai_rappel" placeholder="Rappel (j)">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" class="form-control" wire:model="lignes.{{ $index }}.lieu" placeholder="Lieu (défaut : le temple de l'église)">
                                </div>
                            </div>
                        </div>

                        @for ($slot = 1; $slot <= 2; $slot++)
                            <div class="mt-3">
                                <label class="form-label small text-muted mb-1">
                                    <i class="bi bi-people me-1"></i>Intervenant n°{{ $slot }} (facultatif) — fidèle + type d'intervention
                                </label>
                                <div class="row g-2">
                                    <div class="col-12 col-md-6">
                                        <select class="form-select" wire:model="lignes.{{ $index }}.intervention{{ $slot }}_fidele" aria-label="Fidèle intervenant {{ $slot }}">
                                            <option value="">— Fidèle —</option>
                                            @foreach ($fideles as $fidele)
                                                <option value="{{ $fidele['id'] }}">{{ $fidele['nom'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('lignes.'.$index.'.intervention'.$slot.'_fidele')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <select class="form-select" wire:model="lignes.{{ $index }}.intervention{{ $slot }}_type" aria-label="Type d'intervention {{ $slot }}">
                                            <option value="">— Type d'intervention —</option>
                                            @foreach ($typesIntervention as $type)
                                                <option value="{{ $type['id'] }}">{{ $type['designation'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('lignes.'.$index.'.intervention'.$slot.'_type')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                @endforeach

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-secondary" wire:click="ajouterLigne">
                        <i class="bi bi-plus-lg me-1"></i>Ajouter une ligne
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-calendar-plus me-1"></i>Enregistrer les séances
                    </button>
                </div>
            </form>

            <hr class="my-4">

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="table-seances-programme">
                    <thead>
                        <tr>
                            <th class="text-muted small">N°</th>
                            <th class="text-muted small">Date</th>
                            <th class="text-muted small">Début</th>
                            <th class="text-muted small">Fin</th>
                            <th class="text-muted small">Lieu</th>
                            <th class="text-muted small">Rappel</th>
                            <th class="text-muted small text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($seances as $seance)
                            <tr wire:key="seance-{{ $seance['id'] }}">
                                <td>{{ $seance['numero'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($seance['date_seance'])->format('d/m/Y') }}</td>
                                <td>{{ $seance['heure_debut'] }}</td>
                                <td>{{ $seance['heure_fin'] ?? '—' }}</td>
                                <td>{{ $seance['lieu'] ?? '—' }}</td>
                                <td>{{ $seance['delai_rappel'] ?? '—' }}</td>
                                <td class="text-end">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Supprimer"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal-supprimer"
                                            data-confirm-delete="{{ route('seances.destroy', ['seance' => $seance['id']]) }}"
                                            data-confirm-label="la séance n°{{ $seance['numero'] }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state py-4">
                                        <i class="bi bi-inbox"></i>
                                        <p class="mb-0 mt-2">Aucune séance n'a encore été planifiée pour ce programme.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>
</div>