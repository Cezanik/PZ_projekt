<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKlasaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $klasa = $this->route('klasa');

        return [
            'nazwa'         => ['required', Rule::unique('klasy')->ignore($klasa->id)],
            'wychowawca_id' => 'required|exists:users,id',
        ];
    }
}
