<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrzedmiotRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nazwa' => 'required|string|unique:przedmioty,nazwa|max:255',
        ];
    }
}
