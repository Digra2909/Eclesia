<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatutFideleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'designation' => ['required', 'string', 'max:40', 'unique:statut_fideles,designation,'.$this->route('statut_fidele')],
        ];
    }

    public function messages(): array
    {
        return [
            'designation.required' => 'La désignation du statut est obligatoire.',
            'designation.max' => 'La désignation du statut ne doit pas dépasser 40 caractères.',
            'designation.unique' => 'Ce statut existe déjà.',
        ];
    }
}
