<div class="row g-3">
    <div class="col-12 col-lg-4">
        <x-ui.card title="Planifier des séances">
            @if ($message)
                <div class="alert alert-success py-2 small mb-3">
                    <i class="bi bi-check-circle me-1"></i>{{ $message }}
                </div>
            @endif
            @if ($erreur)
                <div class="alert alert-danger py-2 small mb-3">
                    <i class="bi bi-exclamation-circle me-1"></i>{{ $erreur }}
                </div>
            @endif

            <form wire:submit.prevent="enregistrer">
                <div class="mb-3">
                    <label class="form-label">Programme *</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-diagram-3"></i></span>
                        <select class="form-select" wire:model.live="programmeId">
                            <option value="">Choisir un programme...</option>
                            @foreach ($programmes as $programme)
                                <option value="{{ $programme['id'] }}">
                                    {{ $programme['libelle'] }} ({{ $programme['nb_seances'] }} séance(s))
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('programmeId')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                @error('lignes')
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
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                    <input type="date" class="form-control" wire:model="lignes.{{ $index }}.date_seance" placeholder="Date">
                                </div>
                                @error('lignes.'.$index.'.date_seance')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                    <input type="time" class="form-control" wire:model="lignes.{{ $index }}.heure_debut" placeholder="Début">
                                </div>
                                @error('lignes.'.$index.'.heure_debut')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-clock-history"></i></span>
                                    <input type="time" class="form-control" wire:model="lignes.{{ $index }}.heure_fin" placeholder="Fin">
                                </div>
                                @error('lignes.'.$index.'.heure_fin')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-bell"></i></span>
                                    <input type="number" min="0" class="form-control" wire:model="lignes.{{ $index }}.delai_rappel" placeholder="Rappel (j)">
                                </div>
                            </div>
                            <div class="col-6">
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
                        <i class="bi bi-calendar-plus me-1"></i>Enregistrer
                    </button>
                </div>
            </form>
        </x-ui.card>
    </div>

    <div class="col-12 col-lg-8">
        <x-ui.card title="Toutes les séances" :footer="count($seances).' séance(s) au total'">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" data-table-filter="#table-seances"
                               class="form-control" placeholder="Filtrer par programme, date, lieu..." aria-label="Filtrer">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="table-seances">
                    <thead>
                        <tr>
                            <th class="text-muted small">N°</th>
                            <th class="text-muted small">Programme</th>
                            <th class="text-muted small">Date</th>
                            <th class="text-muted small">Heures</th>
                            <th class="text-muted small">Lieu</th>
                            <th class="text-muted small">Rappel</th>
                            <th class="text-muted small">Intervenants</th>
                            <th class="text-muted small text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($seances as $seance)
                            <tr wire:key="seance-{{ $seance['id'] }}">
                                <td>{{ $seance['numero'] }}</td>
                                <td>{{ $seance['programme'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($seance['date_seance'])->format('d/m/Y') }}</td>
                                <td>{{ $seance['heure_debut'] }}<span class="text-muted">—</span>{{ $seance['heure_fin'] ?? '?' }}</td>
                                <td>{{ $seance['lieu'] ?? '—' }}</td>
                                <td>{{ $seance['delai_rappel'] ?? '—' }}</td>
                                <td>
                                    @forelse ($seance['intervenants'] as $intervenant)
                                        <span class="badge bg-secondary-subtle text-secondary me-1 mb-1">{{ $intervenant }}</span>
                                    @empty
                                        <span class="text-muted small">—</span>
                                    @endforelse
                                </td>
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
                                <td colspan="8">
                                    <div class="empty-state py-4">
                                        <i class="bi bi-inbox"></i>
                                        <p class="mb-0 mt-2">Aucune séance planifiée pour le moment.</p>
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