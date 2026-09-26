@extends('formation.layouts.app')

@section('title', 'Programme - Eclesia.io')
@section('content')
    <livewire:programme-detail :programme="$programme" key="programme-detail-{{ $programme->id }}" />
@endsection