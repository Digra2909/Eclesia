<?php

namespace App\Livewire;

use App\Models\Fidele;
use App\Models\Nu;
use App\Models\StatutFidele;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class Unites extends Component
{
    public ?int $editeId = null;

    public string $nom = '';

    public string $prenom = '';

    public string $postnom = '';

    public ?string $date_naissance = null;

    public ?string $telephone = null;

    public ?string $adresse = null;

    public string $genre = 'M';

    public string $statut_nu = 'en règle';

    public ?string $grace = null;

    public ?string $message = null;

    public ?string $erreur = null;

    public function editer(int $id): void
    {
        $fidele = Fidele::with('nu')->findOrFail($id);

        $this->editeId = $fidele->id;
        $this->nom = $fidele->nom;
        $this->prenom = $fidele->prenom;
        $this->postnom = $fidele->postnom;
        $this->date_naissance = $fidele->date_naissance?->format('Y-m-d');
        $this->telephone = $fidele->telephone ? substr($fidele->telephone, -9) : null;
        $this->adresse = $fidele->adresse;
        $this->genre = $fidele->genre ?: 'M';
        $this->statut_nu = $fidele->nu?->statut ?? 'en règle';
        $this->grace = $fidele->grace;

        $this->message = null;
        $this->erreur = null;
    }

    public function annulerEdition(): void
    {
        $this->resetValidation();

        $this->reset(['editeId', 'nom', 'prenom', 'postnom', 'date_naissance', 'telephone', 'adresse', 'genre', 'statut_nu', 'grace', 'message', 'erreur']);
    }

    public function enregistrer(): void
    {
        $donnees = $this->validate([
            'nom' => ['required', 'string', 'max:20'],
            'prenom' => ['required', 'string', 'max:20'],
            'postnom' => ['required', 'string', 'max:20'],
            'date_naissance' => ['nullable', 'date'],
            'telephone' => ['nullable', 'regex:/^[0-9]{9}$/'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'genre' => ['required', 'in:M,F'],
            'statut_nu' => ['required', 'in:en règle,non en règle'],
            'grace' => ['nullable', 'string', 'max:255'],
        ]);

        $telephone = $donnees['telephone'] ? '+243'.ltrim($donnees['telephone'], '0') : null;
        $statutId = StatutFidele::firstOrCreate(['designation' => $donnees['statut_nu']])->id;

        try {
            if ($this->editeId) {
                $fidele = Fidele::findOrFail($this->editeId);

                $fidele->update([
                    'nom' => $donnees['nom'],
                    'prenom' => $donnees['prenom'],
                    'postnom' => $donnees['postnom'],
                    'date_naissance' => $donnees['date_naissance'],
                    'telephone' => $telephone,
                    'adresse' => $donnees['adresse'],
                    'genre' => $donnees['genre'],
                    'grace' => $donnees['grace'],
                    'statut_id' => $statutId,
                ]);

                $fidele->nu?->update(['statut' => $donnees['statut_nu']]);

                $this->message = 'Unité « '.trim($donnees['prenom'].' '.$donnees['nom']).' » mise à jour.';
            } else {
                // Règle générale : code fidèle généré automatiquement, toujours
                // au format « COMP-CNTRL-<id> » (la saisie du numéro ne sert
                // qu'à la recherche, p. ex. page Présences).
                $fidele = Fidele::create([
                    'code_fidele' => 'X-'.Str::random(12),
                    'nom' => $donnees['nom'],
                    'prenom' => $donnees['prenom'],
                    'postnom' => $donnees['postnom'],
                    'date_naissance' => $donnees['date_naissance'],
                    'telephone' => $telephone,
                    'adresse' => $donnees['adresse'],
                    'genre' => $donnees['genre'],
                    'grace' => $donnees['grace'],
                    'statut_id' => $statutId,
                ]);

                $fidele->update(['code_fidele' => 'COMP-CNTRL-'.$fidele->id]);

                Nu::create([
                    'code_nu' => 'NU-'.$fidele->id,
                    'statut' => $donnees['statut_nu'],
                    'fidele_id' => $fidele->id,
                ]);

                $this->message = 'Nouvelle unité créée ('.$fidele->code_fidele.' / NU-'.$fidele->id.').';
            }

            $this->reset(['editeId', 'nom', 'prenom', 'postnom', 'date_naissance', 'telephone', 'adresse', 'genre', 'statut_nu', 'grace']);
        } catch (\Throwable $e) {
            $this->erreur = 'Une erreur est survenue : '.$e->getMessage();
        }
    }

    public function render(): View
    {
        return view('livewire.unites', [
            'unites' => $this->listeUnites(),
        ]);
    }

    private function listeUnites(): Collection
    {
        return Fidele::with(['statut', 'nu'])->latest()->get()->map(fn (Fidele $fidele) => [
            'id' => $fidele->id,
            'code_fidele' => $fidele->code_fidele,
            'nom' => $fidele->nom.' '.$fidele->postnom,
            'prenom' => $fidele->prenom,
            'postnom' => $fidele->postnom,
            'genre' => $fidele->genre,
            'telephone' => $fidele->telephone,
            'adresse' => $fidele->adresse,
            'date_naissance' => $fidele->date_naissance?->format('d/m/Y'),
            'grace' => $fidele->grace,
            'statut_nu' => $fidele->nu?->statut ?? 'en règle',
            'code_nu' => $fidele->nu?->code_nu,
        ])->values();
    }
}
