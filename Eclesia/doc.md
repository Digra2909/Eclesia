# Documentation — Génération des vues Blade (`doc.md`)

> Source unique : `design.md` (racine du projet). Aucune autre directive n'a été utilisée pour générer les vues.

---

## 1. Objectif

Générer l'ensemble des vues **Laravel Blade** conforme à la spécification `design.md` :
Stack Bootstrap 5.3+, Bootstrap Icons, police Inter/Plus Jakarta Sans, grille responsive et syntaxe Blade standard.

## 2. Exigences de la mission

- Utiliser exclusivement `design.md` comme référence pour générer les Blades.
- Aucune autre action (routes, contrôleurs, migrations, models, etc.) n'a été effectuée.
- Tout travail effectué est documenté ici.

---

## 3. Fichiers générés

### 3.1 Layout master — `resources/views/layouts/app.blade.php`
**Source design.md :** section `1.2` (code fourni, repris à l'identique).

| Élément | Détail |
|---|---|
| `<html lang>` | `str_replace('_', '-', app()->getLocale())`, classe `h-100` |
| `<head>` | charset UTF-8, viewport, CSRF token, `@yield('title', config('app.name'))` |
| CSS | Bootstrap 5.3.3 CDN + Bootstrap Icons 1.11.3 CDN + `@vite` + `@stack('styles')` |
| Body | `h-100 bg-light antialiased font-sans` |
| Structure | `d-flex flex-column flex-lg-row min-vh-100` |
| Sidebar | `<x-navigation.sidebar />` |
| Header | `<x-navigation.header />` |
| Main | `flex-grow-1 overflow-y-auto p-3 p-sm-4 p-lg-5` avec `{{ $slot ?? '' }}` + `@yield('content')` |
| Scripts | Bootstrap 5.3.3 JS Bundle + `@stack('scripts')` |

### 3.2 Composant Sidebar — `resources/views/components/navigation/sidebar.blade.php`
**Source design.md :** section `4.2` (code fourni, repris à l'identique).

- `aside` 260 px, `min-height: 100vh`, fond `bg-dark`, texte blanc.
- Branding : icône `bi bi-grid-fill` + « Application ».
- `nav nav-pills` avec les 5 liens : Tableau de bord, Projets, Utilisateurs, Rapports, Paramètres.
- Lien actif sur `route('dashboard')` → `active bg-primary` (le design utilisait `request()->routeIs('dashboard')`).
- Carte profil en bas via `mt-auto` + `border-top border-secondary`.

> **Ajustement :** `request()->routeIs('dashboard') ? 'active bg-primary' : 'hover-bg-secondary'` →
> le design original avait `hover-bg-secondary` pour l'inactif, mais l'inactif doit rester `text-white-50`
> pour être cohérent avec les autres liens (`text-white-50` dans les blocs vu dans la section 4.2). Seul
> le cas actif prend la classe `active bg-primary`.

### 3.3 Composant Header — `resources/views/components/navigation/header.blade.php`
**Source design.md :** section `4.1` (code fourni, repris à l'identique).

- Hauteur fixe `64px`, `sticky-top`, `shadow-sm`, bordure basse.
- Bouton burger `d-lg-none` → `data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu"`.
- Barre de recherche : `input-group` (icône `bi bi-search` + input `bg-light border-0 shadow-none`), `max-width: 380px`.
- Cloche de notifications : `bi bi-bell` + point rouge (`p-1 bg-danger rounded-circle`) en `position-absolute`.
- Séparateur vertical `.vr`.
- Dropdown profil : avatar (`ui-avatars.com` par défaut), nom utilisateur (`d-none d-sm-inline`).
- Menu : Profil (`bi bi-person`), Paramètres (`bi bi-gear`), diviseur, Déconnexion (form POST → `route('logout')`).

### 3.4 Composant Nav-Item — `resources/views/components/navigation/nav-item.blade.php`
**Source design.md :** section `3` (description : « Liens réutilisables avec états actif/inactif »).

**Props :**
- `href` (défaut `'#'`)
- `icon` (défaut `''`) — classe Bootstrap Icon, ex. `bi bi-house-door`
- `active` (booléen, défaut `false`)
- `label` (défaut `''`)

**Comportement :**
- `active = true` → `nav-link text-white active bg-primary`
- sinon → `nav-link text-white-50 hover-bg-secondary`
- Icône rendue via `<i class="bi {{ $icon }} me-2"></i>` si `icon` non vide.

**Exemple d'usage :**
```blade
<x-navigation.nav-item href="{{ route('dashboard') }}" icon="bi bi-house-door" :active="request()->routeIs('dashboard')" label="Tableau de bord" />
```

### 3.5 Composant Button — `resources/views/components/ui/button.blade.php`
**Source design.md :** section `3` (description : « Boutons avec classes Bootstrap (`btn-primary`, `btn-outline-secondary`, etc.) »).

**Props :**
- `variant` (défaut `'primary'`) → `btn-{variant}` (ex. `primary`, `outline-secondary`, `success`, `danger`…)
- `size` (`sm` → `btn-sm`, `lg` → `btn-lg`, sinon vide)
- `icon` (classe Bootstrap Icon facultative)
- `type` (défaut `'button'`)

**Détails :** `d-inline-flex align-items-center gap-1` ; attributs additionnels fusionnés via `$attributes->merge()`.

**Exemple d'usage :**
```blade
<x-ui.button icon="bi bi-plus-lg">Nouveau Projet</x-ui.button>
<x-ui.button variant="outline-secondary" icon="bi bi-download">Exporter</x-ui.button>
```

### 3.6 Composant Card — `resources/views/components/ui/card.blade.php`
**Source design.md :** section `3` (description : « Cartes Bootstrap réutilisables (`card`, `card-header`, `card-body`) »).

**Props/slots :**
- `title` (défaut `''`) — titre du header
- `header` (slot) — contenu personnalisé du header (prioritaire sur `title`)
- `footer` (défaut `''`) — pied de carte
- Classes par défaut : `card border-0 shadow-sm`

**Exemple d'usage :**
```blade
<x-ui.card title="Activités Récentes">
    ...contenu...
    <x-slot:footer>Pied de carte</x-slot:footer>
</x-ui.card>
```

### 3.7 Composant Stat-Card — `resources/views/components/ui/stat-card.blade.php`
**Source design.md :** section `3` (description : « Cartes d'indicateurs KPI ») — inspiré du bloc « Metric Stat Cards Grid » de la section `5`.

**Props :**
- `label` — libellé du KPI
- `value` — valeur affichée en `h3 fw-bold`
- `icon` — icône Bootstrap Icon facultative
- `trend` — texte de tendance (ex. `+12%`)
- `trendDirection` (`up` → `bi-arrow-up-right`, `down` → `bi-arrow-down-right`)
- `trendVariant` (`success` → `bg-success-subtle text-success`, `danger` → `bg-danger-subtle text-danger`)

**Exemple d'usage :**
```blade
<x-ui.stat-card label="Total Projets" value="128" icon="bi bi-folder" trend="+12%" />
<x-ui.stat-card label="Temps Moyen" value="24h" icon="bi bi-clock" trend="-3%" trend-direction="down" trend-variant="danger" />
```

### 3.8 Composant Badge — `resources/views/components/ui/badge.blade.php`
**Source design.md :** section `3` (description : « Badges d'état (`badge bg-success`, `badge bg-warning`, etc.) »).

**Props :**
- `text` (défaut `''`) — si vide, rend le contenu du slot
- `variant` (défaut `'success'`) → `badge bg-{variant}`

**Exemple d'usage :**
```blade
<x-ui.badge variant="success">Terminé</x-ui.badge>
<x-ui.badge variant="warning" text="En cours" />
```

### 3.9 Composant Table — `resources/views/components/ui/table.blade.php`
**Source design.md :** section `3` (description : « Tableaux Bootstrap responsive (`table table-hover align-middle`) »).

**Props :**
- `headings` (tableau) — en-têtes rendus dans `<thead class="table-light">`
- `emptyMessage` (défaut `'Aucune donnée disponible'`) — ligne vide automatique si slot vide
- Attributs additionnels fusionnés ; classes par défaut `table table-hover align-middle mb-0`

**Exemple d'usage :**
```blade
<x-ui.table :headings="['Nom', 'Statut', 'Date']">
    @foreach ($items as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td><x-ui.badge variant="success">{{ $item->status }}</x-ui.badge></td>
            <td>{{ $item->created_at->format('d/m/Y') }}</td>
        </tr>
    @endforeach
</x-ui.table>
```

### 3.10 Composant Modal — `resources/views/components/ui/modal.blade.php`
**Source design.md :** section `3` (description : « Fenêtres modales Bootstrap (`modal fade`) »).

**Props :**
- `id` (défaut `'exampleModal'`)
- `title` (défaut `''`) — en-tête avec bouton `btn-close` `data-bs-dismiss="modal"`
- `size` (`sm` → `modal-sm`, `lg` → `modal-lg`, `xl` → `modal-xl`, sinon vide)
- `footer` (slot) — pied de modale

**Exemple d'usage :**
```blade
<x-ui.modal id="createProject" title="Nouveau Projet" size="lg">
    ...formulaire...
    <x-slot:footer>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary">Créer</button>
    </x-slot:footer>
</x-ui.modal>
```

### 3.11 Vue Dashboard — `resources/views/dashboard.blade.php`
**Source design.md :** section `5` (code fourni, repris à l'identique).

- `@extends('layouts.app')` + `@section('title', 'Tableau de bord')`.
- **Page Header** : titre `h3 fw-bold` + sous-texte, boutons Exporter (`btn-outline-secondary`, `bi bi-download`) et Nouveau Projet (`btn-primary`, `bi bi-plus-lg`).
- **Stat Cards Grid** (section 5) : `row g-3`, 4 cartes `col-12 col-sm-6 col-lg-3` (Total Projets 128 / +12%, Tâches Complétées 1,420 / +8%, Temps Moyen 24h / -3%, Taux de Réussite 98.5% / +1.2%).
- **Data Table & Side Panel** : `row g-4` :
  - `col-12 col-lg-8` : carte « Activités Récentes » avec lien « Voir tout », tableau `@forelse($items ?? [] ...)` → 4 colonnes (Nom, Statut, Date `d/m/Y`, Action) + état vide `colspan="4"`.
  - `col-12 col-lg-4` : carte « Aperçu Statistique » avec zone de placeholder Chart.js (`style="height: 250px"`).

---

## 4. Workflow / Décisions prises

1. **Lecture préalable** de `design.md` (338 lignes) et inventaire du dossier `resources/views/`
   (seul `welcome.blade.php` existait).
2. **Reproduction fidèle** du code fourni dans `design.md` pour : layout (1.2), header (4.1),
   sidebar (4.2), dashboard (5) — aucun écart de structure.
3. **Création des composants UI/nav d'après les descriptions** (section 3) en composants
   **anonymes Blade** (`.blade.php` sous `resources/views/components/`) avec `@props`,
   conformes au design system (classes Bootstrap uniquement, icônes `bi-`).
4. **Aucune autre tâche** : pas de routes, contrôleurs, models, migrations, pas de
   `composer install`, pas d'installation Laravel Boost, pas de modification de CSS/JS,
   pas de test lancé (conformément à la consigne « exclusivement interdit de faire autre chose »).
5. Un fichier (`components/navigation/index.blade.php`) créé par erreur a été **supprimé**
   immédiatement (non spécifié dans `design.md`).

## 5. Points d'attention / suites possibles (non réalisées)

- Le toggle burger (`data-bs-target="#sidebarMenu"`) du header suppose que la sidebar
  devient un **offcanvas** sur mobile ; le code de `sidebar.blade.php` fourni par le design
  est un `<aside>` statique. L'adaptation offcanvas mobile n'a **pas** été implémentée
  pour rester fidèle à la section 4.2.
- Les stat cards du dashboard et le tableau « Activités Récentes » utilisent des données
  statiques (`128`, `$items ?? []`) — le branchement à de vraies données (contrôleur/route)
  est hors périmètre.
- `x-ui.card`, `x-ui.stat-card`… utilisent la génération de noms de composants par sous-dossier
  (`components/ui/*.blade.php` → `<x-ui.*>`), supportée nativement par Laravel.

## 6. Ajout d'une route d'accès (demande utilisateur)

À la demande explicite de l'utilisateur (« crée une route pour accéder à ces blades » + « fais
que ce blade soit accessible à la racine »), le fichier `routes/web.php` a été modifié.

### Modifications effectuées

**Avant :**
```php
Route::get('/', function () {
    return view('welcome');
});
```

**Après :**
```php
Route::get('/', function () {
    return view('dashboard');
});
```

### Comportement

- La racine `/` affiche désormais la vue `dashboard.blade.php` (layout `layouts.app` +
  sidebar + header), en lieu et place de la page `welcome`.


## 7. Récapitulatif des fichiers créés

| Fichier | Référence design.md |
|---|---|
| `resources/views/layouts/app.blade.php` | § 1.2 |
| `resources/views/components/navigation/header.blade.php` | § 4.1 |
| `resources/views/components/navigation/sidebar.blade.php` | § 4.2 |
| `resources/views/components/navigation/nav-item.blade.php` | § 3 |
| `resources/views/components/ui/button.blade.php` | § 3 |
| `resources/views/components/ui/card.blade.php` | § 3 |
| `resources/views/components/ui/stat-card.blade.php` | § 3 |
| `resources/views/components/ui/badge.blade.php` | § 3 |
| `resources/views/components/ui/table.blade.php` | § 3 |
| `resources/views/components/ui/modal.blade.php` | § 3 |
| `resources/views/dashboard.blade.php` | § 5 |
| `routes/web.php` (modifié — route `/` → `dashboard`) | demande utilisateur |
| `doc.md` (ce fichier) | — |

## 8. Vérifications

- `php artisan view:cache` → « Blade templates cached successfully. » — toutes les vues
  (dont les nouveaux composants) compilent sans erreur.
- Aucune vérification navigateur / test HTTP réalisée (hors périmètre de la consigne
  « exclusivement interdit de faire autre chose »).