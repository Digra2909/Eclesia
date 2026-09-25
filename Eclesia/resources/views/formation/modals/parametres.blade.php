{{-- ===== Paramètres (CRUD : statuts, types, cours, postes) ===== --}}
<x-ui.modal id="modal-parametres" title="Paramètres" size="xl">
    <div class="row g-3">
        {{-- Statuts des fidèles --}}
        <div class="col-12 col-lg-6">
            <x-ui.card>
                <x-slot:header>
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-semibold mb-0"><i class="bi bi-person-check me-1 text-primary"></i>Statuts des fidèles</h6>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-crud-statut"><i class="bi bi-plus-lg"></i></button>
                    </div>
                </x-slot:header>
                <ul class="list-group list-group-flush">
                    @forelse ($statutsFideles as $id => $designation)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $designation }}
                            <span class="d-inline-flex gap-1">
                                <button type="button" class="btn btn-sm btn-light" data-crud-edit
                                        data-crud-form="#form-crud-statut"
                                        data-action="{{ route('statut-fideles.update', ['statut_fidele' => $id]) }}"
                                        data-fields='{"designation": "{{ $designation }}"}'>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('statut-fideles.destroy', ['statut_fidele' => $id]) }}" method="POST" onsubmit="return confirm('Supprimer ce statut ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small">Aucun statut.</li>
                    @endforelse
                </ul>
            </x-ui.card>
        </div>

        {{-- Types d'intervention --}}
        <div class="col-12 col-lg-6">
            <x-ui.card>
                <x-slot:header>
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-semibold mb-0"><i class="bi bi-chat-left-dots me-1 text-primary"></i>Types d'intervention</h6>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-crud-type"><i class="bi bi-plus-lg"></i></button>
                    </div>
                </x-slot:header>
                <ul class="list-group list-group-flush">
                    @forelse ($typeInterventions as $id => $designation)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $designation }}
                            <span class="d-inline-flex gap-1">
                                <button type="button" class="btn btn-sm btn-light" data-crud-edit
                                        data-crud-form="#form-crud-type"
                                        data-action="{{ route('type-interventions.update', ['type_intervention' => $id]) }}"
                                        data-fields='{"designation": "{{ $designation }}"}'>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('type-interventions.destroy', ['type_intervention' => $id]) }}" method="POST" onsubmit="return confirm('Supprimer ce type ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small">Aucun type d'intervention.</li>
                    @endforelse
                </ul>
            </x-ui.card>
        </div>

        {{-- Cours --}}
        <div class="col-12 col-lg-6">
            <x-ui.card>
                <x-slot:header>
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-semibold mb-0"><i class="bi bi-book me-1 text-primary"></i>Cours</h6>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-crud-cours"><i class="bi bi-plus-lg"></i></button>
                    </div>
                </x-slot:header>
                <ul class="list-group list-group-flush">
                    @forelse ($cours as $coursItem)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $coursItem['passages'] }}
                            <span class="d-inline-flex gap-1">
                                <button type="button" class="btn btn-sm btn-light" data-crud-edit
                                        data-crud-form="#form-crud-cours"
                                        data-action="{{ route('cours.update', ['cour' => $coursItem['id']]) }}"
                                        data-fields='{"passages": "{{ $coursItem['passages'] }}"}'>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('cours.destroy', ['cour' => $coursItem['id']]) }}" method="POST" onsubmit="return confirm('Supprimer ce cours ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small">Aucun cours.</li>
                    @endforelse
                </ul>
            </x-ui.card>
        </div>

        {{-- Postes --}}
        <div class="col-12 col-lg-6">
            <x-ui.card>
                <x-slot:header>
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-semibold mb-0"><i class="bi bi-briefcase me-1 text-primary"></i>Postes</h6>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-crud-poste"><i class="bi bi-plus-lg"></i></button>
                    </div>
                </x-slot:header>
                <ul class="list-group list-group-flush">
                    @forelse ($postes as $id => $designation)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $designation }}
                            <span class="d-inline-flex gap-1">
                                <button type="button" class="btn btn-sm btn-light" data-crud-edit
                                        data-crud-form="#form-crud-poste"
                                        data-action="{{ route('postes.update', ['poste' => $id]) }}"
                                        data-fields='{"designation": "{{ $designation }}"}'>
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('postes.destroy', ['poste' => $id]) }}" method="POST" onsubmit="return confirm('Supprimer ce poste ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted small">Aucun poste.</li>
                    @endforelse
                </ul>
            </x-ui.card>
        </div>
    </div>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
    </x-slot:footer>
</x-ui.modal>

{{-- ===== Modales CRUD des paramètres ===== --}}
<x-ui.modal id="modal-crud-statut" title="Statut de fidèle" size="md">
    <form id="form-crud-statut" class="row g-3" action="{{ route('statut-fideles.store') }}" method="POST">
        @csrf
        <div class="col-12">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-check"></i></span>
                <input type="text" name="designation" class="form-control" placeholder="Désignation *" required>
            </div>
        </div>
    </form>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-crud-reset data-crud-form="#form-crud-statut">Annuler</button>
        <button type="submit" form="form-crud-statut" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
    </x-slot:footer>
</x-ui.modal>

<x-ui.modal id="modal-crud-type" title="Type d'intervention" size="md">
    <form id="form-crud-type" class="row g-3" action="{{ route('type-interventions.store') }}" method="POST">
        @csrf
        <div class="col-12">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-chat-left-dots"></i></span>
                <input type="text" name="designation" class="form-control" placeholder="Désignation *" required>
            </div>
        </div>
    </form>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-crud-reset data-crud-form="#form-crud-type">Annuler</button>
        <button type="submit" form="form-crud-type" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
    </x-slot:footer>
</x-ui.modal>

<x-ui.modal id="modal-crud-cours" title="Cours (passages)" size="md">
    <form id="form-crud-cours" class="row g-3" action="{{ route('cours.store') }}" method="POST">
        @csrf
        <div class="col-12">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-book"></i></span>
                <input type="text" name="passages" class="form-control" placeholder="Thème / versets (passages) *" required>
            </div>
        </div>
    </form>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-crud-reset data-crud-form="#form-crud-cours">Annuler</button>
        <button type="submit" form="form-crud-cours" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
    </x-slot:footer>
</x-ui.modal>

<x-ui.modal id="modal-crud-poste" title="Poste" size="md">
    <form id="form-crud-poste" class="row g-3" action="{{ route('postes.store') }}" method="POST">
        @csrf
        <div class="col-12">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                <input type="text" name="designation" class="form-control" placeholder="Désignation *" required>
            </div>
        </div>
    </form>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-crud-reset data-crud-form="#form-crud-poste">Annuler</button>
        <button type="submit" form="form-crud-poste" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
    </x-slot:footer>
</x-ui.modal>