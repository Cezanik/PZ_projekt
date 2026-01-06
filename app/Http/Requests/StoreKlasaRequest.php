<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKlasaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nazwa' => 'required|string|unique:klasy,nazwa|max:10',
            'wychowawca_id' => 'required|exists:users,id',
        ];
    }
}
