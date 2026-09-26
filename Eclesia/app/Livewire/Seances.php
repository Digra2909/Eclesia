<?php

namespace App\Livewire;

use App\Livewire\Concerns\GereLignesSeances;
use App\Models\Fidele;
use App\Models\Programme;
use App\Models\Seance;
use App\Models\TypeIntervention;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Seances extends Component
{
    use GereLignesSeances;

    public ?string $message = null;

    public ?string $erreur = null;

    public function mount(): void
    {
        $this->ajouterLigne();
    }

    public function enregistrer(): void
    {
        $this->erreur = null;
        $this->message = null;

        try {
            $this->enregistrerSeances();
        } catch (\Throwable $e) {
            $this->erreur = 'Une erreur est survenue : '.$e->getMessage();
        }
    }

    public function render(): View
    {
        return view('livewire.seances', [
            'programmes' => Programme::with(['formationNus', 'formationOrds', 'seances'])->latest()->get()
                ->map(function (Programme $programme) {
                    return [
                        'id' => $programme->id,
                        'libelle' => $programme->formationNus->first()?->session ?? $programme->formationOrds->first()?->theme ?? 'Programme #'.$programme->id,
                        'type' => $programme->formationNus->isNotEmpty() ? 'NU' : ($programme->formationOrds->isNotEmpty() ? 'Ord' : null),
                        'statut' => $programme->statut,
                        'nb_seances' => $programme->seances->count(),
                    ];
                })->values(),
            'fideles' => Fidele::orderBy('nom')->orderBy('prenom')->get()
                ->map(fn (Fidele $fidele) => [
                    'id' => $fidele->id,
                    'nom' => trim($fidele->prenom.' '.$fidele->nom.' '.$fidele->postnom),
                ])->values(),
            'typesIntervention' => TypeIntervention::orderBy('designation')->get()
                ->map(fn (TypeIntervention $type) => [
                    'id' => $type->id,
                    'designation' => $type->designation,
                ])->values(),
            'seances' => Seance::with(['programme', 'interventions.fidele', 'interventions.typeIntervention'])
                ->latest('date_seance')->latest('heure_debut')->get()
                ->map(fn (Seance $seance) => [
                    'id' => $seance->id,
                    'numero' => $seance->numero_seance,
                    'programme' => $seance->programme?->formationNus->first()?->session ?? $seance->programme?->formationOrds->first()?->theme ?? 'Programme',
                    'date_seance' => $seance->date_seance,
                    'heure_debut' => $seance->heure_debut,
                    'heure_fin' => $seance->heure_fin,
                    'lieu' => $seance->lieu,
                    'delai_rappel' => $seance->delai_rappel,
                    'intervenants' => $seance->interventions->map(fn ($intervention) => trim(($intervention->fidele?->prenom ?? '').' '.($intervention->fidele?->nom ?? '')).' · '.($intervention->typeIntervention?->designation ?? '?'))->values(),
                ])->values(),
        ]);
    }
}
