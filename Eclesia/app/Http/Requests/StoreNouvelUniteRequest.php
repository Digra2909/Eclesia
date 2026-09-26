<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNouvelUniteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles conformes à la table `fideles` (le code et le QR sont générés
     * automatiquement dans le contrôleur NouvelUniteController).
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:20'],
            'postnom' => ['required', 'string', 'max:20'],
            'prenom' => ['required', 'string', 'max:20'],
            'date_naissance' => ['nullable', 'date'],
            'telephone' => ['nullable', 'regex:/^[0-9]{9}$/'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'grace' => ['nullable', 'string'],
            'genre' => ['required', 'in:M,F'],
            'statut_nu' => ['required', 'in:en règle,non en règle'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.max' => 'Le nom ne doit pas dépasser 20 caractères.',
            'postnom.required' => 'Le post-nom est obligatoire.',
            'postnom.max' => 'Le post-nom ne doit pas dépasser 20 caractères.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.max' => 'Le prénom ne doit pas dépasser 20 caractères.',
            'telephone.regex' => 'Le téléphone doit contenir exactement 9 chiffres (sans le +243).',
            'adresse.max' => 'L\'adresse ne doit pas dépasser 255 caractères.',
            'genre.required' => 'Le genre est obligatoire.',
            'genre.in' => 'Le genre doit être M ou F.',
            'statut_nu.required' => 'Le statut NU est obligatoire.',
            'statut_nu.in' => 'Le statut NU doit être "en règle" ou "non en règle".',
        ];
    }
}
