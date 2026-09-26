@php
    $statutVariantsCours = [
        'passages' => 'primary',
    ];
@endphp

{{-- ============ AJOUTER un cours ============ --}}
<x-ui.modal id="modal-cours-ajouter" title="Ajouter un cours" size="md">
    <form class="row g-3" id="form-cours-ajouter" action="{{ route('cours.store') }}" method="POST">
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
                <span class="input-group-text"><i class="bi bi-book"></i></span>
                <input type="text" class="form-control" name="passages" placeholder="Thème / versets (passages) *" required>
            </div>
        </div>
    </form>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" form="form-cours-ajouter" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Ajouter</button>
    </x-slot:footer>
</x-ui.modal>

{{-- ============ CONSULTER les cours (cards + filtre) ============ --}}
<x-ui.modal id="modal-cours-consulter" title="Consulter les cours" size="xl">
    <div class="row g-2 mb-3">
        <div class="col-12 col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control" placeholder="Filtrer par thème / versets..." aria-label="Filtrer les cours">
            </div>
        </div>
    </div>

    <div class="row g-3" id="cours-cards">
        @forelse ($cours as $coursItem)
            <div class="col-12 col-sm-6 col-lg-4" data-filtre-cours data-passages="{{ $coursItem['passages'] }}">
                <div class="card card-consulter h-100">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-1"><i class="bi bi-book me-1 text-primary"></i>{{ $coursItem['passages'] }}</h6>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-light" title="Éditer"
                                data-bs-toggle="modal" data-bs-target="#modal-cours-editer"
                                data-bs-modal-fill
                                data-action="{{ route('cours.update', ['cour' => $coursItem['id']]) }}"
                                data-passages="{{ $coursItem['passages'] }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer"
                                data-bs-toggle="modal" data-bs-target="#modal-cours-supprimer"
                                data-bs-modal-fill
                                data-action="{{ route('cours.destroy', ['cour' => $coursItem['id']]) }}"
                                data-passages="{{ $coursItem['passages'] }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-book"></i>
                    <p class="mb-0 mt-2">Aucun cours à afficher pour le moment.</p>
                </div>
            </div>
        @endforelse
    </div>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-cours-ajouter">
            <i class="bi bi-plus-lg me-1"></i>Ajouter un cours
        </button>
    </x-slot:footer>
</x-ui.modal>

{{-- ============ ÉDITER un cours ============ --}}
<x-ui.modal id="modal-cours-editer" title="Éditer le cours" size="md">
    <form class="row g-3" id="form-cours-editer" method="POST">
        @csrf
        @method('PUT')
        <div class="col-12">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-book"></i></span>
                <input type="text" class="form-control" name="passages" placeholder="Thème / versets (passages) *" required>
            </div>
        </div>
    </form>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" form="form-cours-editer" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Enregistrer</button>
    </x-slot:footer>
</x-ui.modal>

{{-- ============ SUPPRIMER un cours ============ --}}
<x-ui.modal id="modal-cours-supprimer" title="Confirmer la suppression" size="sm">
    <p class="mb-0">
        Voulez-vous vraiment supprimer le cours <strong data-field="passages"></strong> ?
    </p>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-danger" data-confirm-submit><i class="bi bi-trash me-1"></i>Supprimer</button>
    </x-slot:footer>
</x-ui.modal>

{{-- ============ ASSOCIER des cours à un programme (étape avant les séances) ============ --}}
<x-ui.modal id="modal-cours-selection" title="Associer les cours du programme" size="lg">
    <input type="hidden" id="cours-programme-url" value="{{ route('programmes.cours', ['programme' => '__PROG__']) }}">

    @if ($errors->any())
        <div class="alert alert-danger py-2 small mb-3">
            @foreach ($errors->all() as $error)
                <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form id="form-cours-programme" method="POST">
        @csrf
        <div class="alert alert-primary small py-2 d-flex align-items-center mb-3">
            <i class="bi bi-info-circle me-2"></i>Cochez les cours de ce programme (ou ajoutez-en un), puis passez aux séances.
        </div>

        <div class="list-group mb-3" id="cours-programme-liste">
            @forelse ($cours as $coursItem)
                <label class="list-group-item d-flex align-items-center gap-3 mb-1 border rounded-3">
                    <input class="form-check-input m-0" type="checkbox" name="cours_ids[]" value="{{ $coursItem['id'] }}">
                    <span class="cours-passages"><i class="bi bi-book me-1 text-primary"></i>{{ $coursItem['passages'] }}</span>
                </label>
            @empty
                <div class="text-muted small">Aucun cours existant : ajoutez-en un ci-dessous.</div>
            @endforelse
        </div>
    </form>

    <template id="cours-programme-template">
        <label class="list-group-item d-flex align-items-center gap-3 mb-1 border rounded-3">
            <input class="form-check-input m-0" type="checkbox" name="cours_ids[]">
            <span class="cours-passages"></span>
        </label>
    </template>

    <form id="form-cours-rapide" method="POST" class="d-flex gap-2">
        @csrf
        <input type="text" class="form-control" id="cours-rapide-passages" placeholder="Nouveau thème / versets..."
               maxlength="150" aria-label="Nouveau cours à créer et associer">
        <button type="button" class="btn btn-outline-primary text-nowrap" id="cours-rapide-submit">
            <i class="bi bi-plus-lg me-1"></i>Ajouter & associer
        </button>
    </form>
    <div id="cours-rapide-feedback" class="small mt-1"></div>

    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" form="form-cours-programme" class="btn btn-primary">
            <i class="bi bi-arrow-right-circle me-1"></i>Passer aux séances
        </button>
    </x-slot:footer>
</x-ui.modal>