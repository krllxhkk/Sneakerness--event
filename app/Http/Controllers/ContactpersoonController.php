<?php

namespace App\Http\Controllers;

use App\Models\Contactpersoon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactpersoonController extends Controller
{
    // Toon het overzicht van contactpersonen
    public function index()
    {
        $contactpersoonModel = new Contactpersoon();
        $contactpersonen = $contactpersoonModel->getContactpersonenOverzicht();

        return view('contactpersonen.index', compact('contactpersonen'));
    }

    // Sla een nieuwe contactpersoon op
    public function store(Request $request)
    {
        $gegevens = $request->validate([
            'Naam' => 'required|string|max:100',
            'Telefoonnummer' => 'required|string|max:20',
            'E-mailadres' => 'required|email|max:150',
            'VerkoperId' => 'required|integer',
            'Opmerking' => 'nullable|string|max:250',
        ]);

        try {
            $contactpersoonModel = new Contactpersoon();
            $contactpersoonModel->store($gegevens);

            return redirect()
                ->route('organisator.verkopers')
                ->with('success', 'De contactpersoon is succesvol toegevoegd.');
        } catch (\Throwable $e) {
            Log::error('Databasefout bij toevoegen van contactpersoon.', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('organisator.verkopers')
                ->withInput()
                ->with('error', 'De contactpersoon kon niet worden toegevoegd.');
        }
    }
}
