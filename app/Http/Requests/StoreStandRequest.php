<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStandRequest extends FormRequest
{
    // Controleer of het formulier gebruikt mag worden
    public function authorize(): bool
    {
        return true;
    }

    // Controleer of alle velden goed zijn ingevuld
    public function rules(): array
    {
        return [
            'Standnummer' => ['required', 'string', 'max:20'],
            'Standnaam' => ['required', 'string', 'max:100'],
            'Locatie' => ['required', 'string', 'max:100'],
            'Prijs' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'Kwaliteitsklasse' => ['nullable', 'string', 'max:30'],
        ];
    }

    // Foutmeldingen die bij de velden worden getoond
    public function messages(): array
    {
        return [
            'Standnummer.required' => 'Vul een standnummer in.',
            'Standnaam.required' => 'Vul een standnaam in.',
            'Locatie.required' => 'Vul een locatie in.',
            'Prijs.required' => 'Vul een prijs in.',
            'Prijs.numeric' => 'Vul een geldige prijs in.',
            'Prijs.min' => 'De prijs mag niet negatief zijn.',
            'Prijs.max' => 'De prijs is te hoog.',
        ];
    }
}
