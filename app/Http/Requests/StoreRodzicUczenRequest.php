<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRodzicUczenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rodzic_id' => 'required|exists:users,id',
            'uczen_id' => [
                'required',
                'exists:users,id',
                // Sprawdzamy czy ten uczeń nie jest już przypisany do tego konkretnego rodzica
                Rule::unique('rodzic_uczen', 'uczen_id')->where(function ($query) {
                    return $query->where('rodzic_id', $this->rodzic_id);
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'uczen_id.unique' => 'Ten uczeń jest już przypisany do wybranego rodzica.',
        ];
    }
}
