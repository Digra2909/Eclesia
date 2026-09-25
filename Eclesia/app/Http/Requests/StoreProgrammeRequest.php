<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgrammeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:NU,Ord'],
            'session' => ['nullable', 'string', 'max:30', 'required_if:type,NU'],
            'theme' => ['nullable', 'string', 'max:30', 'required_if:type,Ord'],
            'montant' => ['nullable', 'integer', 'min:0'],
            'statut' => ['required', 'in:créé,programmé,validé,en cours,cloturé'],
            'commentaire' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Le type de programme est obligatoire.',
            'type.in' => 'Le type doit être NU ou Ord.',
            'session.required_if' => 'La session est obligatoire pour un programme NU.',
            'session.max' => 'La session ne doit pas dépasser 30 caractères.',
            'theme.required_if' => 'Le thème est obligatoire pour un programme Ord.',
            'theme.max' => 'Le thème ne doit pas dépasser 30 caractères.',
            'montant.integer' => 'Le montant doit être un entier.',
            'montant.min' => 'Le montant ne peut pas être négatif.',
            'statut.required' => 'Le statut du programme est obligatoire.',
            'statut.in' => 'Le statut doit être l\'un des suivants : créé, programmé, validé, en cours, cloturé.',
        ];
    }
}
