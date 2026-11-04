<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStandRequest extends FormRequest
{
    protected $errorBag = 'standWijzigen';

    // De route controleert de organisatorrol.
    public function authorize(): bool
    {
        return true;
    }

    // Controleer de invoer voor het wijzigen van een stand.
    public function rules(): array
    {
        return [
            'Standnaam' => ['required', 'string', 'max:100'],
            'Locatie' => ['required', 'string', 'max:100'],
            'Prijs' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'Kwaliteitsklasse' => ['nullable', 'string', 'max:30'],
            'VerhuurdStatus' => ['required', 'boolean'],
            'VerkoperId' => ['nullable', 'integer', 'exists:Verkoper,Id'],
        ];
    }

    public function messages(): array
    {
        return [
            'Standnaam.required' => 'Vul een standnaam in.',
            'Locatie.required' => 'Vul een locatie in.',
            'Prijs.required' => 'Vul een prijs in.',
            'Prijs.numeric' => 'Vul een geldige prijs in.',
            'Prijs.min' => 'De prijs mag niet negatief zijn.',
            'Prijs.max' => 'De prijs is te hoog.',
            'VerhuurdStatus.required' => 'Selecteer een status.',
            'VerkoperId.exists' => 'Selecteer een bestaande verkoper.',
        ];
    }
}