@extends('formation.layouts.app')

@section('title', 'Réglages - Eclesia.io')
@section('content')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <h4 class="mb-0"><i class="bi bi-sliders2 me-2"></i>Réglages</h4>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <livewire:parametres type="statut" wire:key="parametres-statut" />
        </div>
        <div class="col-12 col-lg-6">
            <livewire:parametres type="type" wire:key="parametres-type" />
        </div>
        <div class="col-12 col-lg-6">
            <livewire:parametres type="poste" wire:key="parametres-poste" />
        </div>
        <div class="col-12 col-lg-6">
            <livewire:parametres type="cours" wire:key="parametres-cours" />
        </div>
    </div>
@endsection