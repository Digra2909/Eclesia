<?php

namespace App\Http\Requests;

use App\Models\Seance;
use Illuminate\Foundation\Http\FormRequest;

class StoreSeancesBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation d'un lot de séances ajoutées en une seule fois depuis la
     * modale (un formulaire répété, indexé par `seances`).
     */
    public function rules(): array
    {
        return [
            'programme_id' => ['required', 'exists:programmes,id'],
            'seances' => ['required', 'array', 'min:1'],
            'seances.*.numero_seance' => ['required', 'integer', 'min:1'],
            'seances.*.date_seance' => ['required', 'date_format:Y-m-d'],
            'seances.*.heure_debut' => ['required', 'date_format:H:i'],
            'seances.*.heure_fin' => ['nullable', 'date_format:H:i'],
            'seances.*.lieu' => ['nullable', 'string', 'max:50'],
            'seances.*.delai_rappel' => ['nullable', 'integer'],
        ];
    }

    /**
     * Contraintes par lot : numéros consécutifs, dates croissantes et
     * heure de fin toujours postérieure à l'heure de début.
     */
    public function after(): array
    {
        return [
            function ($validator) {
                $programmeId = $this->input('programme_id');
                $rows = $this->input('seances', []);

                $datePrecedente = null;
                $numeroPrecedent = 0;

                foreach ($rows as $index => $row) {
                    if ($numeroPrecedent !== 0 && (int) $row['numero_seance'] !== $numeroPrecedent + 1) {
                        $validator->errors()->add(
                            "seances.$index.numero_seance",
                            'Les numéros de séances doivent se suivre (1, 2, 3…).'
                        );
                    }

                    if ($datePrecedente !== null && strtotime($row['date_seance']) < $datePrecedente) {
                        $validator->errors()->add(
                            "seances.$index.date_seance",
                            'La date d\'une séance ne peut pas être antérieure à celle de la séance précédente.'
                        );
                    }

                    if (! empty($row['heure_fin']) && strtotime($row['heure_fin']) <= strtotime($row['heure_debut'])) {
                        $validator->errors()->add(
                            "seances.$index.heure_fin",
                            'L\'heure de fin doit être après l\'heure de début.'
                        );
                    }

                    $datePrecedente = strtotime($row['date_seance']);
                    $numeroPrecedent = (int) $row['numero_seance'];
                }

                if ($programmeId !== null && ! empty($rows)) {
                    $max = Seance::where('programme_id', $programmeId)->max('numero_seance') ?? 0;
                    if ((int) $rows[0]['numero_seance'] !== $max + 1) {
                        $validator->errors()->add(
                            $max > 0 ? 'seances' : 'seances.0.numero_seance',
                            $max > 0
                                ? 'Ce programme possède déjà des séances : numérotez à partir de '.($max + 1).'.'
                                : 'La première séance doit porter le numéro 1.'
                        );
                    }
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'programme_id.required' => 'Le programme est obligatoire.',
            'programme_id.exists' => 'Le programme choisi n\'existe pas.',
            'seances.required' => 'Ajoutez au moins une séance.',
            'seances.min' => 'Ajoutez au moins une séance.',
            'seances.*.numero_seance.required' => 'Le numéro de séance est obligatoire.',
            'seances.*.numero_seance.min' => 'Le numéro de séance doit être au moins 1.',
            'seances.*.date_seance.required' => 'La date de la séance est obligatoire.',
            'seances.*.date_seance.date_format' => 'La date de la séance doit être au format AAAA-MM-JJ.',
            'seances.*.heure_debut.required' => 'L\'heure de début est obligatoire.',
            'seances.*.heure_debut.date_format' => 'L\'heure de début doit être au format HH:MM.',
            'seances.*.heure_fin.date_format' => 'L\'heure de fin doit être au format HH:MM.',
            'seances.*.lieu.max' => 'Le lieu ne doit pas dépasser 50 caractères.',
        ];
    }
}
