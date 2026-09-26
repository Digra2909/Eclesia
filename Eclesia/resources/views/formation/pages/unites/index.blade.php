@extends('formation.layouts.app')

@section('title', 'Unités - Eclesia.io')
@section('content')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <h4 class="mb-0"><i class="bi bi-person-plus-fill me-2"></i>Nouvelles unités</h4>
        <span class="text-muted small"><i class="bi bi-people me-1"></i>{{ $totalUnites }} unité(s)</span>
    </div>
    <livewire:unites />
@endsection