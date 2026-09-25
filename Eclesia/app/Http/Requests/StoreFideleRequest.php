<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFideleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation conformes à la table `fideles`.
     */
    public function rules(): array
    {
        return [
            'code_fidele' => ['required', 'string', 'max:15', 'unique:fideles,code_fidele'],
            'nom' => ['required', 'string', 'max:20'],
            'postnom' => ['required', 'string', 'max:20'],
            'prenom' => ['required', 'string', 'max:20'],
            'date_naissance' => ['nullable', 'date'],
            'telephone' => ['nullable', 'string', 'max:13'],
            'grace' => ['nullable', 'string'],
            'genre' => ['required', 'in:M,F'],
            'path_qr_code' => ['required', 'string'],
            'statut_id' => ['nullable', 'exists:statut_fideles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'code_fidele.required' => 'Le code fidèle est obligatoire.',
            'code_fidele.unique' => 'Ce code fidèle existe déjà.',
            'code_fidele.max' => 'Le code fidèle ne doit pas dépasser 15 caractères.',
            'nom.required' => 'Le nom est obligatoire.',
            'nom.max' => 'Le nom ne doit pas dépasser 20 caractères.',
            'postnom.required' => 'Le post-nom est obligatoire.',
            'postnom.max' => 'Le post-nom ne doit pas dépasser 20 caractères.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.max' => 'Le prénom ne doit pas dépasser 20 caractères.',
            'telephone.max' => 'Le téléphone ne doit pas dépasser 13 caractères.',
            'genre.required' => 'Le genre est obligatoire.',
            'genre.in' => 'Le genre doit être M ou F.',
            'path_qr_code.required' => 'Le QR code est obligatoire.',
            'statut_id.exists' => 'Le statut choisi n\'existe pas.',
        ];
    }
}
