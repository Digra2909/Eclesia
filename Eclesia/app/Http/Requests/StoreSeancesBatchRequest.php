<?php

namespace App\Http\Requests;

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
            'seances' => ['required', 'array', 'min:1'],
            'seances.*.numero_seance' => ['required', 'integer', 'min:1'],
            'seances.*.date_seance' => ['required', 'date'],
            'seances.*.heure_debut' => ['required', 'date_format:H:i'],
            'seances.*.heure_fin' => ['nullable', 'date_format:H:i', 'after:seances.*.heure_debut'],
            'seances.*.lieu' => ['nullable', 'string', 'max:50'],
            'seances.*.delai_rappel' => ['nullable', 'integer'],
            'seances.*.programme_id' => ['required', 'exists:programmes,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'seances.required' => 'Ajoutez au moins une séance.',
            'seances.*.numero_seance.required' => 'Le numéro de séance est obligatoire.',
            'seances.*.numero_seance.min' => 'Le numéro de séance doit être au moins 1.',
            'seances.*.date_seance.required' => 'La date de la séance est obligatoire.',
            'seances.*.heure_debut.required' => 'L\'heure de début est obligatoire.',
            'seances.*.heure_debut.date_format' => 'L\'heure de début doit être au format HH:MM.',
            'seances.*.heure_fin.date_format' => 'L\'heure de fin doit être au format HH:MM.',
            'seances.*.heure_fin.after' => 'L\'heure de fin doit être après l\'heure de début.',
            'seances.*.lieu.max' => 'Le lieu ne doit pas dépasser 50 caractères.',
            'seances.*.programme_id.required' => 'Le programme est obligatoire.',
            'seances.*.programme_id.exists' => 'Le programme choisi n\'existe pas.',
        ];
    }
}
