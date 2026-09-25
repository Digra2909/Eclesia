<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePosteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'designation' => ['required', 'string', 'max:20', 'unique:postes,designation'],
        ];
    }

    public function messages(): array
    {
        return [
            'designation.required' => 'La désignation du poste est obligatoire.',
            'designation.max' => 'La désignation du poste ne doit pas dépasser 20 caractères.',
            'designation.unique' => 'Ce poste existe déjà.',
        ];
    }
}
