# Documentation — Vues Blade du module « Formation » (`doc.md`)

> Référence : schéma de base de données fourni par l'utilisateur (tables `fideles`, `postes`,
> `type_interventions`, `statut_fideles`, `nus`, `ouvriers`, `presences`, `interventions`,
> `seances`, `programmes`, `formation_nus`, `formation_ords`, `cours`, `cours_programme`).
>
> **Règle impérative** : les blades sont construit(e)s **strictement à partir du schéma** —
> aucune colonne n'est inventée pour l'affichage. Les seules libertés autorisées sont les
> **améliorations UX front** (modales centrées, icônes/placeholders dans les champs, filtres,
> cartes, astérisques sur les champs requis …).

---

## 1. Architecture des vues (`resources/views`)

Les vues sont regroupées **par application**. L'application **Formation** vit dans
`resources/views/formation/` ; le générique (lay-out, composants `ui/*`, header) reste global.

```
resources/views/
├── components/
│   ├── navigation/
│   │   ├── header.blade.php        → barre supérieure globale (sticky)
│   │   └── nav-item.blade.php      → lien réutilisable actif/inactif
│   └── ui/
│       ├── badge.blade.php         → <span class="badge bg-{variant}">
│       ├── button.blade.php        → bouton Bootstrap paramétrable
│       ├── card.blade.php          → .card border-0 shadow-sm (+ header/footer slots)
│       ├── modal.blade.php         → .modal-dialog .modal-dialog-centered .modal-dialog-scrollable
│       ├── stat-card.blade.php     → carte KPI (label, valeur, icône, tendance)
│       └── table.blade.php         → tableau responsive + état vide
├── formation/
│   ├── layouts/
│   │   └── app.blade.php           → layout de l'application Formation
│   │                                (sidebar par défaut + header + main + scripts CDN)
│   ├── partials/
│   │   └── sidebar.blade.php       → sidebar Formation (items + sous-modales, offcanvas mobile)
│   ├── dashboard.blade.php         → tableau de bord exclusif Formation
│   └── modals/
│       ├── nouvelles-unites.blade.php
│       ├── cours.blade.php
│       ├── programmes.blade.php
│       ├── seances.blade.php
│       ├── presence.blade.php
│       └── evaluation.blade.php
```

## 2. Layout de l'application — `formation/layouts/app.blade.php`

- `@hasSection('sidebar') … @else @include('formation.partials.sidebar')` : la sidebar est
  **par application** (une autre app fournit la sienne via `@section('sidebar')`).
- `@include('formation.partials.sidebar')` par défaut + `<x-navigation.header />`.
- Contenu : `main` avec `{{ $slot ?? '' }}` + `@yield('content')`.
- Scripts en fin de page : Bootstrap 5.3.3 bundle + **html5-qrcode** (CDN) pour le scanner.

## 3. Dashboard — `formation/dashboard.blade.php`

Racine `/` → `DashboardController@index` → `view('formation.dashboard')`.

- **Charts** : 4 graphiques Chart.js (canvas `sparklineChart`, `histogramChart`, `sectorChart`,
  `doughnutChart`), chacun dans un `.chart-box` (hauteur fixe `var(--chart-height)` définie dans
  `app.css`). Données injectées via un **bloc JSON** :
  ```blade
  <script type="application/json" id="dashboard-data">@json($dashboardData)</script>
  ```
  puis `formation/dashboard.js` (entrée Vite) lit ce bloc et dessine les graphiques.
- **Cartes d'accès rapide** : 6 cartes ouvrent les modales des modules (unités, cours,
  programmes, séances, présences, évaluation).
- **Inclusion des modales** (données passées en arguments pour isoler les scopes) :
  ```blade
  @include('formation.modals.nouvelles-unites', ['unites' => $unites])
  @include('formation.modals.cours',          ['cours' => $cours])
  @include('formation.modals.programmes',     ['programmes' => $programmes])
  @include('formation.modals.seances',        ['programmes' => $programmes, 'seances' => $seances])
  @include('formation.modals.presence',       [ 'statsPresences' => ..., 'statutsFideles' => …,
                                                 'typeInterventions' => …, 'postes' => …, 'cours' => …])
  @include('formation.modals.evaluation',     ['nuCotations' => $nusEvaluation, 'evalDate' => $evalDate])
  ```

### Données fournies par `DashboardController`

| Variable | Contenu |
|---|---|
| `$unites` | fidèles + `code_fidele`, `nom`, `genre`, `telephone`, `grace`, `statut_nu` (depuis `nu.statut`) |
| `$cours` | `id`, `passages` |
| `$programmes` | `id`, `type` (NU/Ord), `libelle` (session/thème), `montant`, `statut`, `commentaire`, `nb_seances`, `nb_cours`, `date_creation` |
| `$seances` | `id`, `numero_seance`, `date_seance`, `heure_debut`, `heure_fin`, `lieu`, `delai_rappel`, `programme`, `programme_id` |
| `$statutsFideles`, `$typeInterventions`, `$postes` | `pluck('designation', 'id')` |
| `$nusEvaluation` | `id`, `code_nu`, `fidele`, `note_oral`, `note_ecrite`, `pourcentage` |
| `$statsPresences` | par séance : `programme`, `date_seance`, `present`, `absent`, `taux` |
| `$evalDate` | date de référence des cotations |

## 4. Blocs et composants des modales

Chaque fichier de `modals/` regroupe **toutes les modales du module** (convention de nommage) :
`#modal-{module}-{action}`. Chaque modale utilise le composant anonyme `<x-ui.modal>` (centrée et
scrollable), avec :

- un **formulaire** (action réelle `POST`/`PUT`/`DELETE` vers les routes du contrôleur) ;
- des **messages de validation en haut** du modal correspondant :
  ```blade
  @if ($errors->any())
      <div class="alert alert-danger py-2 small">
          @foreach ($errors->all() as $error) <div>…{{ $error }}</div> @endforeach
      </div>
  @endif
  ```
- des champs en `input-group` avec **icône `bi-` + placeholder** (pas de label) ;
- un `*` sur les champs requis (`placeholder="Nom *"`, attribut `required`) ;
- des **modales de confirmation de suppression** (`size="sm"`) avec remplissage automatique.

## 5. Modale « Nouvelles unités » — `nouvelles-unites.blade.php`

- `#modal-unites-ajouter` : formulaire → `route('nouvel-unite.store')`. Champs **exactement selon
  le schéma `fideles`+`nus`** : `nom`, `postnom`, `prenom`, `date_naissance`, `telephone` (13),
  `genre` (M/F), `statut_nu` (« en règle » / « non en règle »), `grace`. Les codes et le QR sont
  générés automatiquement (note d'information en haut du formulaire).
- `#modal-unites-consulter` (xl) : **format cartes** (`#unites-cards`), chaque carte
  `data-filtre-unit data-nom data-statut` ; **filtres** `#filtre-unites-nom` (texte) +
  `#filtre-unites-statut` (select) ; badges de statut (variants `success`/`warning`).
- `#modal-unites-editer` : formulaire pré-rempli via `data-bs-modal-fill` ; `data-action` pointe
  vers `fidele.update`.
- `#modal-unites-supprimer` (sm) : confirmation ; bouton danger `data-confirm-submit` →
  soumission `DELETE` vers `fidele.destroy`.

## 6. Modale « Cours » — `cours.blade.php`

- `#modal-cours-ajouter` : UN champ `passages` (thème/versets) → `route('cours.store')`.
- `#modal-cours-consulter` (xl) : **cartes** (`data-filtre-cours data-passages`) + filtre texte
  (`data-table-filter`/`data-filtre`), boutons « éditer » / « supprimer ».
- `#modal-cours-editer` / `#modal-cours-supprimer` : remplissage auto + `data-confirm-submit`.

## 7. Modale « Programmes » — `programmes.blade.php`

- `#modal-prog-ajouter` → `route('programmes.store')` :
  - select `type` (`#prog-type` : NU / Ord) qui **bascule** les blocs `#prog-fields-nu`
    (`session`) et `#prog-fields-ord` (`theme`) — JS `app.js` ;
  - `montant` (FCFA), `statut`, `commentaire`. Le contrôleur crée en plus la
    `formation_nus`/`formation_ords` selon le type.
- `#modal-prog-consulter` (xl) : **cartes** (`#prog-cards`) avec **3 filtres combinés** :
  `#filtre-prog-intitule` (texte), `#filtre-prog-type`, `#filtre-prog-statut`. Chaque carte :
  badges type (`primary`=NU / `success`=Ord) + statut, montant formaté, nb séances/cours,
  boutons **éditer / valider / supprimer**. Bouton bas « Gérer les séances » →
  `#modal-seance-consulter`.
- `#modal-prog-valider` (xl) : **cartes** avec **filtres type/intitulé/date** ; bouton
  « Afficher » (`[data-afficher]`) déplie `[data-validation-form]` contenant un **commentaire**
  et deux actions : **« Valider »** (`[data-validation]`) et **« À modifier »**.
  **Règle UX** : dès que le commentaire est saisi, le bouton « Valider » est **grisé
  (disabled)** (JS `app.js`).
- `#modal-prog-editer` : `montant`, `statut`, `commentaire` (seules colonnes réelles) + rappel
  lecture seule du libellé.
- `#modal-prog-supprimer` (sm) : `data-confirm-submit` → `programmes.destroy`.

## 8. Modale « Séances » — `seances.blade.php`

- `#modal-seance-ajouter` (xl) : liste de **n séances dans le même modal** —
  une ligne `[data-seance-row]` modèle + bouton **« Ajouter une autre séance »** (`#seances-add`)
  qui clone la ligne (numéro renuméroté, bouton « retirer » par ligne). Champs par ligne :
  `numero_seance`, `date_seance`, `heure_debut`, `heure_fin`, `lieu` (défaut « temple de
  l'église »), `delai_rappel` (jours) ; `programme_id` commun en haut. Envoi →
  `seances.batch` (`SeanceController@storeBatch`, requête `StoreSeancesBatchRequest`).
- `#modal-seance-consulter` (xl) : **tableau** (`#table-seances`) + filtre texte
  (`data-table-filter`) ; colonnes N°, Programme, Date, Heures, Lieu, Rappel, Actions
  (supprimer → `data-confirm-submit`).

## 9. Modale « Présences » — `presence.blade.php`

Onglets Bootstrap (`nav-tabs`) :

- **Scanner** (`#modal-presence-scanner`, `#presence-tab-scanner`) : zone `#qr-reader`
  (html5-qrcode, caméra via `{ facingMode: 'environment' }`), boutons « Ouvrir la caméra » /
  « Arrêter », résumé `#qr-recap` et bouton « Enregistrer la présence » (activé après scan).
- **Statistiques** (`#modal-presence-stats`, onglet) : cartes par séance
  (`#presence-stats-cards`, `data-filtre-pres`) + filtre `#filtre-stats-presence` ; barres de
  progression et taux.
- **Paramètres** (`#presence-tab-params`) : **CRUD complet** pour les 4 référentiels du schéma —
  **Statuts des fidèles**, **Types d'intervention**, **Cours**, **Postes**. Chaque section liste
  les enregistrements avec boutons **éditer** (`[data-crud-edit]`) et **supprimer** (form
  `DELETE` inline), et un bouton `+` ouvrant une modale CRUD (`#modal-crud-statut`,
  `#modal-crud-type`, `#modal-crud-cours`, `#modal-crud-poste`). Le JS `app.js` bascule le
  formulaire partagé entre **création** (`data-action-store`) et **mise à jour**
  (`data-action` + `_method PUT`).

## 10. Modale « Évaluation » — `evaluation.blade.php`

- `#modal-eval-cotation` (xl) : **une ligne par NU** (code, fidèle) avec deux champs
  `note_oral` / `note_ecrite` (sur 20) et le `pourcentage` calculé (chaîne de `nus`).
  Chaque ligne est un formulaire `PUT` → `nus.update`. Bandeau d'information avec la
  `$evalDate` de référence.

## 11. JavaScript — `resources/js/app.js`

Toutes les interactions front (aucune logique dans les vues) :

| Comportement | Détail |
|---|---|
| Remplissage auto | `[data-bs-modal-fill]` copie les `data-*` du bouton vers les champs `[name]`/`[data-field]` de la modale cible ; si `data-action` présent → définit `action` du formulaire + mémorise pour suppression |
| Suppression | `[data-confirm-submit]` construit un formulaire `_method=DELETE` et soumet l'URL mémorisée |
| Filtres cartes | unités (`#filtre-unites-…`), programmes (intitulé/type/statut), validation (intitulé/type/date), stats présence |
| Filtre tableaux | `[data-table-filter]` (filtre lignes `<tbody>`, colonnes optionnelles via `data-cols`) |
| Multi-séances | clonage `[data-seance-row]`, renumérotation, retrait |
| Bascule NU/Ord | `#prog-type` ⇄ `#prog-fields-nu` / `#prog-fields-ord` |
| Validation | commentaire saisi → bouton « Valider » désactivé ; « Afficher » déplie le formulaire |
| CRUD paramètres | `[data-crud-edit]` / `[data-crud-reset]` basculent création ⇄ mise à jour |
| Scanner QR | html5-qrcode : démarrage/arrêt caméra, résumé, activation de l'enregistrement |

Graphiques du dashboard : `resources/js/formation/dashboard.js` (entrée Vite dédiée,
déclarée dans `vite.config.js`).

## 12. Styles — `resources/css/app.css`

- Variables : `--bs-border-radius: 5px`, `--chart-height`, ombres douces.
- `.chart-box` (hauteur fixe), `.seance-row`, `.empty-state`, unités communes ; les blades
  restent **allégées** (pas de gros blocs `<style>` inline).

## 13. Fichiers supprimés / déplacés

- `resources/views/layouts/app.blade.php` → **déplacé** vers `formation/layouts/app.blade.php`.
- `resources/views/dashboard.blade.php`, `resources/views/welcome.blade.php`,
  `resources/views/components/navigation/sidebar.blade.php` → **supprimés** (remplacés par le
  dashboard et la sidebar Formation).

## 14. Tests & vérifications

- `php artisan test` : **5 tests / 14 assertions verts** (dashboard 200, 4 canvas dans
  `.chart-box`, hauteur fixe via `--chart-height` dans la feuille compilée, sidebar sticky et
  non flottante, layout appli).
- `vendor/bin/pint --dirty --format agent` : formatage appliqué.
- `npm run build` : Vite compile `app.css`, `app.js` et `dashboard.js`.

> Chaque modification exige ensuite : `php artisan view:clear`, `php artisan test`,
> `vendor/bin/pint --dirty`, `npm run build`.