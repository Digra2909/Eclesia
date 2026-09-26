<div class="row g-3">
    <div class="col-12 col-lg-4">
        <x-ui.card :title="$editeId ? 'Modifier le cours' : 'Ajouter un cours'">
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

            <form wire:submit.prevent="enregistrer" class="g-3">
                <div class="mb-3">
                    <label class="form-label">Passages (thème ou versets) *</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-book"></i></span>
                        <input type="text" class="form-control" wire:model.defer="passages" placeholder="Ex : La foi d'Abraham" autocomplete="off">
                    </div>
                    @error('passages')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
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

    <div class="col-12 col-lg-8">
        <x-ui.card title="Liste des cours" :footer="count($cours).' cours au total'">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" data-table-filter="#table-cours" data-cols="1"
                               class="form-control" placeholder="Filtrer par passages..." aria-label="Filtrer">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="table-cours">
                    <thead>
                        <tr>
                            <th class="text-muted small">N°</th>
                            <th class="text-muted small">Passages</th>
                            <th class="text-muted small">Programmes</th>
                            <th class="text-muted small text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cours as $index => $entree)
                            <tr wire:key="cours-{{ $entree['id'] }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $entree['passages'] }}</td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary">{{ $entree['nb_programmes'] }}</span>
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-light" title="Éditer" wire:click="editer({{ $entree['id'] }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Supprimer"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal-supprimer"
                                            data-confirm-delete="{{ route('cours.destroy', ['cours' => $entree['id']]) }}"
                                            data-confirm-label="{{ $entree['passages'] }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state py-4">
                                        <i class="bi bi-inbox"></i>
                                        <p class="mb-0 mt-2">Aucun cours enregistré pour le moment.</p>
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