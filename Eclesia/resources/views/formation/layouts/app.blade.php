<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

    <!-- Favicon (logo déposé dans public/) -->
    <link rel="icon" type="image/png" href="{{ asset('image.png') }}">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Styles & Scripts Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-100 bg-light antialiased font-sans">
    <div class="d-flex flex-column flex-lg-row min-vh-100">
        <!-- Sidebar propre à l'application (défaut : Formation). Une autre application
             peut fournir la sienne en définissant une section @section('sidebar') -->
        
        @hasSection('sidebar')
            @yield('sidebar')
        @else
            @include('formation.partials.sidebar')
        @endif

        <!-- Main Content Area -->
        <div class="flex-grow-1 d-flex flex-column min-width-0 overflow-hidden">
            <!-- Header Component -->
            <x-navigation.header />

            <!-- Dynamic Body Content -->
            <main class="flex-grow-1 overflow-y-auto p-3 p-sm-4 p-lg-5">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Modal de confirmation de suppression (remplace les alertes JS) -->
    <div class="modal fade" id="modal-supprimer" tabindex="-1" aria-labelledby="modal-supprimer-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-supprimer-label">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">
                        Voulez-vous vraiment supprimer <strong id="element-supprimer"></strong> ?
                        Cette action est irréversible.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <form id="form-confirm-delete" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>