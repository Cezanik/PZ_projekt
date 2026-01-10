<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateOcenaRequest extends FormRequest
{
    public function authorize(): bool
    {

        return Auth::user()->role === 'nauczyciel';
    }

    public function rules(): array
    {
        return [
            'wartosc'      => 'required|numeric|between:1,6',
            'opis'         => 'required|string|max:255',
            'powod_zmiany' => 'required|string|min:5|max:255', 
        ];
    }

    public function messages(): array
    {
        return [
            'powod_zmiany.required' => 'Musisz podać powód zmiany oceny.',
        ];
    }
}
