@extends('formation.layouts.app')

@section('title', 'Cours - Eclesia.io')
@section('content')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <h4 class="mb-0"><i class="bi bi-book me-2"></i>Cours</h4>
    </div>
    <livewire:cours-section />
@endsection