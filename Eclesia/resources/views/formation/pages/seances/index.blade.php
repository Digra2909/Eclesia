@extends('formation.layouts.app')

@section('title', 'Séances - Eclesia.io')
@section('content')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <h4 class="mb-0"><i class="bi bi-clock-history me-2"></i>Séances</h4>
    </div>
    <livewire:seances />
@endsection