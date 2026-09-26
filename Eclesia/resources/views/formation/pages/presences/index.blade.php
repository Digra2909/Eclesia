@extends('formation.layouts.app')

@section('title', 'Présences - Eclesia.io')
@section('content')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <h4 class="mb-0"><i class="bi bi-person-check-fill me-2"></i>Présences</h4>
    </div>
    <livewire:presences />
@endsection