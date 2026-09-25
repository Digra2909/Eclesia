<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePresenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'est_present' => ['required', 'boolean'],
            'fidele_id' => ['required', 'exists:fideles,id'],
            'seance_id' => ['required', 'exists:seances,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'est_present.required' => 'Indiquez si le fidèle est présent.',
            'est_present.boolean' => 'La présence doit être vraie ou fausse.',
            'fidele_id.required' => 'Le fidèle est obligatoire.',
            'fidele_id.exists' => 'Le fidèle choisi n\'existe pas.',
            'seance_id.required' => 'La séance est obligatoire.',
            'seance_id.exists' => 'La séance choisie n\'existe pas.',
        ];
    }
}
