<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUczenKlasaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'klasa_id' => 'required|exists:klasy,id',
            'uczniowie_ids' => 'required|array',
            'uczniowie_ids.*' => 'exists:users,id', 
        ];
    }

    public function messages(): array
    {
        return [
            'uczniowie_ids.required' => 'Wybierz przynajmniej jednego ucznia.',
            'klasa_id.required' => 'Wybierz klasę.',
        ];
    }
}
