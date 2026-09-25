<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInterventionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type_intervention_id' => ['required', 'exists:type_interventions,id'],
            'fidele_id' => ['required', 'exists:fideles,id'],
            'seance_id' => ['required', 'exists:seances,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'type_intervention_id.required' => 'Le type d\'intervention est obligatoire.',
            'type_intervention_id.exists' => 'Le type d\'intervention choisi n\'existe pas.',
            'fidele_id.required' => 'Le fidèle est obligatoire.',
            'fidele_id.exists' => 'Le fidèle choisi n\'existe pas.',
            'seance_id.required' => 'La séance est obligatoire.',
            'seance_id.exists' => 'La séance choisie n\'existe pas.',
        ];
    }
}
