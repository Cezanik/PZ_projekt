<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // PDF str. 17: Zmieniamy na true, jeśli ma działać (zakładamy, że middleware auth już chroni trasę)
        return true; 
    }

    public function rules(): array
    {
        return [
            'login' => 'required|unique:users,login',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,nauczyciel,uczen,rodzic',
            'imie' => 'required|string|max:255',
            'nazwisko' => 'required|string|max:255',
        ];
    }

    // Opcjonalnie: własne komunikaty (nie było w PDF, ale dobra praktyka)
    public function messages(): array
    {
        return [
            'login.unique' => 'Ten login jest już zajęty.',
            'password.min' => 'Hasło musi mieć minimum 6 znaków.',
        ];
    }
}
