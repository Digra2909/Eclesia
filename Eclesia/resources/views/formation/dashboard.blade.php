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
            <a href="{{ route('unites.index') }}" class="btn btn-primary rounded-pill px-3 d-inline-flex align-items-center gap-2 btn-header-pulse">
                <i class="bi bi-person-plus-fill fs-6"></i> Nouvelle unité
            </a>
            <a href="{{ route('programmes.index') }}" class="btn btn-outline-primary rounded-pill px-3 d-inline-flex align-items-center gap-2">
                <i class="bi bi-calendar2-range fs-6"></i> Programmes
            </a>
        </div>
    </div>

    {{-- ===== Cartes KPI (liens vers les modules) ===== --}}
    <div class="row g-3">
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="{{ route('unites.index') }}" class="text-decoration-none">
                <x-ui.stat-card label="Nouvelles Unités (NU)" :value="$totalNus" icon="bi bi-journal-text" variant="success" trend="Module Unités" />
            </a>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="{{ route('programmes.index') }}" class="text-decoration-none">
                <x-ui.stat-card label="En attente de validation" :value="$programmesEnAttente" icon="bi bi-hourglass-split" variant="warning" trend="Module Programmes" />
            </a>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="{{ route('programmes.index') }}" class="text-decoration-none">
                <x-ui.stat-card label="Programmes validés" :value="$programmesValides" icon="bi bi-check2-circle" variant="info" trend="Module Programmes" />
            </a>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="{{ route('seances.index') }}" class="text-decoration-none">
                <x-ui.stat-card label="Séances planifiées" :value="$seancesPlanifiees" icon="bi bi-calendar-event" variant="primary" trend="Module Séances" />
            </a>
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



    @php
        $dashboardData = [
            'sparkline' => ['labels' => $sparklineLabels, 'values' => $sparklineData],
            'sector' => $sectorData,
            'doughnut' => $doughnutData,
        ];
    @endphp
    <script type="application/json" id="dashboard-data">@json($dashboardData)</script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
    <script type="module" src="{{ Vite::asset('resources/js/formation/dashboard.js') }}"></script>
</div>
@endsection