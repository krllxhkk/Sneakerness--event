<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    protected $errorBag = 'eventWijzigen';

    // De route controleert de organisatorrol.
    public function authorize(): bool
    {
        return true;
    }

    // Controleer de invoer voor het wijzigen van een evenement.
    public function rules(): array
    {
        return [
            'Naam' => ['required', 'string', 'max:100'],
            'Datum' => ['required', 'date_format:Y-m-d'],
            'Tijd' => ['required', 'date_format:H:i'],
            'Locatie' => ['required', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'Naam.required' => 'Vul een naam in.',
            'Naam.max' => 'De naam mag maximaal 100 tekens bevatten.',
            'Datum.required' => 'Vul een datum in.',
            'Datum.date_format' => 'Vul een geldige datum in.',
            'Tijd.required' => 'Vul een tijd in.',
            'Tijd.date_format' => 'Vul een geldige tijd in.',
            'Locatie.required' => 'Vul een locatie in.',
            'Locatie.max' => 'De locatie mag maximaal 150 tekens bevatten.',
        ];
    }
}