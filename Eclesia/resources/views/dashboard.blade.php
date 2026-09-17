@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="d-flex flex-column gap-4">
    <!-- Page Header & Actions -->
    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Tableau de bord</h1>
            <p class="text-muted small mb-0">Aperçu global des indicateurs clés et activités récentes.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary d-flex align-items-center gap-1">
                <i class="bi bi-download"></i> Exporter
            </button>
            <button class="btn btn-primary d-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i> Nouveau Projet
            </button>
        </div>
    </div>

    <!-- Metric Stat Cards Grid -->
    <div class="row g-3">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Total Projets</span>
                    <i class="bi bi-folder fs-4 text-primary"></i>
                </div>
                <h3 class="fw-bold mb-1">128</h3>
                <span class="badge bg-success-subtle text-success w-auto align-self-start"><i class="bi bi-arrow-up-right"></i> +12%</span>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Tâches Complétées</span>
                    <i class="bi bi-check-circle fs-4 text-success"></i>
                </div>
                <h3 class="fw-bold mb-1">1,420</h3>
                <span class="badge bg-success-subtle text-success w-auto align-self-start"><i class="bi bi-arrow-up-right"></i> +8%</span>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Temps Moyen</span>
                    <i class="bi bi-clock fs-4 text-warning"></i>
                </div>
                <h3 class="fw-bold mb-1">24h</h3>
                <span class="badge bg-danger-subtle text-danger w-auto align-self-start"><i class="bi bi-arrow-down-right"></i> -3%</span>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Taux de Réussite</span>
                    <i class="bi bi-pie-chart fs-4 text-info"></i>
                </div>
                <h3 class="fw-bold mb-1">98.5%</h3>
                <span class="badge bg-success-subtle text-success w-auto align-self-start"><i class="bi bi-arrow-up-right"></i> +1.2%</span>
            </div>
        </div>
    </div>

    <!-- Data Table & Side Panel Section -->
    <div class="row g-4">
        <!-- Main Data Table Container (8 cols width) -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold mb-0">Activités Récentes</h5>
                    <a href="#" class="text-decoration-none small fw-semibold">Voir tout</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items ?? [] as $item)
                                <tr>
                                    <td class="fw-medium">{{ $item->name }}</td>
                                    <td><span class="badge bg-success">{{ $item->status }}</span></td>
                                    <td class="text-muted">{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td class="text-end"><button class="btn btn-sm btn-light">Détails</button></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Aucune donnée disponible</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Secondary Widget Card (4 cols width) -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm p-3 h-100">
                <h5 class="fw-semibold mb-3">Aperçu Statistique</h5>
                <div class="d-flex align-items-center justify-content-center bg-light rounded border border-dashed text-muted" style="height: 250px;">
                    <span class="small">[ Zone d'intégration Graphique / Chart.js ]</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection