<header class="navbar navbar-expand bg-white border-bottom sticky-top px-3 px-sm-4 shadow-sm" style="height: 64px;z-index:50">
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
                    @if(Route::has('logout'))
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Déconnexion</button>
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</header>