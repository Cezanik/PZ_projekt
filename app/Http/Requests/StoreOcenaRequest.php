<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOcenaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'uczen_id' => 'required|exists:users,id',
            'przedmiot_id' => 'required|exists:przedmioty,id',
            'wartosc' => 'required|numeric|between:1,6',
            'opis' => 'nullable|string|max:255',
        ];
    }
}
