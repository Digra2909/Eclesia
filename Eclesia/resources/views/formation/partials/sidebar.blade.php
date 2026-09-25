<aside id="sidebarMenu" class="sidebar-formation offcanvas-lg offcanvas-start bg-dark text-white" tabindex="-1">
    <div class="offcanvas-header d-lg-none pt-3 pe-3 pb-0">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-white text-decoration-none fs-5 fw-bold px-2">
            <img src="{{ asset('image.png') }}" alt="Eclesia.io" class="app-sidebar-logo me-2">
            <span>Eclesia.io</span>
        </a>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column p-3">
    <a href="{{ route('dashboard') }}" class="app-sidebar-brand d-flex align-items-center mb-4 me-md-auto text-white text-decoration-none fs-5 fw-bold px-2">
        <img src="{{ asset('image.png') }}" alt="Eclesia.io" class="app-sidebar-logo me-2">
        <span>Eclesia.io</span>
    </a>
    <hr class="border-secondary my-0 mb-3">

    <span class="app-section-label mb-3 mt-1"><i class="bi bi-mortarboard me-1"></i>Application Formation</span>

    <ul class="nav nav-pills flex-column mb-auto gap-1">
        <!-- Vue d'ensemble (exclusif à l'application formation) -->
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('dashboard') ? 'active bg-primary text-white' : 'text-white-50' }}">
                <i class="bi bi-compass me-2"></i>
                <span>Vue d'ensemble</span>
            </a>
        </li>

        <!-- Unités -->
        <li class="nav-submenu">
            <a href="#" class="nav-link d-flex align-items-center text-white-50">
                <i class="bi bi-person-plus-fill me-2"></i>
                <span>Unités</span>
                <i class="bi bi-chevron-right ms-auto small"></i>
            </a>
            <ul class="app-submenu">
                <li><button type="button" class="app-submenu-link" data-bs-toggle="modal" data-bs-target="#modal-unites-ajouter"><i class="bi bi-plus-lg me-2"></i>Enregistrer</button></li>
                <li><button type="button" class="app-submenu-link" data-bs-toggle="modal" data-bs-target="#modal-unites-consulter"><i class="bi bi-eye me-2"></i>Consulter</button></li>
            </ul>
        </li>

        <!-- Cours -->
        <li class="nav-submenu">
            <a href="#" class="nav-link d-flex align-items-center text-white-50">
                <i class="bi bi-journal-richtext me-2"></i>
                <span>Cours</span>
                <i class="bi bi-chevron-right ms-auto small"></i>
            </a>
            <ul class="app-submenu">
                <li><button type="button" class="app-submenu-link" data-bs-toggle="modal" data-bs-target="#modal-cours-ajouter"><i class="bi bi-plus-lg me-2"></i>Ajouter</button></li>
                <li><button type="button" class="app-submenu-link" data-bs-toggle="modal" data-bs-target="#modal-cours-consulter"><i class="bi bi-eye me-2"></i>Consulter</button></li>
            </ul>
        </li>

        <!-- Programmes (avec sous-menu Séances et Validation) -->
        <li class="nav-submenu">
            <a href="#" class="nav-link d-flex align-items-center text-white-50">
                <i class="bi bi-calendar2-range me-2"></i>
                <span>Programmes</span>
                <i class="bi bi-chevron-right ms-auto small"></i>
            </a>
            <ul class="app-submenu">
                <li><button type="button" class="app-submenu-link" data-bs-toggle="modal" data-bs-target="#modal-prog-ajouter"><i class="bi bi-plus-lg me-2"></i>Créer</button></li>
                <li><button type="button" class="app-submenu-link" data-bs-toggle="modal" data-bs-target="#modal-prog-consulter"><i class="bi bi-eye me-2"></i>Consulter</button></li>

                <li class="nav-submenu">
                    <a href="#" class="app-submenu-title">
                        <i class="bi bi-clock-history me-2"></i>
                        <span>Séances</span>
                        <i class="bi bi-chevron-right ms-auto small"></i>
                    </a>
                    <ul class="app-submenu">
                        <li><button type="button" class="app-submenu-link" data-bs-toggle="modal" data-bs-target="#modal-seance-ajouter"><i class="bi bi-plus-lg me-2"></i>Planifier</button></li>
                        <li><button type="button" class="app-submenu-link" data-bs-toggle="modal" data-bs-target="#modal-seance-consulter"><i class="bi bi-eye me-2"></i>Consulter</button></li>
                    </ul>
                </li>

                <li><button type="button" class="app-submenu-link" data-bs-toggle="modal" data-bs-target="#modal-prog-valider"><i class="bi bi-clipboard2-check me-2"></i>Validation</button></li>
            </ul>
        </li>

        <!-- Présences -->
        <li class="nav-submenu">
            <a href="#" class="nav-link d-flex align-items-center text-white-50">
                <i class="bi bi-person-check-fill me-2"></i>
                <span>Présences</span>
                <i class="bi bi-chevron-right ms-auto small"></i>
            </a>
            <ul class="app-submenu">
                <li><button type="button" class="app-submenu-link" data-bs-toggle="modal" data-bs-target="#modal-presence-saisie"><i class="bi bi-keyboard me-2"></i>Par code</button></li>
                <li><button type="button" class="app-submenu-link" data-bs-toggle="modal" data-bs-target="#modal-presence-saisie"><i class="bi bi-qr-code-scan me-2"></i>Scanner</button></li>
                <li><button type="button" class="app-submenu-link" data-bs-toggle="modal" data-bs-target="#modal-presence-stats"><i class="bi bi-bar-chart-line me-2"></i>Statistiques</button></li>
            </ul>
        </li>

        <!-- Cotations -->
        <li class="nav-submenu">
            <a href="#" class="nav-link d-flex align-items-center text-white-50">
                <i class="bi bi-clipboard2-pulse me-2"></i>
                <span>Cotations</span>
                <i class="bi bi-chevron-right ms-auto small"></i>
            </a>
            <ul class="app-submenu">
                <li><button type="button" class="app-submenu-link" data-bs-toggle="modal" data-bs-target="#modal-eval-cotation"><i class="bi bi-pencil-square me-2"></i>Coter les NU</button></li>
            </ul>
        </li>

        <!-- Réglages (CRUD des paramètres) -->
        <li class="nav-item">
            <button type="button" class="nav-link d-flex align-items-center w-100 text-white-50" data-bs-toggle="modal" data-bs-target="#modal-parametres">
                <i class="bi bi-sliders2 me-2"></i>
                <span>Réglages</span>
                <i class="bi bi-gear-wide-connected ms-auto small opacity-50"></i>
            </button>
        </li>
    </ul>
    </div><!-- /.offcanvas-body -->

    <style>
        /* Design des liens : transitions, survols et chevrons pivotants */
        .sidebar-formation .nav-link {
            border-radius: 0.5rem;
            transition: background-color 0.15s ease, color 0.15s ease;
        }
        .sidebar-formation .nav-item .nav-link,
        .sidebar-formation .nav-submenu > .nav-link {
            border: 0;
            background: none;
        }
        .sidebar-formation .nav-link:hover { background: rgba(255, 255, 255, 0.08); color: #fff; }
        .sidebar-formation .nav-link.active {
            background: linear-gradient(90deg, #3b7dcd, #2b9cfe);
            box-shadow: 0 0.25rem 0.75rem rgba(43, 156, 254, 0.35);
            color: #fff;
        }
        .sidebar-formation .bi-chevron-right { transition: transform 0.2s ease; }
        .sidebar-formation .nav-submenu:hover > .nav-link .bi-chevron-right,
        .sidebar-formation .app-submenu .nav-submenu:hover > .app-submenu-title .bi-chevron-right {
            transform: rotate(90deg);
        }
        .sidebar-formation .app-section-label {
            display: inline-flex;
            align-items: center;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            color: rgba(255, 255, 255, 0.55);
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 999px;
            padding: 0.25rem 0.6rem;
        }

        /* Sous-items accessibles au survol — menu en flottant à droite */
        .sidebar-formation .nav-submenu { position: relative; }
        .sidebar-formation .app-submenu {
            display: none;
            position: absolute;
            left: 100%;
            top: 0;
            min-width: 230px;
            margin: 0;
            padding: 0.5rem;
            list-style: none;
            background: #1f2329;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 0.5rem;
            box-shadow: 0 0.6rem 1.2rem rgba(0, 0, 0, 0.45);
            z-index: 1050;
        }
        .sidebar-formation .nav-submenu:hover > .app-submenu,
        .sidebar-formation .app-submenu .nav-submenu:hover > .app-submenu { display: block; }
        .sidebar-formation .app-menu-separator {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin: 0.4rem 0;
        }
        .sidebar-formation .app-submenu-link,
        .sidebar-formation .app-submenu-title {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0.45rem 0.75rem;
            border: 0;
            border-radius: 0.375rem;
            background: none;
            text-align: left;
            font-size: 0.875rem;
            text-decoration: none;
        }
        .sidebar-formation .app-submenu-link {
            color: rgba(255, 255, 255, 0.75);
            white-space: nowrap;
        }
        .sidebar-formation .app-submenu-link:hover { background: rgba(255, 255, 255, 0.1); color: #fff; }
        .sidebar-formation .app-submenu-title { color: #fff; font-weight: 600; }

        /* Côté mobile : le hamburger ouvre le menu en offcanvas (classes Bootstrap) */
        .sidebar-formation { width: 280px; }

        /* Bureau (lg+) : la sidebar redevient statique dans le flux, ancrée en haut.
           Pas d'overflow (le clip casserait les sous-menus flottants à droite). */
        @media (min-width: 992px) {
            .sidebar-formation {
                width: 260px !important;
                flex: 0 0 260px;
                position: sticky;
                top: 0;
                height: 100vh;
                max-height: 100vh;
                overflow: visible;
                z-index: 1020;
            }
            .sidebar-formation .offcanvas-body { overflow: visible; }
            .sidebar-formation .app-sidebar-brand { display: flex !important; }
        }
        .app-sidebar-brand { display: none !important; }
        .app-sidebar-logo {
            height: 30px;
            width: auto;
            object-fit: contain;
            border-radius: 0.375rem;
        }

        /* Sur mobile (offcanvas), les sous-menus s'affichent en cascade au lieu de flotter */
        @media (max-width: 991.98px) {
            .sidebar-formation .app-submenu {
                position: static;
                margin-left: 1rem;
                box-shadow: none;
            }
            .sidebar-formation .app-submenu .app-submenu {
                margin-left: 0.5rem;
            }
        }
    </style>
</aside>