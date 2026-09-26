<?php

namespace App\Livewire;

use App\Models\Fidele;
use App\Models\Presence;
use App\Models\Seance;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Presences extends Component
{
    public string $codeFidele = '';

    public string $dateFiltre = '';

    public string $nomFiltre = '';

    public string $codeFiltre = '';

    public string $onglet = 'gestion';

    public string $moisStats = '';

    public ?string $message = null;

    public ?string $erreur = null;

    public function mount(): void
    {
        $this->dateFiltre = today()->format('Y-m-d');
        $this->moisStats = today()->format('Y-m');
    }

    /**
     * Règle générale : les codes fidèles sont stockés au format « COMP-CNTRL-<n°> ».
     * L'entrée de l'opérateur ne contient que le numéro ; le préfixe est ajouté ici.
     */
    private function codeFideleComplet(string $saisie): string
    {
        $saisie = mb_strtoupper(trim($saisie));

        if (str_starts_with($saisie, 'COMP-CNTRL-')) {
            return $saisie;
        }

        return 'COMP-CNTRL-'.ltrim($saisie, '0');
    }

    public function enregistrer(): void
    {
        $this->validate([
            'codeFidele' => ['required', 'string', 'max:20'],
        ]);

        $this->erreur = null;
        $this->message = null;

        if ($this->dateFiltre !== today()->format('Y-m-d')) {
            $this->erreur = 'Les présences ne peuvent être enregistrées qu\'à la date du jour.';

            return;
        }

        $fidele = Fidele::where('code_fidele', $this->codeFideleComplet($this->codeFidele))->first();

        if (! $fidele) {
            $this->erreur = 'Aucun fidèle ne correspond à ce code ('.$this->codeFidele.').';

            return;
        }

        $seance = Seance::whereDate('date_seance', $this->dateFiltre)->first();

        if (! $seance) {
            $this->erreur = 'Aucune séance n\'est planifiée à cette date. Planifiez une séance.';

            return;
        }

        $presence = Presence::firstOrCreate(
            ['fidele_id' => $fidele->id, 'seance_id' => $seance->id],
            ['est_present' => true],
        );

        $nom = trim($fidele->prenom.' '.$fidele->nom);

        if ($presence->wasRecentlyCreated) {
            $this->message = 'Présence enregistrée pour '.$nom;
        } else {
            $this->message = $nom.' est déjà enregistré(e) à cette séance.';
        }

        $this->codeFidele = '';
    }

    public function render(): View
    {
        $mois = $this->moisStats ? explode('-', $this->moisStats) : null;

        $classement = Fidele::withCount(['presences' => function ($q) use ($mois) {
            $q->where('est_present', true);

            if ($mois) {
                $q->whereHas('seance', fn ($s) => $s
                    ->whereYear('date_seance', (int) $mois[0])
                    ->whereMonth('date_seance', (int) $mois[1]));
            }
        }])
            ->orderByDesc('presences_count')
            ->orderBy('nom')
            ->get()
            ->filter(fn (Fidele $fidele) => $fidele->presences_count > 0)
            ->values()
            ->map(fn (Fidele $fidele, int $index) => [
                'rang' => $index + 1,
                'nom' => trim($fidele->prenom.' '.$fidele->nom.' '.$fidele->postnom),
                'code_fidele' => $fidele->code_fidele,
                'total' => $fidele->presences_count,
            ])
            ->values();

        return view('livewire.presences', [
            'seanceDuJour' => Seance::whereDate('date_seance', $this->dateFiltre)->first(),
            'presences' => $this->listePresences(),
            'classement' => $classement,
        ]);
    }

    private function listePresences(): Collection
    {
        $terme = trim($this->nomFiltre);

        return Presence::with(['fidele', 'seance'])
            ->whereHas('seance', fn ($q) => $q->whereDate('date_seance', $this->dateFiltre))
            ->when($terme !== '', function ($q) use ($terme) {
                return $q->whereHas('fidele', function ($f) use ($terme) {
                    $f->where('nom', 'like', '%'.$terme.'%')
                        ->orWhere('postnom', 'like', '%'.$terme.'%')
                        ->orWhere('prenom', 'like', '%'.$terme.'%');
                });
            })
            ->when(trim($this->codeFiltre) !== '', function ($q) {
                return $q->whereHas('fidele', fn ($f) => $f->where('code_fidele', $this->codeFideleComplet($this->codeFiltre)));
            })
            ->latest()
            ->get()
            ->map(fn (Presence $presence) => [
                'id' => $presence->id,
                'nom' => trim($presence->fidele?->prenom.' '.$presence->fidele?->nom),
                'code_fidele' => $presence->fidele?->code_fidele,
                'date_seance' => $presence->seance?->date_seance,
                'heure_debut' => $presence->seance?->heure_debut,
                'lieu' => $presence->seance?->lieu,
            ])->values();
    }
}
