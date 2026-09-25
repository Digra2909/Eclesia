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

{{-- ============ AJOUTER une nouvelle unité ============ --}}
<x-ui.modal id="modal-unites-ajouter" title="Ajouter une nouvelle unité" size="lg">
    <form id="form-unites-ajouter" class="row g-3" action="{{ route('nouvel-unite.store') }}" method="POST">
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
        <div class="col-12 field-hint">
            <i class="bi bi-info-circle me-1"></i>Les codes (fidèle, NU) et le QR code sont générés automatiquement.
        </div>

        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control" name="nom" placeholder="Nom *" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                <input type="text" class="form-control" name="postnom" placeholder="Post-nom *" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                <input type="text" class="form-control" name="prenom" placeholder="Prénom *" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                <input type="date" class="form-control" name="date_naissance" placeholder="Date de naissance">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                <input type="tel" class="form-control" name="telephone" maxlength="13" placeholder="Téléphone (WhatsApp)">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                <select class="form-select" name="genre" required>
                    <option value="" selected disabled>Genre *</option>
                    @foreach ($genreVariants as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-check2-circle"></i></span>
                <select class="form-select" name="statut_nu" required>
                    <option value="" selected disabled>Statut NU *</option>
                    <option>en règle</option>
                    <option>non en règle</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-stars"></i></span>
                <input type="text" class="form-control" name="grace" placeholder="Grâce (service, don)">
            </div>
        </div>
    </form>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" form="form-unites-ajouter" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Ajouter</button>
    </x-slot:footer>
</x-ui.modal>

{{-- ============ CONSULTER les nouvelles unités (cards + filtres) ============ --}}
<x-ui.modal id="modal-unites-consulter" title="Consulter les nouvelles unités" size="xl">
    <div class="row g-2 mb-3">
        <div class="col-12 col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search text-muted"></i></span>
                <input type="text" id="filtre-unites-nom" class="form-control" placeholder="Filtrer par nom..." aria-label="Filtrer par nom">
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-funnel text-muted"></i></span>
                <select id="filtre-unites-statut" class="form-select" aria-label="Filtrer par statut">
                    <option value="">Tous les statuts</option>
                    <option>en règle</option>
                    <option>non en règle</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row g-3" id="unites-cards">
        @forelse ($unites as $unite)
            <div class="col-12 col-sm-6 col-lg-4"
                 data-filtre-unit
                 data-nom="{{ $unite['nom'] }}"
                 data-statut="{{ $unite['statut_nu'] }}">
                <div class="card card-consulter h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">{{ $unite['code_fidele'] ?? $unite['nom'] }}</span>
                        <x-ui.badge :variant="$statutVariants[$unite['statut_nu']] ?? 'secondary'">{{ $unite['statut_nu'] }}</x-ui.badge>
                    </div>
                    <div class="card-body">
                        <h6 class="fw-semibold mb-1">{{ $unite['nom'] }}</h6>
                        <p class="text-muted small mb-1"><i class="bi bi-telephone me-1"></i>{{ $unite['telephone'] ?? '—' }}</p>
                        <p class="text-muted small mb-0">
                            <i class="bi {{ $unite['genre'] === 'F' ? 'bi-gender-female' : 'bi-gender-male' }} me-1"></i>{{ $genreVariants[$unite['genre']] ?? $unite['genre'] }}
                            @if (! empty($unite['grace']))
                                <span class="ms-2"><i class="bi bi-stars me-1"></i>{{ $unite['grace'] }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-light" title="Éditer"
                                data-bs-toggle="modal" data-bs-target="#modal-unites-editer"
                                data-bs-modal-fill
                                data-action="{{ route('fideles.update', ['fidele' => $unite['id']]) }}"
                                data-nom="{{ $unite['nom'] }}"
                                data-code="{{ $unite['code_fidele'] ?? '' }}"
                                data-genre="{{ $unite['genre'] }}"
                                data-telephone="{{ $unite['telephone'] ?? '' }}"
                                data-statut_nu="{{ $unite['statut_nu'] }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer"
                                data-bs-toggle="modal" data-bs-target="#modal-unites-supprimer"
                                data-bs-modal-fill
                                data-action="{{ route('fideles.destroy', ['fidele' => $unite['id']]) }}"
                                data-nom="{{ $unite['nom'] }}"
                                data-code="{{ $unite['code_fidele'] ?? '' }}">
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
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
    </x-slot:footer>
</x-ui.modal>

{{-- ============ ÉDITER une nouvelle unité ============ --}}
<x-ui.modal id="modal-unites-editer" title="Éditer l'unité" size="lg">
    <form class="row g-3">
        @csrf
        <input type="hidden" name="code">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control" name="nom" placeholder="Nom *" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                <input type="tel" class="form-control" name="telephone" placeholder="Téléphone (WhatsApp)">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                <select class="form-select" name="genre">
                    <option>M</option>
                    <option>F</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-check2-circle"></i></span>
                <select class="form-select" name="statut_nu">
                    <option>en règle</option>
                    <option>non en règle</option>
                </select>
            </div>
        </div>
    </form>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
    </x-slot:footer>
</x-ui.modal>

{{-- ============ SUPPRIMER une nouvelle unité ============ --}}
<x-ui.modal id="modal-unites-supprimer" title="Confirmer la suppression" size="sm">
    <p class="mb-0">
        Voulez-vous vraiment supprimer l'unité <strong data-field="nom"></strong>
        (code <strong data-field="code"></strong>) ? Cette action est irréversible.
    </p>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-danger" data-confirm-submit><i class="bi bi-trash me-1"></i>Supprimer</button>
    </x-slot:footer>
</x-ui.modal>