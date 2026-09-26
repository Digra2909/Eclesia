<?php

namespace App\Livewire;

use App\Livewire\Concerns\GereLignesSeances;
use App\Models\Cours;
use App\Models\Fidele;
use App\Models\Programme;
use App\Models\TypeIntervention;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ProgrammeDetail extends Component
{
    use GereLignesSeances;

    public Programme $programme;

    /** @var array<int, int|string> */
    public array $coursIds = [];

    public string $nouveauPassages = '';

    public string $statut = '';

    public string $commentaire = '';

    public ?string $message = null;

    public ?string $erreur = null;

    public function mount(Programme $programme): void
    {
        $this->programme = $programme;
        $this->statut = $programme->statut;
        $this->commentaire = $programme->commentaire ?? '';
        $this->coursIds = $programme->cours->pluck('id')->map(fn (int $id): string => (string) $id)->all();
        $this->programmeId = $programme->id;

        $this->ajouterLigne();
    }

    public function ajouterEtAssocierCours(): void
    {
        $this->validate([
            'nouveauPassages' => ['nullable', 'string', 'max:150'],
        ]);

        if (trim($this->nouveauPassages) === '') {
            return;
        }

        $cours = Cours::create(['passages' => trim($this->nouveauPassages)]);

        $this->programme->cours()->attach($cours->id);
        $this->coursIds[] = (string) $cours->id;
        $this->nouveauPassages = '';

        $this->message = 'Cours créé et associé au programme.';
    }

    public function associerCours(): void
    {
        if (empty($this->coursIds)) {
            return;
        }

        $this->programme->cours()->sync(array_map('intval', $this->coursIds));

        $this->message = 'Cours associés au programme.';
    }

    public function enregistrerStatut(): void
    {
        $this->validate([
            'statut' => ['required', 'string', 'max:255'],
            'commentaire' => ['nullable', 'string', 'max:255'],
        ]);

        $this->programme->update([
            'statut' => $this->statut,
            'commentaire' => $this->commentaire ?: null,
        ]);

        $this->message = 'Programme mis à jour.';
    }

    public function render(): View
    {
        return view('livewire.programme-detail', [
            'cours' => Cours::latest()->get()->map(fn (Cours $cours) => [
                'id' => $cours->id,
                'passages' => $cours->passages,
            ])->values(),
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
            'seances' => $this->programme->seances()->orderBy('numero_seance')->get()->map(fn ($seance) => [
                'id' => $seance->id,
                'numero' => $seance->numero_seance,
                'date_seance' => $seance->date_seance,
                'heure_debut' => $seance->heure_debut,
                'heure_fin' => $seance->heure_fin,
                'lieu' => $seance->lieu,
                'delai_rappel' => $seance->delai_rappel,
            ])->values(),
            'infos' => [
                'type' => $this->programme->formationNus->isNotEmpty() ? 'NU' : ($this->programme->formationOrds->isNotEmpty() ? 'Ord' : null),
                'libelle' => $this->programme->formationNus->first()?->session ?? $this->programme->formationOrds->first()?->theme,
                'statut' => $this->programme->statut,
            ],
        ]);
    }
}
