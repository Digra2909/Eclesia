<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Fidele;
use App\Models\Nu;
use App\Models\Ouvrier;
use App\Models\Poste;
use App\Models\Programme;
use App\Models\Seance;
use App\Models\StatutFidele;
use App\Models\TypeIntervention;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Calcule les indicateurs pertinents pour la gestion de la formation
     * et renvoie le tableau de bord (KPI + graphiques + données des modales).
     */
    public function index()
    {
        // ===== KPI =====
        $totalOuvriers = Ouvrier::distinct('fidele_id')->count('fidele_id');
        $totalNus = Nu::count();
        $programmesEnAttente = Programme::where('statut', 'créé')->count();
        $programmesValides = Programme::where('statut', 'validé')->count();

        // ===== Graphiques (valeurs calculées depuis la base) =====

        // Tendance : pourcentage de présences par rapport à l'ensemble des
        // fidèles ouvriers, sur les 7 derniers programmes de formation ordinaire.
        $totalOuvriersPourGraphique = max(1, $totalOuvriers);

        $programmesOrd = Programme::whereHas('formationOrds')
            ->with([
                'formationOrds',
                'seances.presences' => fn ($q) => $q->where('est_present', true),
            ])
            ->latest()
            ->take(7)
            ->get()
            ->reverse()
            ->values();

        $sparklineLabels = $programmesOrd->map(
            fn ($p) => Str::limit($p->formationOrds->first()?->theme ?? 'Programme #'.$p->id, 20)
        );
        $sparklineData = $programmesOrd->map(function ($p) use ($totalOuvriersPourGraphique) {
            $presents = $p->seances->sum(fn ($s) => $s->presences->count());

            return (int) round(min(100, ($presents / $totalOuvriersPourGraphique) * 100));
        })->values();

        // Secteur : répartition des fidèles par genre.
        $sectorData = [
            'labels' => ['Masculin', 'Féminin'],
            'datasets' => [
                [
                    'label' => 'Genre',
                    'data' => [Fidele::where('genre', 'M')->count(), Fidele::where('genre', 'F')->count()],
                    'backgroundColor' => ['#4e73df', '#1cc88a'],
                ],
            ],
        ];

        // Anneau : répartition des programmes par statut.
        $doughnutLabels = ['créé', 'programmé', 'validé', 'en cours', 'cloturé'];
        $doughnutData = [
            'labels' => $doughnutLabels,
            'datasets' => [
                [
                    'label' => 'Statut des programmes',
                    'data' => collect($doughnutLabels)->map(fn ($statut) => Programme::where('statut', $statut)->count())->all(),
                    'backgroundColor' => ['#f6c23e', '#37b24d', '#3b7dcd', '#2b9cfe', '#858796'],
                ],
            ],
        ];

        // ===== Données des modales (uniquement issues du schéma) =====

        // Nouvelles unités : fidèles + leur statut + leur NU.
        $unites = Fidele::with(['statut', 'nu'])->latest()->get()->map(fn (Fidele $fidele) => [
            'id' => $fidele->id,
            'code_fidele' => $fidele->code_fidele,
            'nom' => trim(implode(' ', array_filter([$fidele->prenom, $fidele->nom, $fidele->postnom]))),
            'genre' => $fidele->genre,
            'telephone' => $fidele->telephone,
            'grace' => $fidele->grace,
            'statut_nu' => $fidele->nu?->statut ?? 'en règle',
        ])->values();

        // Cours : passages (thème / versets).
        $cours = Cours::latest()->get()->map(fn (Cours $coursItem) => [
            'id' => $coursItem->id,
            'passages' => $coursItem->passages,
        ])->values();

        // Programmes : type (NU/Ord), libellé, montant, statut, commentaire.
        $programmes = Programme::with(['formationNus', 'formationOrds', 'seances', 'cours'])->latest()->get()
            ->map(function (Programme $programme) {
                $formationNu = $programme->formationNus->first();
                $formationOrd = $programme->formationOrds->first();

                return [
                    'id' => $programme->id,
                    'type' => $formationNu ? 'NU' : ($formationOrd ? 'Ord' : null),
                    'libelle' => $formationNu?->session ?? $formationOrd?->theme ?? 'Programme #'.$programme->id,
                    'montant' => $programme->montant,
                    'statut' => $programme->statut,
                    'commentaire' => $programme->commentaire,
                    'nb_seances' => $programme->seances->count(),
                    'nb_cours' => $programme->cours->count(),
                    'date_creation' => $programme->created_at?->format('d/m/Y'),
                    'cours_list' => $programme->cours->pluck('passages')->values(),
                    'seances_list' => $programme->seances->sortBy('date_seance')->values()->map(fn (Seance $seance) => [
                        'numero_seance' => $seance->numero_seance,
                        'date_seance' => $seance->date_seance?->format('d/m/Y'),
                        'heure_debut' => $seance->heure_debut?->format('H:i'),
                        'heure_fin' => $seance->heure_fin?->format('H:i'),
                        'lieu' => $seance->lieu,
                        'delai_rappel' => $seance->delai_rappel,
                    ])->values(),
                ];
            })->values();

        // Listes pour les modales de détail des KPI.
        $programmesAttente = $programmes->where('statut', 'créé')->values();
        $programmesValidesListe = $programmes->where('statut', 'validé')->values();

        // Séances : programme associé.
        $seances = Seance::with('programme')->orderBy('date_seance')->get()->map(fn (Seance $seance) => [
            'id' => $seance->id,
            'numero_seance' => $seance->numero_seance,
            'date_seance' => $seance->date_seance?->format('d/m/Y'),
            'heure_debut' => $seance->heure_debut?->format('H:i'),
            'heure_fin' => $seance->heure_fin?->format('H:i'),
            'lieu' => $seance->lieu,
            'delai_rappel' => $seance->delai_rappel,
            'programme' => $seance->programme
                ? ($seance->programme->formationNus()->first()?->session ?? $seance->programme->formationOrds()->first()?->theme ?? 'Programme #'.$seance->programme_id)
                : '—',
            'programme_id' => $seance->programme_id,
        ])->values();

        // Paramètres : statuts, types d'intervention, postes (CRUD modales).
        $statutsFideles = StatutFidele::orderBy('designation')->pluck('designation', 'id');
        $typeInterventions = TypeIntervention::orderBy('designation')->pluck('designation', 'id');
        $postes = Poste::orderBy('designation')->pluck('designation', 'id');

        // Ouvriers (KPI « Fidèles ouvriers ») : code, fidèle associé et poste.
        $ouvriers = Ouvrier::with(['fidele', 'poste'])->latest()->get()->map(fn (Ouvrier $ouvrier) => [
            'id' => $ouvrier->id,
            'code_ouvrier' => $ouvrier->code_ouvrier,
            'nom' => trim(implode(' ', array_filter([
                $ouvrier->fidele?->prenom,
                $ouvrier->fidele?->nom,
                $ouvrier->fidele?->postnom,
            ]))),
            'code_fidele' => $ouvrier->fidele?->code_fidele ?? '—',
            'poste' => $ouvrier->poste?->designation ?? '—',
        ])->values();

        // Évaluation : cotations des NU.
        $nusEvaluation = Nu::with('fidele')->get()->map(fn (Nu $nu) => [
            'id' => $nu->id,
            'code_nu' => $nu->code_nu,
            'fidele' => trim(implode(' ', array_filter([
                $nu->fidele?->prenom,
                $nu->fidele?->nom,
                $nu->fidele?->postnom,
            ]))),
            'note_oral' => $nu->note_oral,
            'note_ecrite' => $nu->note_ecrite,
            'pourcentage' => $nu->pourcentage,
        ])->values();

        // Présences : statistiques par séance.
        $statsPresences = Seance::with('presences')->get()->map(fn (Seance $seance) => [
            'programme' => $seance->programme_id,
            'date_seance' => $seance->date_seance?->format('d/m/Y'),
            'present' => $seance->presences->where('est_present', true)->count(),
            'absent' => $seance->presences->where('est_present', false)->count(),
            'taux' => $seance->presences->count() > 0
                ? (int) round(($seance->presences->where('est_present', true)->count() / $seance->presences->count()) * 100)
                : 0,
        ])->values();

        return view('formation.dashboard', [
            'totalOuvriers' => $totalOuvriers,
            'totalNus' => $totalNus,
            'programmesEnAttente' => $programmesEnAttente,
            'programmesValides' => $programmesValides,
            'sparklineLabels' => $sparklineLabels,
            'sparklineData' => $sparklineData,
            'sectorData' => $sectorData,
            'doughnutLabels' => $doughnutLabels,
            'doughnutData' => $doughnutData,
            // Modales
            'unites' => $unites,
            'cours' => $cours,
            'programmes' => $programmes,
            'programmesAttente' => $programmesAttente,
            'programmesValidesListe' => $programmesValidesListe,
            'ouvriers' => $ouvriers,
            'seances' => $seances,
            'statutsFideles' => $statutsFideles,
            'typeInterventions' => $typeInterventions,
            'postes' => $postes,
            'nusEvaluation' => $nusEvaluation,
            'statsPresences' => $statsPresences,
        ]);
    }
}
