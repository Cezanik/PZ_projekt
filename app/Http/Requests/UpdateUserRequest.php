<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Zezwalamy na wykonanie requestu
    }

    public function rules(): array
    {
        // Pobieramy instancję użytkownika z trasy (np. /users/{user})
        $user = $this->route('user');

        return [
            'login'    => ['required', Rule::unique('users')->ignore($user->id)],
            'imie'     => 'required|string',
            'nazwisko' => 'required|string',
            'role'     => 'required|in:admin,nauczyciel,uczen,rodzic',
            'password' => 'nullable|min:8', // Hasło opcjonalne przy edycji
        ];
    }
}
