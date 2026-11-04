<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteStandRequest extends FormRequest
{
    protected $errorBag = 'standVerwijderen';

    // De route controleert de organisatorrol.
    public function authorize(): bool
    {
        return true;
    }

    // Verwijderen vereist een bevestiging.
    public function rules(): array
    {
        return [
            'bevestiging' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'bevestiging.required' => 'Bevestig eerst het verwijderen.',
            'bevestiging.accepted' => 'Bevestig eerst het verwijderen.',
        ];
    }
}