<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCoursProgrammeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cours_id' => ['required', 'exists:cours,id'],
            'programme_id' => ['required', 'exists:programmes,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'cours_id.required' => 'Le cours est obligatoire.',
            'cours_id.exists' => 'Le cours choisi n\'existe pas.',
            'programme_id.required' => 'Le programme est obligatoire.',
            'programme_id.exists' => 'Le programme choisi n\'existe pas.',
        ];
    }
}
