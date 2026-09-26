<?php

namespace App\Livewire\Concerns;

use App\Models\Seance;
use Illuminate\Support\Facades\DB;

/**
 * Partagé par les composants qui saisissent plusieurs séances d'un coup
 * (page Séances et page détail d'un programme) : lignes dynamiques, numéro
 * automatique et validation par lot.
 */
trait GereLignesSeances
{
    public int|string|null $programmeId = null;

    /** @var array<int, array<string, mixed>> */
    public array $lignes = [];

    public function ajouterLigne(): void
    {
        $this->lignes[] = [
            'date_seance' => '',
            'heure_debut' => '',
            'heure_fin' => '',
            'lieu' => '',
            'delai_rappel' => '',
            'intervention1_fidele' => '',
            'intervention1_type' => '',
            'intervention2_fidele' => '',
            'intervention2_type' => '',
        ];
    }

    public function retirerLigne(int $index): void
    {
        unset($this->lignes[$index]);

        $this->lignes = array_values($this->lignes);

        if (empty($this->lignes)) {
            $this->ajouterLigne();
        }
    }

    public function numeroDe(int $index): int
    {
        return $this->maxNumeroSeance() + $index + 1;
    }

    public function maxNumeroSeance(): int
    {
        if (! $this->programmeId) {
            return 0;
        }

        return (int) (Seance::where('programme_id', $this->programmeId)->max('numero_seance') ?? 0);
    }

    public function reglesSeances(): array
    {
        return [
            'programmeId' => ['required', 'numeric', 'exists:programmes,id'],
            'lignes' => ['required', 'array', 'min:1'],
            'lignes.*.date_seance' => ['required', 'date_format:Y-m-d'],
            'lignes.*.heure_debut' => ['required', 'date_format:H:i'],
            'lignes.*.heure_fin' => ['nullable', 'date_format:H:i'],
            'lignes.*.lieu' => ['nullable', 'string', 'max:50'],
            'lignes.*.delai_rappel' => ['nullable', 'integer'],
            'lignes.*.intervention1_fidele' => ['nullable', 'integer', 'exists:fideles,id'],
            'lignes.*.intervention1_type' => ['nullable', 'integer', 'exists:type_interventions,id'],
            'lignes.*.intervention2_fidele' => ['nullable', 'integer', 'exists:fideles,id'],
            'lignes.*.intervention2_type' => ['nullable', 'integer', 'exists:type_interventions,id'],
        ];
    }

    /**
     * Contraintes transversales : heure de fin après l'heure de début et
     * dates croissantes. Retourne true si une erreur a été ajoutée.
     */
    public function contraintesParLot(): bool
    {
        $erreurs = false;
        $datePrecedente = null;

        foreach ($this->lignes as $index => $ligne) {
            if (! empty($ligne['heure_fin']) && ! empty($ligne['heure_debut'])
                && strtotime($ligne['heure_fin']) <= strtotime($ligne['heure_debut'])) {
                $this->addError("lignes.$index.heure_fin", 'L\'heure de fin doit être après l\'heure de début.');

                $erreurs = true;
            }

            $date = ! empty($ligne['date_seance']) ? strtotime($ligne['date_seance']) : null;
            if ($date !== null && $datePrecedente !== null && $date < $datePrecedente) {
                $this->addError("lignes.$index.date_seance", 'La date d\'une séance ne peut pas être antérieure à celle de la séance précédente.');

                $erreurs = true;
            }

            if ($date !== null) {
                $datePrecedente = $date;
            }
        }

        return $erreurs;
    }

    public function enregistrerSeances(): void
    {
        $this->validate($this->reglesSeances());

        if ($this->contraintesParLot()) {
            return;
        }

        $programmeId = (int) $this->programmeId;
        $base = $this->maxNumeroSeance();

        $creees = DB::transaction(function () use ($programmeId, $base) {
            return collect($this->lignes)
                ->map(function (array $ligne, int $index) use ($programmeId, $base) {
                    $seance = Seance::create([
                        ...$ligne,
                        'lieu' => ! empty($ligne['lieu']) ? $ligne['lieu'] : 'le temple de l\'église',
                        'delai_rappel' => $ligne['delai_rappel'] !== '' ? $ligne['delai_rappel'] : null,
                        'numero_seance' => $base + $index + 1,
                        'programme_id' => $programmeId,
                    ]);

                    $this->creerInterventions($seance, $ligne);

                    return $seance;
                })
                ->values();
        });

        $this->lignes = [];
        $this->ajouterLigne();

        $nbInterventions = DB::table('interventions')
            ->whereIn('seance_id', $creees->pluck('id'))
            ->count();

        $this->message = $creees->count().' séance(s) enregistrée(s) avec succès'
            .($nbInterventions > 0 ? ' et '.$nbInterventions.' intervention(s).' : '.');
    }

    /**
     * Crée les interventions associées à la séance à partir des 2 lignes
     * réservées dans le formulaire (fidèle + type d'intervention). Une même
     * combinaison (fidèle, type) n'est pas dupliquée. Les lignes incomplètes
     * (fidèle ou type manquant) sont ignorées.
     */
    private function creerInterventions(Seance $seance, array $ligne): void
    {
        $interventions = [];

        foreach ([1, 2] as $numero) {
            $fideleId = $ligne["intervention{$numero}_fidele"] ?? '';
            $typeId = $ligne["intervention{$numero}_type"] ?? '';

            if ($fideleId === '' || $typeId === '') {
                continue;
            }

            $cle = $fideleId.':'.$typeId;
            $interventions[$cle] = [
                'fidele_id' => (int) $fideleId,
                'type_intervention_id' => (int) $typeId,
            ];
        }

        if ($interventions !== []) {
            $seance->interventions()->createMany(array_values($interventions));
        }
    }
}
