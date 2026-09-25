@php
    $seanceDuJour = collect($seances ?? [])->first(fn ($s) => ($s['date_seance'] ?? null) === now()->format('d/m/Y'));
@endphp

{{-- ===== Enregistrement d'une présence (séance du jour par défaut) ===== --}}
<x-ui.modal id="modal-presence-saisie" title="Enregistrer une présence" size="md">
    {{-- Séance du jour : rattachée automatiquement, aucun champ à choisir --}}
    <div class="alert alert-info d-flex align-items-center gap-2 py-2 px-3 mb-3">
        <i class="bi bi-calendar2-check fs-5"></i>
        <div class="small">
            @if ($seanceDuJour)
                Séance du jour automatiquement ciblée :
                <strong>Séance n°{{ $seanceDuJour['numero_seance'] }} — {{ $seanceDuJour['date_seance'] }}</strong>
            @else
                <strong>Aucune séance prévue aujourd'hui.</strong> L'enregistrement est momentanément indisponible.
            @endif
        </div>
    </div>

    {{-- 1) Saisie directe du code fidèle (voie prioritaire) --}}
    <div class="mb-4">
        <h6 class="fw-semibold mb-2">
            <span class="badge bg-primary me-1">1</span><i class="bi bi-keyboard me-1 text-primary"></i>Par code du fidèle
        </h6>
        <form id="form-presence-code" class="row g-2" data-url="{{ route('presences.store') }}">
            @csrf
            <div class="col-12">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-hash"></i></span>
                    <input type="text" class="form-control" name="code_fidele" placeholder="Code fidèle (ex. COMP-CNTRL-6) *" required>
                </div>
            </div>
        </form>
        <div id="presence-code-feedback" class="small mt-2 d-none"></div>
        <button type="submit" form="form-presence-code" class="btn btn-primary mt-2"><i class="bi bi-check2-circle me-1"></i>Enregistrer</button>
    </div>

    <hr>

    {{-- 2) Scanner de QR code (voie secondaire) --}}
    <div>
        <h6 class="fw-semibold mb-2">
            <span class="badge bg-secondary me-1">2</span><i class="bi bi-qr-code-scan me-1 text-primary"></i>Scanner un QR code
        </h6>
        <div class="text-center">
            <div id="qr-reader" class="mb-3"></div>
            <div id="qr-recap" class="alert alert-success d-none mb-3 text-start">
                <div class="fw-semibold" id="qr-participant">—</div>
            </div>
            <div id="qr-enregistrer-feedback" class="small mt-2 d-none text-start"></div>
            <div class="d-flex justify-content-center gap-2 mt-2">
                <button type="button" class="btn btn-outline-primary" id="qr-start-btn"><i class="bi bi-camera me-1"></i>Ouvrir la caméra</button>
                <button type="button" class="btn btn-outline-secondary d-none" id="qr-stop-btn"><i class="bi bi-stop me-1"></i>Arrêter</button>
                <button type="button" class="btn btn-primary" id="qr-enregistrer-btn" disabled><i class="bi bi-check2-circle me-1"></i>Enregistrer la présence</button>
            </div>
        </div>
    </div>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
    </x-slot:footer>
</x-ui.modal>

{{-- ===== Statistiques des présences par séance ===== --}}
<x-ui.modal id="modal-presence-stats" title="Statistiques des présences" size="xl">
    <div class="row g-2 mb-3">
        <div class="col-12 col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control" placeholder="Filtrer par séance..." aria-label="Filtrer les stats" id="filtre-stats-presence">
            </div>
        </div>
    </div>
    <div class="row g-3" id="presence-stats-cards">
        @forelse ($statsPresences as $stat)
            <div class="col-12 col-sm-6 col-lg-4" data-filtre-pres data-date="{{ $stat['date_seance'] }}">
                <div class="card card-consulter h-100">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-2"><i class="bi bi-calendar-event me-1 text-primary"></i>Séance du {{ $stat['date_seance'] }}</h6>
                        <p class="text-muted small mb-2">Programme n°{{ $stat['programme'] }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Présents</span>
                            <span class="fw-semibold text-success">{{ $stat['present'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Absents</span>
                            <span class="fw-semibold text-danger">{{ $stat['absent'] }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: {{ $stat['taux'] }}%"></div>
                        </div>
                        <div class="text-muted small mt-1">Taux de présence : <strong>{{ $stat['taux'] }}%</strong></div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-bar-chart-line"></i>
                    <p class="mb-0 mt-2">Aucune statistique de présence pour le moment.</p>
                </div>
            </div>
        @endforelse
    </div>
    <x-slot:footer>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
    </x-slot:footer>
</x-ui.modal>