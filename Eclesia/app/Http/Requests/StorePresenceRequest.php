<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePresenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Le code_fidele est la voie prioritaire : c'est lui qui identifie
            // le fidèle. Le scanner QR renvoie le même code_fidele (voie 2).
            'code_fidele' => ['required', 'string', 'max:255'],
            'seance_id' => ['nullable', 'exists:seances,id'],
            'est_present' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code_fidele.required' => 'Le code du fidèle est obligatoire.',
            'seance_id.exists' => 'La séance choisie n\'existe pas.',
            'est_present.boolean' => 'La présence doit être vraie ou fausse.',
        ];
    }
}
