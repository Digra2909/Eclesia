<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTypeInterventionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'designation' => ['required', 'string', 'max:40', 'unique:type_interventions,designation'],
        ];
    }

    public function messages(): array
    {
        return [
            'designation.required' => 'La désignation du type d\'intervention est obligatoire.',
            'designation.max' => 'La désignation ne doit pas dépasser 40 caractères.',
            'designation.unique' => 'Ce type d\'intervention existe déjà.',
        ];
    }
}
