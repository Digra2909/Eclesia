<aside class="bg-dark text-white d-flex flex-column flex-shrink-0 p-3" style="width: 260px; min-height: 100vh;">
    <a href="/" class="d-flex align-items-center mb-4 me-md-auto text-white text-decoration-none fs-5 fw-bold px-2">
        <i class="bi bi-grid-fill text-primary me-2 fs-4"></i>
        <span>Application</span>
    </a>
    <hr class="border-secondary my-0 mb-3">

    <ul class="nav nav-pills flex-column mb-auto gap-1">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active bg-primary' : 'text-white-50 hover-bg-secondary' }}">
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