<?php

namespace App\Livewire;

use App\Models\FormationNu;
use App\Models\FormationOrd;
use App\Models\Programme;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Programmes extends Component
{
    public ?int $editeId = null;

    public string $type = 'NU';

    public string $session = '';

    public string $theme = '';

    public string $commentaire = '';

    public ?string $message = null;

    public ?string $erreur = null;

    public ?int $dernierId = null;

    public function editer(int $id): void
    {
        $programme = Programme::with(['formationNus', 'formationOrds'])->findOrFail($id);

        $this->editeId = $programme->id;
        $this->commentaire = $programme->commentaire ?? '';
        $this->session = $programme->formationNus->first()?->session ?? '';
        $this->theme = $programme->formationOrds->first()?->theme ?? '';
        $this->type = ! empty($this->session) ? 'NU' : 'Ord';
        $this->dernierId = null;
        $this->message = null;
        $this->erreur = null;
    }

    public function annulerEdition(): void
    {
        $this->resetValidation();

        $this->reset(['editeId', 'type', 'session', 'theme', 'commentaire', 'message', 'erreur', 'dernierId']);
    }

    public function enregistrer(): void
    {
        $this->validate([
            'type' => ['required', 'in:NU,Ord'],
            'session' => ['required_if:type,NU', 'nullable', 'string', 'max:150'],
            'theme' => ['required_if:type,Ord', 'nullable', 'string', 'max:150'],
            'commentaire' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            if ($this->editeId) {
                Programme::findOrFail($this->editeId)->update([
                    'commentaire' => $this->commentaire ?: null,
                ]);

                $this->message = 'Programme mis à jour.';
            } else {
                $programme = Programme::create([
                    'statut' => 'créé',
                    'commentaire' => $this->commentaire ?: null,
                ]);

                if ($this->type === 'Ord') {
                    FormationOrd::create([
                        'theme' => $this->theme,
                        'programme_id' => $programme->id,
                    ]);
                } else {
                    FormationNu::create([
                        'session' => $this->session,
                        'programme_id' => $programme->id,
                    ]);
                }

                $this->dernierId = $programme->id;
                $this->message = 'Programme créé. Complétez-le maintenant : associez les cours, puis ajoutez les séances.';
            }

            $this->reset(['editeId', 'type', 'session', 'theme', 'commentaire']);
        } catch (\Throwable $e) {
            $this->erreur = 'Une erreur est survenue : '.$e->getMessage();
        }
    }

    public function render(): View
    {
        return view('livewire.programmes', [
            'programmes' => $this->listeProgrammes(),
        ]);
    }

    private function listeProgrammes(): Collection
    {
        return Programme::with(['formationNus', 'formationOrds', 'seances', 'cours'])->latest()->get()
            ->map(fn (Programme $programme) => [
                'id' => $programme->id,
                'type' => $programme->formationNus->isNotEmpty() ? 'NU' : ($programme->formationOrds->isNotEmpty() ? 'Ord' : null),
                'libelle' => $programme->formationNus->first()?->session ?? $programme->formationOrds->first()?->theme ?? 'Programme #'.$programme->id,
                'statut' => $programme->statut,
                'commentaire' => $programme->commentaire,
                'nb_seances' => $programme->seances->count(),
                'nb_cours' => $programme->cours->count(),
            ])->values();
    }
}
