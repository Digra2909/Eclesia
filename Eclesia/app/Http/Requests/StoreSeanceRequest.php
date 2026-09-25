<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSeanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'numero_seance' => ['required', 'integer', 'min:1'],
            'date_seance' => ['required', 'date'],
            'heure_debut' => ['required', 'date_format:H:i'],
            'heure_fin' => ['nullable', 'date_format:H:i', 'after:heure_debut'],
            'lieu' => ['nullable', 'string', 'max:50'],
            'delai_rappel' => ['nullable', 'integer'],
            'programme_id' => ['required', 'exists:programmes,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'numero_seance.required' => 'Le numéro de séance est obligatoire.',
            'numero_seance.min' => 'Le numéro de séance doit être au moins 1.',
            'date_seance.required' => 'La date de la séance est obligatoire.',
            'heure_debut.required' => 'L\'heure de début est obligatoire.',
            'heure_debut.date_format' => 'L\'heure de début doit être au format HH:MM.',
            'heure_fin.date_format' => 'L\'heure de fin doit être au format HH:MM.',
            'heure_fin.after' => 'L\'heure de fin doit être après l\'heure de début.',
            'lieu.max' => 'Le lieu ne doit pas dépasser 50 caractères.',
            'programme_id.required' => 'Le programme est obligatoire.',
            'programme_id.exists' => 'Le programme choisi n\'existe pas.',
        ];
    }
}
