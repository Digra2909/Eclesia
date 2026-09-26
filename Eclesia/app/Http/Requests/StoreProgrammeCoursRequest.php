<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgrammeCoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cours_ids' => ['nullable', 'array'],
            'cours_ids.*' => ['integer', 'exists:cours,id'],
            'nouveau_passages' => ['nullable', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'cours_ids.*.exists' => 'Un des cours choisis n\'existe plus.',
            'nouveau_passages.max' => 'Le thème ou les versets ne doivent pas dépasser 150 caractères.',
        ];
    }
}
