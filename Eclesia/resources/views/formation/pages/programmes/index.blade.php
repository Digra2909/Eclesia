@extends('formation.layouts.app')

@section('title', 'Programmes - Eclesia.io')
@section('content')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <h4 class="mb-0"><i class="bi bi-calendar2-range me-2"></i>Programmes</h4>
    </div>
    <livewire:programmes />
@endsection