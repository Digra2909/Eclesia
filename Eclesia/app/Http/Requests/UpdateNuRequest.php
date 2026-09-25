<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code_nu' => ['required', 'string', 'max:6', 'unique:nus,code_nu,'.$this->route('nu')->id],
            'note_oral' => ['nullable', 'numeric', 'min:0', 'max:99.9'],
            'note_ecrite' => ['nullable', 'numeric', 'min:0', 'max:99.9'],
            'pourcentage' => ['nullable', 'numeric', 'min:0', 'max:99.9'],
            'statut' => ['required', 'in:en règle,non en règle'],
            'fidele_id' => ['required', 'exists:fideles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'code_nu.required' => 'Le code NU est obligatoire.',
            'code_nu.unique' => 'Ce code NU existe déjà.',
            'code_nu.max' => 'Le code NU ne doit pas dépasser 6 caractères.',
            'note_oral.numeric' => 'La note orale doit être numérique.',
            'note_ecrite.numeric' => 'La note écrite doit être numérique.',
            'pourcentage.numeric' => 'Le pourcentage doit être numérique.',
            'statut.required' => 'Le statut NU est obligatoire.',
            'statut.in' => 'Le statut NU doit être "en règle" ou "non en règle".',
            'fidele_id.required' => 'Le fidèle est obligatoire.',
            'fidele_id.exists' => 'Le fidèle choisi n\'existe pas.',
        ];
    }
}
