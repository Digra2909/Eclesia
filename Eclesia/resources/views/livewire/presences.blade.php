<div>
    {{-- ============ Onglets ============ --}}
    <ul class="nav nav-pills mb-4" role="tablist">
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link {{ $onglet === 'gestion' ? 'active' : '' }}"
                    wire:click="$set('onglet', 'gestion')">
                <i class="bi bi-clipboard-check me-1"></i>Gestion des présences
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link {{ $onglet === 'statistiques' ? 'active' : '' }}"
                    wire:click="$set('onglet', 'statistiques')">
                <i class="bi bi-bar-chart me-1"></i>Statistiques
            </button>
        </li>
    </ul>

    @if ($onglet === 'gestion')
        {{-- ============ Filtres (jour + nom + code) ============ --}}
        <div class="row g-2 mb-3">
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-calendar3 text-muted"></i></span>
                    <input type="date" class="form-control" wire:model.live="dateFiltre" aria-label="Filtrer par date">
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control" wire:model.live="nomFiltre" placeholder="Filtrer par nom..." aria-label="Filtrer par nom">
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text">COMP-CNTRL-</span>
                    <input type="text" class="form-control" wire:model.live="codeFiltre" placeholder="Filtrer par N° (ex : 3)" aria-label="Filtrer par N°">
                </div>
            </div>
        </div>

        {{-- ============ Résumé de la date sélectionnée ============ --}}
        <div class="row g-3 mb-3">
            <div class="col-6 col-lg-4">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <span class="text-muted small mb-1">Séance du {{ \Carbon\Carbon::parse($dateFiltre)->format('d/m/Y') }}</span>
                    @if ($seanceDuJour)
                        <h4 class="fw-bold mb-0">n°{{ $seanceDuJour->numero_seance }}</h4>
                        <span class="small text-muted">{{ $seanceDuJour->heure_debut }} · {{ $seanceDuJour->lieu ?? 'le temple de l\'église' }}</span>
                    @else
                        <h4 class="fw-bold mb-0 text-muted">—</h4>
                        <span class="small text-muted">Aucune séance à cette date</span>
                    @endif
                </div>
            </div>
            <div class="col-6 col-lg-4">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <span class="text-muted small mb-1">Présences enregistrées</span>
                    <h4 class="fw-bold mb-0">{{ count($presences) }}</h4>
                    <span class="small text-muted">
                        @if (trim($nomFiltre) !== '')
                            pour « {{ $nomFiltre }} »
                        @elseif (trim($codeFiltre) !== '')
                            pour le code COMP-CNTRL-{{ $codeFiltre }}
                        @else
                            à cette date
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-5">
                <x-ui.card title="Enregistrer une présence">
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

                    <form wire:submit.prevent="enregistrer" class="mb-3">
                        <label class="form-label">Code du fidèle *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-qr-code"></i></span>
                            <span class="input-group-text border-start-0 fw-semibold">COMP-CNTRL-</span>
                            <input type="text" class="form-control" wire:model.defer="codeFidele"
                                   placeholder="N° (ex : 3)" inputmode="numeric" autocomplete="off">
                            <button type="submit"
                                    class="btn btn-primary"
                                    @disabled($dateFiltre !== today()->format('Y-m-d'))
                                    title="{{ $dateFiltre !== today()->format('Y-m-d') ? 'Enregistrement possible uniquement le jour de la séance' : '' }}">
                                <i class="bi bi-check2-circle me-1"></i>Valider
                            </button>
                        </div>
                        @error('codeFidele')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        @if ($dateFiltre !== today()->format('Y-m-d'))
                            <span class="text-muted small d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i>Validation désactivée : les présences s'enregistrent le jour même.
                            </span>
                        @endif
                    </form>
                </x-ui.card>
            </div>

            <div class="col-12 col-lg-7">
                <x-ui.card title="Historique des présences" :footer="count($presences).' enregistrement(s)'">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="text-muted small">Fidèle</th>
                                    <th class="text-muted small">Code</th>
                                    <th class="text-muted small">Date</th>
                                    <th class="text-muted small">Heure</th>
                                    <th class="text-muted small">Lieu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($presences as $presence)
                                    <tr wire:key="presence-{{ $presence['id'] }}">
                                        <td>{{ $presence['nom'] }}</td>
                                        <td><span class="badge bg-secondary-subtle text-secondary">{{ $presence['code_fidele'] }}</span></td>
                                        <td>{{ \Carbon\Carbon::parse($presence['date_seance'])->format('d/m/Y') }}</td>
                                        <td>{{ $presence['heure_debut'] }}</td>
                                        <td>{{ $presence['lieu'] ?? 'le temple de l\'église' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state py-4">
                                                <i class="bi bi-inbox"></i>
                                                <p class="mb-0 mt-2">Aucune présence à cette date pour le moment.</p>
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
    @else
        {{-- ============ Statistiques : classement par assiduité ============ --}}
        <div class="row g-3 mb-3">
            <div class="col-12 col-lg-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-calendar-month text-muted"></i></span>
                    <input type="month" class="form-control" wire:model.live="moisStats" aria-label="Filtrer par mois">
                </div>
            </div>
            <div class="col-12 col-lg-9 d-flex align-items-center">
                <span class="text-muted small me-2">
                    <i class="bi bi-info-circle me-1"></i>
                    Classement des fidèles par nombre de présences
                    @if ($moisStats)
                        en {{ \Carbon\Carbon::parse($moisStats.'-01')->translatedFormat('F Y') }}.
                    @else
                        (toutes périodes confondues).
                    @endif
                </span>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12">
                <x-ui.card :footer="count($classement).' fidèle(s) classé(s)'">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="text-muted small">Rang</th>
                                    <th class="text-muted small">Fidèle</th>
                                    <th class="text-muted small">Code</th>
                                    <th class="text-muted small text-end">Présences</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($classement as $entree)
                                    <tr wire:key="classement-{{ $entree['rang'] }}">
                                        <td>
                                            @if ($entree['rang'] <= 3)
                                                <i class="bi bi-trophy text-warning me-1"></i>
                                            @endif
                                            <span class="fw-semibold">#{{ $entree['rang'] }}</span>
                                        </td>
                                        <td>{{ $entree['nom'] }}</td>
                                        <td><span class="badge bg-secondary-subtle text-secondary">{{ $entree['code_fidele'] }}</span></td>
                                        <td class="text-end">
                                            <span class="badge bg-primary">{{ $entree['total'] }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-state py-4">
                                                <i class="bi bi-bar-chart"></i>
                                                <p class="mb-0 mt-2">Aucune présence enregistrée pour cette période.</p>
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
    @endif
</div>