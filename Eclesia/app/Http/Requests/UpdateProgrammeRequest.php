<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProgrammeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'montant' => ['nullable', 'integer', 'min:0'],
            'statut' => ['required', 'in:créé,programmé,validé,en cours,cloturé'],
            'commentaire' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'montant.integer' => 'Le montant doit être un entier.',
            'montant.min' => 'Le montant ne peut pas être négatif.',
            'statut.required' => 'Le statut du programme est obligatoire.',
            'statut.in' => 'Le statut doit être l\'un des suivants : créé, programmé, validé, en cours, cloturé.',
        ];
    }
}
