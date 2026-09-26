<x-ui.card :title="$config['libelle']" :footer="count($elements).' élément(s)'">
    @if ($message)
        <div class="alert alert-success py-2 small mb-3">
            <i class="bi bi-check-circle me-1"></i>{{ $message }}
        </div>
    @endif

    @error('valeur')
        <div class="alert alert-danger py-2 small mb-3">
            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
        </div>
    @enderror

    <form wire:submit.prevent="enregistrer" class="row g-2 mb-3">
        <div class="col-12">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-plus-lg"></i></span>
                <input type="text" class="form-control" wire:model.defer="valeur" placeholder="Nouveau libellé" autocomplete="off">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-{{ $editeId ? 'check-lg' : 'plus-lg' }} me-1"></i>{{ $editeId ? 'Mettre à jour' : 'Ajouter' }}
                </button>
            </div>
        </div>
        @if ($editeId)
            <div class="col-12">
                <button type="button" class="btn btn-sm btn-light" wire:click="annulerEdition">
                    <i class="bi bi-x-circle me-1"></i>Annuler la modification
                </button>
            </div>
        @endif
    </form>

    <ul class="list-group list-group-flush">
        @forelse ($elements as $id => $libelle)
            <li class="list-group-item d-flex justify-content-between align-items-center px-0" wire:key="element-{{ $id }}">
                <span>
                    <i class="bi bi-dot me-1 text-muted"></i>{{ $libelle }}
                    @if ($editeId === (int) $id)
                        <span class="badge bg-warning-subtle text-warning ms-1">en cours d'édition</span>
                    @endif
                </span>
                <span class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-light"
                            wire:click="editer({{ $id }}, {{ json_encode($libelle) }})">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button type="button"
                            class="btn btn-sm btn-outline-danger"
                            title="Supprimer"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-supprimer"
                            data-confirm-delete="{{ $this->routeDestruction($id) }}"
                            data-confirm-label="{{ $libelle }}">
                        <i class="bi bi-trash"></i>
                    </button>
                </span>
            </li>
        @empty
            <li class="list-group-item px-0">
                <div class="empty-state py-3">
                    <i class="bi bi-inbox"></i>
                    <p class="mb-0 mt-2">Aucun élément pour le moment.</p>
                </div>
            </li>
        @endforelse
    </ul>
</x-ui.card>