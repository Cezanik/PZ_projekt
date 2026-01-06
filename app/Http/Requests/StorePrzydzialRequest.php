<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrzydzialRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'klasa_id' => 'required|exists:klasy,id',
            'przedmiot_id' => 'required|exists:przedmioty,id',
            'nauczyciel_id' => 'required|exists:users,id',
        ];
    }
}
