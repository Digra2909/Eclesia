{{-- ============ AJOUTER des séances (multi-lignes) ============ --}}
<x-ui.modal id="modal-seance-ajouter" title="Ajouter des séances" size="xl">
    <form class="row g-3" id="form-seances-ajouter" action="{{ route('seances.batch') }}" method="POST">
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
                <span class="input-group-text"><i class="bi bi-mortarboard"></i></span>
                <select class="form-select" name="programme_id" id="seances-programme" required>
                    <option value="" selected disabled>Programme *</option>
                    @foreach ($programmes as $programme)
                        <option value="{{ $programme['id'] }}">{{ $programme['libelle'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Bloc modèle (cloné à chaque clic sur "+") --}}
        <div class="col-12 seance-row" data-seance-row>
            <div class="card border-0 shadow-sm">
                <div class="card-header py-2 bg-white d-flex justify-content-between align-items-center">
                    <span class="small text-muted" data-seance-num>Séance 1</span>
                    <button type="button" class="btn btn-sm btn-outline-danger" data-seance-retirer title="Retirer"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="card-body row g-2">
                    <div class="col-6 col-md-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-hash"></i></span>
                            <input type="number" min="1" class="form-control" name="seances[][numero_seance]" placeholder="N° *" required>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                            <input type="date" class="form-control" name="seances[][date_seance]" placeholder="Date *" required>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-clock"></i></span>
                            <input type="time" class="form-control" name="seances[][heure_debut]" placeholder="Début *" required>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-clock-history"></i></span>
                            <input type="time" class="form-control" name="seances[][heure_fin]" placeholder="Fin">
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" class="form-control" name="seances[][lieu]" placeholder="Lieu (défaut : temple de l'église)">
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-hourglass-split"></i></span>
                            <input type="number" min="0" class="form-control" name="seances[][delai_rappel]" placeholder="Délai de rappel (jours)">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="seances-conteneur"></div>

        <div class="col-12">
            <button type="button" class="btn btn-outline-primary w-100" id="seances-add"><i class="bi bi-plus-lg me-1"></i>Ajouter une autre séance</button>
        </div>
    </form>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" form="form-seances-ajouter" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Enregistrer les séances</button>
    </x-slot:footer>
</x-ui.modal>

{{-- ============ CONSULTER les séances (tableau + filtre) ============ --}}
<x-ui.modal id="modal-seance-consulter" title="Consulter les séances" size="xl">
    <div class="row g-2 mb-3">
        <div class="col-12 col-md-7">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control" placeholder="Filtrer par programme, lieu, date..." aria-label="Filtrer les séances"
                       data-table-filter="#table-seances">
            </div>
        </div>
        <div class="col-12 col-md-5 text-md-end">
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-seance-ajouter">
                <i class="bi bi-plus-lg me-1"></i>Ajouter des séances
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="table-seances">
            <thead class="table-light">
                <tr>
                    <th>N°</th>
                    <th>Programme</th>
                    <th>Date</th>
                    <th>Heures</th>
                    <th>Lieu</th>
                    <th>Rappel</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($seances as $seance)
                    <tr>
                        <td><span class="fw-medium">{{ $seance['numero_seance'] }}</span></td>
                        <td>{{ $seance['programme'] }}</td>
                        <td>{{ $seance['date_seance'] }}</td>
                        <td>{{ $seance['heure_debut'] }} – {{ $seance['heure_fin'] }}</td>
                        <td>{{ $seance['lieu'] }}</td>
                        <td>{{ $seance['delai_rappel'] ?? '—' }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer"
                                        data-bs-toggle="modal" data-bs-target="#modal-seance-supprimer"
                                        data-bs-modal-fill
                                        data-action="{{ route('seances.destroy', ['seance' => $seance['id']]) }}"
                                        data-progr="{{ $seance['programme'] }}" data-numero_seance="{{ $seance['numero_seance'] }}" data-date_seance="{{ $seance['date_seance'] }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-calendar-x"></i>
                                <p class="mb-0 mt-2">Aucune séance à afficher pour le moment.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <p class="text-muted small mt-2 mb-0">Accessible depuis « Consulter les programmes » (bouton Gérer les séances).</p>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
    </x-slot:footer>
</x-ui.modal>

{{-- ============ SUPPRIMER une séance ============ --}}
<x-ui.modal id="modal-seance-supprimer" title="Confirmer la suppression" size="sm">
    <p class="mb-0">
        Voulez-vous vraiment supprimer la séance <strong data-field="numero_seance"></strong>
        du programme <strong data-field="progr"></strong> (le <strong data-field="date_seance"></strong>) ?
    </p>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-danger" data-confirm-submit><i class="bi bi-trash me-1"></i>Supprimer</button>
    </x-slot:footer>
</x-ui.modal>