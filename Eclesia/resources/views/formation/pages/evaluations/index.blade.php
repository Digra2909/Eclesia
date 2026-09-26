@extends('formation.layouts.app')

@php
    $pourcentage = function (App\Models\Nu $n): ?float {
        if ($n->pourcentage !== null) {
            return (float) $n->pourcentage;
        }

        if ($n->note_oral === null && $n->note_ecrite === null) {
            return null;
        }

        return round(((float) $n->note_oral + (float) $n->note_ecrite) * 100 / 40, 1);
    };
@endphp

@section('title', 'Cotations - Eclesia.io')
@section('content')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <h4 class="mb-0"><i class="bi bi-clipboard2-pulse me-2"></i>Cotations des NUs</h4>
        <span class="text-muted small"><i class="bi bi-people me-1"></i>{{ $nus->count() }} NU cotable(s)</span>
    </div>

    <p class="text-muted small mb-3">
        <i class="bi bi-info-circle me-1"></i>Note sur 20 : écrite à gauche (rouge), orale à droite (bleu). Le pourcentage se calcule automatiquement : (écrite + orale) × 100 / 40. Chaque ligne s'enregistre indépendamment.
    </p>

    {{-- ============ Sous-menu : Cartes / Tableau ============ --}}
    <ul class="nav nav-pills mb-3" id="onglet-cotations" role="tablist">
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#panneau-cartes" role="tab">
                <i class="bi bi-card-list me-1"></i>Cartes
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#panneau-tableau" role="tab">
                <i class="bi bi-table me-1"></i>Tableau
            </button>
        </li>
    </ul>

    <div class="tab-content">
        {{-- ============ Cartes NU ============ --}}
        <div class="tab-pane fade show active" id="panneau-cartes" role="tabpanel">
            <div class="row g-3">
                @forelse ($nus as $nu)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <span class="fw-semibold"><i class="bi bi-credit-card me-1 text-muted"></i>{{ $nu->code_nu }}</span>
                                @php $pct = $pourcentage($nu); @endphp
                                <span class="badge bg-success-subtle text-success fw-semibold">
                                    @if ($pct !== null)
                                        {{ $pct }}%
                                    @else
                                        —
                                    @endif
                                </span>
                            </div>
                            <div class="card-body py-3">
                                <h6 class="fw-semibold mb-2">
                                    <i class="bi bi-person me-1 text-muted"></i>{{ trim(implode(' ', array_filter([$nu->fidele?->prenom, $nu->fidele?->nom, $nu->fidele?->postnom]))) }}
                                </h6>
                                <p class="text-muted small mb-2"><i class="bi bi-qr-code me-1"></i>{{ $nu->fidele?->code_fidele }}</p>
                                <div class="d-flex flex-wrap gap-2 small">
                                    <span class="badge bg-danger-subtle text-danger">Écrite : {{ $nu->note_ecrite ?? '—' }}</span>
                                    <span class="badge bg-primary-subtle text-primary">Orale : {{ $nu->note_oral ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p class="mb-0 mt-2">Aucune NU enregistrée pour le moment.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ============ Tableau ============ --}}
        <div class="tab-pane fade" id="panneau-tableau" role="tabpanel">
            <x-ui.card title="Notes des nouvelles unités" :footer="$nus->count().' NU au total'">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th class="text-muted small">Code NU</th>
                                <th class="text-muted small">Fidèle</th>
                                <th class="text-muted small text-end">Écrite / Orale</th>
                                <th class="text-muted small text-end">Pourcentage</th>
                                <th class="text-muted small text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($nus as $nu)
                                <livewire:cotation-form :nu="$nu" wire:key="cotation-{{ $nu->id }}" />
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state py-4">
                                            <i class="bi bi-inbox"></i>
                                            <p class="mb-0 mt-2">Aucune NU enregistrée pour le moment.</p>
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
@endsection