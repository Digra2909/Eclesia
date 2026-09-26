<?php

namespace App\Livewire;

use App\Models\Cours;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CoursSection extends Component
{
    public ?int $editeId = null;

    public string $passages = '';

    public ?string $message = null;

    public ?string $erreur = null;

    public function editer(int $id): void
    {
        $cours = Cours::findOrFail($id);

        $this->editeId = $cours->id;
        $this->passages = $cours->passages;
        $this->message = null;
        $this->erreur = null;
    }

    public function annulerEdition(): void
    {
        $this->resetValidation();

        $this->reset(['editeId', 'passages', 'message', 'erreur']);
    }

    public function enregistrer(): void
    {
        $this->validate([
            'passages' => ['required', 'string', 'max:150'],
        ]);

        try {
            if ($this->editeId) {
                Cours::findOrFail($this->editeId)->update(['passages' => $this->passages]);

                $this->message = 'Cours mis à jour.';
            } else {
                Cours::create(['passages' => $this->passages]);

                $this->message = 'Cours ajouté.';
            }

            $this->reset(['editeId', 'passages']);
        } catch (\Throwable $e) {
            $this->erreur = 'Une erreur est survenue : '.$e->getMessage();
        }
    }

    public function render(): View
    {
        return view('livewire.cours-section', [
            'cours' => Cours::latest()->get()->map(fn (Cours $cours) => [
                'id' => $cours->id,
                'passages' => $cours->passages,
                'nb_programmes' => $cours->programmes()->count(),
            ])->values(),
        ]);
    }
}
