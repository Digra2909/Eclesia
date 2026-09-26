<?php

namespace App\Http\Controllers;

use App\Models\Fidele;
use App\Models\Nu;
use App\Models\Ouvrier;
use App\Models\Programme;
use App\Models\Seance;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Page d'accueil de l'application Formation : indicateurs clés (KPI) et
     * graphiques. Chaque KPI renvoie vers la page du module concerné.
     */
    public function index()
    {
        // ===== KPI =====
        $totalNus = Nu::count();
        $programmesEnAttente = Programme::where('statut', 'créé')->count();
        $programmesValides = Programme::where('statut', 'validé')->count();
        $seancesPlanifiees = Seance::count();

        // ===== Graphiques (valeurs calculées depuis la base) =====

        // Tendance : pourcentage de présences par rapport à l'ensemble des
        // fidèles ouvriers, sur les 7 derniers programmes de formation ordinaire.
        $totalOuvriersPourGraphique = max(1, Ouvrier::distinct('fidele_id')->count('fidele_id'));

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

        return view('formation.dashboard', [
            'totalNus' => $totalNus,
            'programmesEnAttente' => $programmesEnAttente,
            'programmesValides' => $programmesValides,
            'seancesPlanifiees' => $seancesPlanifiees,
            'sparklineLabels' => $sparklineLabels,
            'sparklineData' => $sparklineData,
            'sectorData' => $sectorData,
            'doughnutLabels' => $doughnutLabels,
            'doughnutData' => $doughnutData,
        ]);
    }
}
