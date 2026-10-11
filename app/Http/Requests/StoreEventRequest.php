<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    // Alleen de organisator mag een event toevoegen
    public function authorize(): bool
    {
        return $this->user() !== null
            && $this->user()->email === 'organisator@sneakerness.nl';
    }

    // Controleer of alle gegevens goed zijn ingevuld
    public function rules(): array
    {
        return [
            'Naam' => ['required', 'string', 'max:100'],
            'Datum' => ['required', 'date', 'after_or_equal:today'],
            'Tijd' => ['required', 'date_format:H:i'],
            'Locatie' => ['required', 'string', 'max:150'],
            'AantalTicketsPerTijdslot' => ['required', 'integer', 'min:0'],
            'BeschikbareStands' => ['required', 'integer', 'min:0'],
        ];
    }

    // Foutmeldingen voor het formulier
    public function messages(): array
    {
        return [
            'Naam.required' => 'Vul een naam in.',
            'Datum.required' => 'Vul een datum in.',
            'Datum.after_or_equal' => 'De datum mag niet in het verleden liggen.',
            'Tijd.required' => 'Vul een tijd in.',
            'Tijd.date_format' => 'Vul een geldige tijd in.',
            'Locatie.required' => 'Vul een locatie in.',
            'AantalTicketsPerTijdslot.required' => 'Vul het aantal tickets per tijdslot in.',
            'AantalTicketsPerTijdslot.integer' => 'Vul een geldig aantal tickets in.',
            'AantalTicketsPerTijdslot.min' => 'Het aantal tickets mag niet negatief zijn.',
            'BeschikbareStands.required' => 'Vul het aantal beschikbare stands in.',
            'BeschikbareStands.integer' => 'Vul een geldig aantal stands in.',
            'BeschikbareStands.min' => 'Het aantal stands mag niet negatief zijn.',
        ];
    }
}
