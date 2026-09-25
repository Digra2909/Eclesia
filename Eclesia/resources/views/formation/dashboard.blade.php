@extends('formation.layouts.app')

@section('title', 'Formation · Tableau de bord')

@section('content')
<div class="d-flex flex-column gap-4">

    {{-- ===== En-tête de page ===== --}}
    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Tableau de bord — Formation</h1>
            <p class="text-muted small mb-0">Dashboard exclusif de l'application <strong>Formation</strong> : unités, cours, programmes, séances, présences et évaluations.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button class="btn btn-primary rounded-pill px-3 d-inline-flex align-items-center gap-2 btn-header-pulse" data-bs-toggle="modal" data-bs-target="#modal-unites-ajouter">
                <i class="bi bi-person-plus-fill fs-6"></i> Nouvelle unité
            </button>
            <button class="btn btn-outline-primary rounded-pill px-3 d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modal-prog-consulter">
                <i class="bi bi-calendar2-range fs-6"></i> Programmes
            </button>
        </div>
    </div>

    {{-- ===== Cartes KPI (survol animé, clic = modale de détail) ===== --}}
    <div class="row g-3">
        <div class="col-12 col-sm-6 col-lg-3">
            <x-ui.stat-card label="Fidèles ouvriers" :value="$totalOuvriers" icon="bi bi-people-fill" variant="primary"
                            data-bs-toggle="modal" data-bs-target="#modal-kpi-ouvriers" />
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <x-ui.stat-card label="Nouvelles Unités (NU)" :value="$totalNus" icon="bi bi-journal-text" variant="success"
                            data-bs-toggle="modal" data-bs-target="#modal-kpi-nus" />
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <x-ui.stat-card label="En attente de validation" :value="$programmesEnAttente" icon="bi bi-hourglass-split" variant="warning"
                            data-bs-toggle="modal" data-bs-target="#modal-kpi-attente" />
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <x-ui.stat-card label="Programmes validés" :value="$programmesValides" icon="bi bi-check2-circle" variant="info"
                            data-bs-toggle="modal" data-bs-target="#modal-kpi-valides" />
        </div>
    </div>

    {{-- ===== Graphiques (agrandis) ===== --}}
    <div class="row g-4">
        <!-- Tendance : % de présences sur les 7 derniers programmes formation ordinaire -->
        <div class="col-12">
            <div class="card border-0 shadow-sm p-3 h-100">
                <h6 class="fw-semibold mb-2">Statut des présences · 7 derniers programmes formation ordinaire</h6>
                <div class="chart-box"><canvas id="sparklineChart"></canvas></div>
            </div>
        </div>

        <!-- Secteur -->
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm p-3 h-100">
                <h6 class="fw-semibold mb-2">Répartition par genre</h6>
                <div class="chart-box"><canvas id="sectorChart"></canvas></div>
            </div>
        </div>

        <!-- Doughnut -->
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm p-3 h-100">
                <h6 class="fw-semibold mb-2">Statut des programmes</h6>
                <div class="chart-box"><canvas id="doughnutChart"></canvas></div>
            </div>
        </div>
    </div>

    {{-- ===== Accès rapide aux modules ===== --}}
    <div class="row g-3">
        <div class="col-12 col-md-6 col-lg-4">
            <x-ui.card>
                <div class="d-flex align-items-center gap-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary" style="width: 48px; height: 48px;"><i class="bi bi-journal-plus fs-4"></i></span>
                    <div>
                        <h6 class="fw-semibold mb-0">Nouvelles unités</h6>
                        <span class="text-muted small">Ajouter / consulter les unités</span>
                    </div>
                    <div class="ms-auto d-flex gap-1">
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-unites-ajouter" title="Ajouter"><i class="bi bi-plus-lg"></i></button>
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal-unites-consulter" title="Consulter"><i class="bi bi-eye"></i></button>
                    </div>
                </div>
            </x-ui.card>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <x-ui.card>
                <div class="d-flex align-items-center gap-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary" style="width: 48px; height: 48px;"><i class="bi bi-book fs-4"></i></span>
                    <div>
                        <h6 class="fw-semibold mb-0">Cours</h6>
                        <span class="text-muted small">Ajouter / consulter les cours</span>
                    </div>
                    <div class="ms-auto d-flex gap-1">
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-cours-ajouter" title="Ajouter"><i class="bi bi-plus-lg"></i></button>
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal-cours-consulter" title="Consulter"><i class="bi bi-eye"></i></button>
                    </div>
                </div>
            </x-ui.card>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <x-ui.card>
                <div class="d-flex align-items-center gap-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary" style="width: 48px; height: 48px;"><i class="bi bi-calendar-event fs-4"></i></span>
                    <div>
                        <h6 class="fw-semibold mb-0">Programmes</h6>
                        <span class="text-muted small">Programmes & séances associées</span>
                    </div>
                    <div class="ms-auto d-flex gap-1">
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-prog-ajouter" title="Ajouter"><i class="bi bi-plus-lg"></i></button>
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal-prog-consulter" title="Consulter"><i class="bi bi-eye"></i></button>
                    </div>
                </div>
            </x-ui.card>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <x-ui.card>
                <div class="d-flex align-items-center gap-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary" style="width: 48px; height: 48px;"><i class="bi bi-clock-history fs-4"></i></span>
                    <div>
                        <h6 class="fw-semibold mb-0">Séances</h6>
                        <span class="text-muted small">Gestion des séances de formation</span>
                    </div>
                    <div class="ms-auto d-flex gap-1">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal-seance-consulter" title="Consulter"><i class="bi bi-eye"></i></button>
                    </div>
                </div>
            </x-ui.card>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <x-ui.card>
                <div class="d-flex align-items-center gap-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary" style="width: 48px; height: 48px;"><i class="bi bi-qr-code-scan fs-4"></i></span>
                    <div>
                        <h6 class="fw-semibold mb-0">Présences</h6>
                        <span class="text-muted small">Enregistrer & statistiques</span>
                    </div>
                    <div class="ms-auto d-flex gap-1">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal-presence-saisie" title="Enregistrer une présence"><i class="bi bi-person-check"></i></button>
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal-presence-stats" title="Statistiques"><i class="bi bi-bar-chart-line"></i></button>
                    </div>
                </div>
            </x-ui.card>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <x-ui.card>
                <div class="d-flex align-items-center gap-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary" style="width: 48px; height: 48px;"><i class="bi bi-pencil-square fs-4"></i></span>
                    <div>
                        <h6 class="fw-semibold mb-0">Évaluation</h6>
                        <span class="text-muted small">Cotation par NU (oral / écrit)</span>
                    </div>
                    <div class="ms-auto d-flex gap-1">
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-eval-cotation" title="Cotation"><i class="bi bi-check2-square"></i></button>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>

    {{-- ===== Modales des modules ===== --}}
    @include('formation.modals.nouvelles-unites', ['unites' => $unites])
    @include('formation.modals.cours', ['cours' => $cours])
    @include('formation.modals.programmes', ['programmes' => $programmes])
    @include('formation.modals.seances', ['programmes' => $programmes, 'seances' => $seances])
    @include('formation.modals.presence', ['statsPresences' => $statsPresences])
    @include('formation.modals.parametres', ['statutsFideles' => $statutsFideles, 'typeInterventions' => $typeInterventions, 'postes' => $postes, 'cours' => $cours])
    @include('formation.modals.kpi-details', ['ouvriers' => $ouvriers, 'nuCotations' => $nusEvaluation, 'programmesAttente' => $programmesAttente, 'programmesValidesListe' => $programmesValidesListe])
    @include('formation.modals.evaluation', ['nuCotations' => $nusEvaluation])

    @php
        $dashboardData = [
            'sparkline' => ['labels' => $sparklineLabels, 'values' => $sparklineData],
            'sector' => $sectorData,
            'doughnut' => $doughnutData,
        ];
    @endphp
    <script type="application/json" id="dashboard-data">@json($dashboardData)</script>

    @if (session('seances_pour_programme'))
        <input type="hidden" id="dashboard-seances-programme" value="{{ session('seances_pour_programme') }}">
    @endif

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
    <script type="module" src="{{ Vite::asset('resources/js/formation/dashboard.js') }}"></script>
</div>
@endsection
