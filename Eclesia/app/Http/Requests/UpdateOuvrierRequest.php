<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOuvrierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code_ouvrier' => ['required', 'string', 'max:15', 'unique:ouvriers,code_ouvrier,'.$this->route('ouvrier')->id],
            'poste_id' => ['nullable', 'exists:postes,id'],
            'fidele_id' => ['required', 'exists:fideles,id'],
            'path_photo' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'code_ouvrier.required' => 'Le code ouvrier est obligatoire.',
            'code_ouvrier.unique' => 'Ce code ouvrier existe déjà.',
            'code_ouvrier.max' => 'Le code ouvrier ne doit pas dépasser 15 caractères.',
            'poste_id.exists' => 'Le poste choisi n\'existe pas.',
            'fidele_id.required' => 'Le fidèle est obligatoire.',
            'fidele_id.exists' => 'Le fidèle choisi n\'existe pas.',
        ];
    }
}
