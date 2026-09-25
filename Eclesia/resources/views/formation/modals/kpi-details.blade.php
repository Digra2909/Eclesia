{{-- ===== Modales de détail des KPI (au clic sur une carte) ===== --}}

{{-- Fidèles ouvriers --}}
<x-ui.modal id="modal-kpi-ouvriers" title="Fidèles ouvriers" size="xl">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Code ouvrier</th>
                    <th>Ouvrier</th>
                    <th>Code fidèle</th>
                    <th>Poste</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ouvriers as $ouvrier)
                    <tr>
                        <td><span class="fw-medium">{{ $ouvrier['code_ouvrier'] }}</span></td>
                        <td>{{ $ouvrier['nom'] }}</td>
                        <td>{{ $ouvrier['code_fidele'] }}</td>
                        <td>{{ $ouvrier['poste'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="bi bi-people"></i>
                                <p class="mb-0 mt-2">Aucun ouvrier enregistré pour le moment.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
    </x-slot:footer>
</x-ui.modal>

{{-- Nouvelles Unités (NU) --}}
<x-ui.modal id="modal-kpi-nus" title="Nouvelles Unités (NU)" size="xl">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Code NU</th>
                    <th>Fidèle</th>
                    <th class="text-center">Orale</th>
                    <th class="text-center">Écrite</th>
                    <th class="text-center">%</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($nuCotations as $nu)
                    <tr>
                        <td><span class="fw-medium">{{ $nu['code_nu'] }}</span></td>
                        <td>{{ $nu['fidele'] }}</td>
                        <td class="text-center">{{ $nu['note_oral'] ?? '—' }}</td>
                        <td class="text-center">{{ $nu['note_ecrite'] ?? '—' }}</td>
                        <td class="text-center">{{ $nu['pourcentage'] ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="bi bi-journal-text"></i>
                                <p class="mb-0 mt-2">Aucune nouvelle unité pour le moment.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
    </x-slot:footer>
</x-ui.modal>

{{-- Programmes en attente de validation (statut « créé ») --}}
<x-ui.modal id="modal-kpi-attente" title="Programmes en attente de validation" size="xl">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Intitulé</th>
                    <th>Type</th>
                    <th class="text-center">Séances</th>
                    <th class="text-end">Montant</th>
                    <th class="text-center">Créé le</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($programmesAttente as $programme)
                    <tr>
                        <td><span class="fw-medium">{{ $programme['libelle'] }}</span></td>
                        <td>{{ $programme['type'] ?? '—' }}</td>
                        <td class="text-center">{{ $programme['nb_seances'] }}</td>
                        <td class="text-end">{{ number_format((int) $programme['montant'], 0, ',', ' ') }} FCFA</td>
                        <td class="text-center">{{ $programme['date_creation'] ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="bi bi-hourglass-split"></i>
                                <p class="mb-0 mt-2">Aucun programme en attente de validation.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
    </x-slot:footer>
</x-ui.modal>

{{-- Programmes validés --}}
<x-ui.modal id="modal-kpi-valides" title="Programmes validés" size="xl">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Intitulé</th>
                    <th>Type</th>
                    <th class="text-center">Séances</th>
                    <th class="text-end">Montant</th>
                    <th class="text-center">Créé le</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($programmesValidesListe as $programme)
                    <tr>
                        <td><span class="fw-medium">{{ $programme['libelle'] }}</span></td>
                        <td>{{ $programme['type'] ?? '—' }}</td>
                        <td class="text-center">{{ $programme['nb_seances'] }}</td>
                        <td class="text-end">{{ number_format((int) $programme['montant'], 0, ',', ' ') }} FCFA</td>
                        <td class="text-center">{{ $programme['date_creation'] ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="bi bi-check2-circle"></i>
                                <p class="mb-0 mt-2">Aucun programme validé pour le moment.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
    </x-slot:footer>
</x-ui.modal>