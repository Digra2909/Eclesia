<?php

namespace App\Livewire;

use App\Models\Cours;
use App\Models\Poste;
use App\Models\StatutFidele;
use App\Models\TypeIntervention;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Parametres extends Component
{
    public string $type = 'statut';

    public ?int $editeId = null;

    public string $valeur = '';

    public ?string $message = null;

    public function config(): array
    {
        return match ($this->type) {
            'type' => ['classe' => TypeIntervention::class, 'colonne' => 'designation', 'libelle' => 'Type d\'intervention', 'route' => 'type-interventions.destroy'],
            'poste' => ['classe' => Poste::class, 'colonne' => 'designation', 'libelle' => 'Poste des ouvriers', 'route' => 'postes.destroy'],
            'cours' => ['classe' => Cours::class, 'colonne' => 'passages', 'libelle' => 'Cours / passages', 'route' => 'cours.destroy'],
            default => ['classe' => StatutFidele::class, 'colonne' => 'designation', 'libelle' => 'Statut du fidèle', 'route' => 'statut-fideles.destroy'],
        };
    }

    public function routeDestruction(int $id): string
    {
        $config = $this->config();

        return route($config['route'], [$id]);
    }

    public function editer(int $id, string $valeur): void
    {
        $this->editeId = $id;
        $this->valeur = $valeur;
        $this->message = null;
    }

    public function annulerEdition(): void
    {
        $this->resetValidation();

        $this->reset(['editeId', 'valeur']);
    }

    public function enregistrer(): void
    {
        $this->validate([
            'valeur' => ['required', 'string', 'max:255'],
        ]);

        $config = $this->config();

        try {
            if ($this->editeId) {
                $config['classe']::findOrFail($this->editeId)->update([$config['colonne'] => trim($this->valeur)]);

                $this->message = 'Élément mis à jour.';
            } else {
                $config['classe']::create([$config['colonne'] => trim($this->valeur)]);

                $this->message = 'Élément ajouté.';
            }

            $this->reset(['editeId', 'valeur']);
        } catch (\Throwable $e) {
            $this->addError('valeur', $e->getMessage());
        }
    }

    public function render(): View
    {
        $config = $this->config();

        return view('livewire.parametres', [
            'config' => $config,
            'elements' => $config['classe']::orderBy($config['colonne'])->pluck($config['colonne'], 'id'),
        ]);
    }
}
