<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePrzedmiotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $przedmiot = $this->route('przedmiot');

        return [
            'nazwa' => ['required', Rule::unique('przedmioty')->ignore($przedmiot->id)],
        ];
    }
}
