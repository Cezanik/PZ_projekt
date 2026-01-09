<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreOcenaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->role === 'nauczyciel';
    }

    public function rules(): array
    {
        return [
            'uczen_id'     => 'required|exists:users,id',
            'przedmiot_id' => 'required|exists:przedmioty,id',
            'wartosc'      => 'required|numeric|between:1,6', 
            'opis'         => 'required|string|max:255',
        ];
    }
}
