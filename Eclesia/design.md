# System & UI Specification Guidelines (`design.md`)

Ce document sert de spécification technique, architecturale et visuelle pour **OpenCode** (ou tout agent de génération de code AI) afin de générer fidèlement les vues **Laravel Blade** adaptées, structurées et responsive à partir du Canvas / Maquette Figma fourni.

---

## 1. Vue d'Ensemble & Directives d'Architecture Blade

### 1.1 Stack Technique
* **Framework Backend :** Laravel (Blade Templating Engine)
* **Framework CSS :** Bootstrap 5.3+ (avec Bootstrap Icons)
* **Interactivité Frontend :** Alpine.js / Livewire / Bootstrap JS
* **Icônes :** Bootstrap Icons (`bi-icon-name`)
* **Police :** Inter / Plus Jakarta Sans (Google Fonts)

### 1.2 Structure du Layout Master (`resources/views/layouts/app.blade.php`)
Toutes les vues principales dérivent d'un layout commun basé sur la grille et le système de composants Bootstrap :

```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Styles & Scripts Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-100 bg-light antialiased font-sans">
    <div class="d-flex flex-column flex-lg-row min-vh-100">
        <!-- Sidebar Layout Component -->
        <x-navigation.sidebar />

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

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
```

---

## 2. Design System & Palette de Couleurs (Bootstrap Custom)

```scss
// custom-bootstrap.scss
$primary: #0284c7;
$secondary: #64748b;
$success: #10b981;
$info: #06b6d4;
$warning: #f59e0b;
$danger: #ef4444;
$light: #f8fafc;
$dark: #0f172a;

$font-family-sans-serif: 'Inter', system-ui, -apple-system, sans-serif;

@import "bootstrap/scss/bootstrap";
```

---

## 3. Structure Générale des Vues Blade

### Component Map
* `resources/views/components/`
  * `navigation/`
    * `sidebar.blade.php` : Navigation latérale responsive basée sur Bootstrap Offcanvas/Sidebar
    * `header.blade.php` : En-tête supérieur (Navbar Bootstrap) avec recherche, notifications et menu profil
    * `nav-item.blade.php` : Liens réutilisables avec états actif/inactif
  * `ui/`
    * `card.blade.php` : Cartes Bootstrap réutilisables (`card`, `card-header`, `card-body`)
    * `button.blade.php` : Boutons avec classes Bootstrap (`btn-primary`, `btn-outline-secondary`, etc.)
    * `stat-card.blade.php` : Cartes d'indicateurs KPI
    * `badge.blade.php` : Badges d'état (`badge bg-success`, `badge bg-warning`, etc.)
    * `table.blade.php` : Tableaux Bootstrap responsive (`table table-hover align-middle`)
    * `modal.blade.php` : Fenêtres modales Bootstrap (`modal fade`)

---

## 4. Spécification Détaillée des Composants

### 4.1 En-Tête Supérieur (`x-navigation.header`)
* **Barre de Recherche Global :** Input group avec icône loupe (`bi bi-search`).
* **Menu Profil & Dropdown :** Dropdown Bootstrap (`dropdown-toggle`) avec avatar utilisateur.
* **Alertes & Notifications :** Bouton icône cloche (`bi bi-bell`) avec badge de notification.

```html
<!-- resources/views/components/navigation/header.blade.php -->
<header class="navbar navbar-expand bg-white border-bottom sticky-top px-3 px-sm-4 shadow-sm" style="height: 64px;">
    <div class="container-fluid p-0 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-link text-dark p-0 d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                <i class="bi bi-list fs-3"></i>
            </button>
            <div class="input-group" style="max-width: 380px;">
                <span class="input-group-text bg-light border-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control bg-light border-0 shadow-none" placeholder="Rechercher...">
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="button" class="btn btn-light position-relative rounded-circle p-2">
                <i class="bi bi-bell fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
            </button>
            <div class="vr my-2"></div>
            
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" data-bs-toggle="dropdown">
                    <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=User' }}" alt="Avatar" class="rounded-circle me-2" width="36" height="36">
                    <span class="d-none d-sm-inline font-weight-medium">{{ auth()->user()->name ?? 'Utilisateur' }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profil</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> Paramètres</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Déconnexion</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
```

### 4.2 Barre Latérale (`x-navigation.sidebar`)
* Logo & Branding en haut
* Liens de navigation ordonnés utilisant les composants Bootstrap `nav nav-pills`
* Bas de page avec carte profil utilisateur et paramètres

```html
<!-- resources/views/components/navigation/sidebar.blade.php -->
<aside class="bg-dark text-white d-flex flex-column flex-shrink-0 p-3" style="width: 260px; min-height: 100vh;">
    <a href="/" class="d-flex align-items-center mb-4 me-md-auto text-white text-decoration-none fs-5 fw-bold px-2">
        <i class="bi bi-grid-fill text-primary me-2 fs-4"></i>
        <span>Application</span>
    </a>
    <hr class="border-secondary my-0 mb-3">
    
    <ul class="nav nav-pills flex-column mb-auto gap-1">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active bg-primary' : 'hover-bg-secondary' }}">
                <i class="bi bi-house-door me-2"></i> Tableau de bord
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-white-50">
                <i class="bi bi-folder me-2"></i> Projets
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-white-50">
                <i class="bi bi-people me-2"></i> Utilisateurs
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-white-50">
                <i class="bi bi-bar-chart me-2"></i> Rapports
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-white-50">
                <i class="bi bi-gear me-2"></i> Paramètres
            </a>
        </li>
    </ul>

    <div class="mt-auto pt-3 border-top border-secondary">
        <div class="d-flex align-items-center bg-secondary bg-opacity-25 p-2 rounded">
            <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=User' }}" width="38" height="38" class="rounded-circle me-2">
            <div class="overflow-hidden">
                <div class="fw-semibold text-truncate text-white small">{{ auth()->user()->name ?? 'Utilisateur' }}</div>
                <div class="text-white-50 text-truncate small" style="font-size: 0.75rem;">{{ auth()->user()->email ?? 'user@domain.com' }}</div>
            </div>
        </div>
    </div>
</aside>
```

---

## 5. Layout Dynamic Content & Grid (Bootstrap 5 Blade Example)

Exemple de vue d'ensemble générée pour une page de tableau de bord ou de gestion globale avec la grille Bootstrap (`row`, `col-*`) :

```html
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
```

---

## 6. Instructions pour OpenCode

Lors de l'utilisation de ce fichier `design.md` dans **OpenCode** :
1. **Utiliser Bootstrap 5.3+ et Bootstrap Icons (`bi-`)** exclusivement à la place de Tailwind CSS.
2. **Suivre scrupuleusement la structure des composants** sous `resources/views/components/`.
3. **Exploiter la grille responsive Bootstrap (`container`, `row`, `col-*`, `g-*`)** et les utilitaires d'espacement Bootstrap (`m-*`, `p-*`, `d-flex`, `align-items-*`, `justify-content-*`).
4. **Utiliser la syntaxe Blade standard** (`@extends`, `@section`, `@component`, `<x-...>`, `@forelse`, `@if`).
