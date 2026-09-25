<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'passages' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'passages.required' => 'Les passages sont obligatoires.',
            'passages.max' => 'Les passages ne doivent pas dépasser 100 caractères.',
        ];
    }
}
