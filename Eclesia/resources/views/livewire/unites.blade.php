@php
    $statutVariants = [
        'en règle' => 'success',
        'non en règle' => 'warning',
    ];
    $genreVariants = [
        'M' => 'Masculin',
        'F' => 'Féminin',
    ];
@endphp

<div class="row g-3">
    {{-- ============ Formulaires (création / édition) ============ --}}
    <div class="col-12 col-lg-4">
        <x-ui.card
            :title="$editeId ? 'Modifier l\'unité' : 'Nouvelle unité'">

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

            <form wire:submit.prevent="enregistrer" class="row g-3">
                <div class="col-12 field-hint">
                    <i class="bi bi-info-circle me-1"></i>Les codes (fidèle, NU) sont générés automatiquement.
                </div>

                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" wire:model.defer="nom" placeholder="Nom *" autocomplete="off">
                    </div>
                    @error('nom')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                        <input type="text" class="form-control" wire:model.defer="prenom" placeholder="Prénom *" autocomplete="off">
                    </div>
                    @error('prenom')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                        <input type="text" class="form-control" wire:model.defer="postnom" placeholder="Post-nom" autocomplete="off">
                    </div>
                    @error('postnom')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                        <input type="date" class="form-control" wire:model.defer="date_naissance" placeholder="Date de naissance" autocomplete="off">
                    </div>
                </div>

                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <span class="input-group-text border-start-0">+243</span>
                        <input type="tel" class="form-control" wire:model.defer="telephone" maxlength="9" inputmode="numeric"
                               pattern="[0-9]{9}" placeholder="Ex: 820701979" autocomplete="off">
                    </div>
                    @error('telephone')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                        <input type="text" class="form-control" wire:model.defer="adresse" placeholder="Adresse" autocomplete="off">
                    </div>
                </div>

                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                        <select class="form-select" wire:model.defer="genre">
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                </div>

                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-check2-circle"></i></span>
                        <select class="form-select" wire:model.defer="statut_nu">
                            <option value="en règle">en règle</option>
                            <option value="non en règle">non en règle</option>
                        </select>
                    </div>
                    @error('statut_nu')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 d-flex justify-content-end gap-2">
                    @if ($editeId)
                        <button type="button" class="btn btn-light" wire:click="annulerEdition">Annuler</button>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-{{ $editeId ? 'check-lg' : 'plus-lg' }} me-1"></i>{{ $editeId ? 'Enregistrer' : 'Ajouter' }}
                    </button>
                </div>
            </form>
        </x-ui.card>
    </div>

    {{-- ============ Liste des unités (cards + filtres) ============ --}}
    <div class="col-12 col-lg-8">
        <x-ui.card title="Consulter les nouvelles unités">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="filtre-unites-nom" class="form-control" placeholder="Filtrer par nom..." aria-label="Filtrer par nom">
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text border-end-0">COMP-CNTRL-</span>
                        <input type="text" id="filtre-unites-code" class="form-control border-start-0"
                               placeholder="Filtrer par N° (ex : 3)" aria-label="Filtrer par code fidèle" inputmode="numeric">
                    </div>
                </div>
            </div>

            <div class="row g-3" id="unites-cards">
                @forelse ($unites as $unite)
                    <div class="col-12 col-sm-6 col-lg-6" wire:key="unite-{{ $unite['id'] }}"
                         data-filtre-unit
                         data-nom="{{ $unite['nom'] }}"
                         data-code="{{ $unite['code_fidele'] }}"
                         data-statut="{{ $unite['statut_nu'] }}">
                        <div class="card card-consulter h-100 p-2">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <span class="fw-semibold">{{ $unite['code_fidele'] }}</span>
                                <x-ui.badge :variant="$statutVariants[$unite['statut_nu']] ?? 'secondary'">{{ $unite['statut_nu'] }}</x-ui.badge>
                            </div>
                            <div class="card-body">
                                <h6 class="fw-semibold mb-1">{{ $unite['nom'] }}</h6>
                                @if (! empty($unite['code_nu']))
                                    <p class="text-muted small mb-1"><i class="bi bi-credit-card me-1"></i>{{ $unite['code_nu'] }}</p>
                                @endif
                                <p class="text-muted small mb-1">
                                    <i class="bi bi-{{ $unite['genre'] === 'F' ? 'gender-female' : 'gender-male' }} me-1"></i>{{ $genreVariants[$unite['genre']] ?? $unite['genre'] }}
                                    @if (! empty($unite['date_naissance']))
                                        <span class="ms-2"><i class="bi bi-calendar3 me-1"></i>{{ $unite['date_naissance'] }}</span>
                                    @endif
                                </p>
                                <p class="text-muted small mb-1"><i class="bi bi-telephone me-1"></i>{{ $unite['telephone'] ?? '—' }}</p>
                                <p class="text-muted small mb-0"><i class="bi bi-geo-alt me-1"></i>{{ $unite['adresse'] ?? '—' }}</p>
                                @if (! empty($unite['grace']))
                                    <p class="text-muted small mb-0 mt-1"><i class="bi bi-stars me-1"></i>{{ $unite['grace'] }}</p>
                                @endif
                                @if (! empty($unite['statut_nu']))
                                    <p class="text-muted small mb-0 mt-1"><i class="bi bi-stars me-1"></i>{{ $unite['statut_nu'] }}</p>
                                @endif
                            </div>
                            <div class="card-footer bg-white d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-sm btn-light" title="Éditer" wire:click="editer({{ $unite['id'] }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Supprimer"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-supprimer"
                                        data-confirm-delete="{{ route('fideles.destroy', ['fidele' => $unite['id']]) }}"
                                        data-confirm-label="{{ $unite['nom'] }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p class="mb-0 mt-2">Aucune nouvelle unité à afficher pour le moment.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </x-ui.card>
    </div>
</div>