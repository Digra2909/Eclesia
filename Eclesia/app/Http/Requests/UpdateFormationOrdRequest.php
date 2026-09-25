<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFormationOrdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'theme' => ['required', 'string', 'max:30'],
            'programme_id' => ['required', 'exists:programmes,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'theme.required' => 'Le thème est obligatoire.',
            'theme.max' => 'Le thème ne doit pas dépasser 30 caractères.',
            'programme_id.required' => 'Le programme est obligatoire.',
            'programme_id.exists' => 'Le programme choisi n\'existe pas.',
        ];
    }
}
