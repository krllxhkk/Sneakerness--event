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
                ->with('error', 'De verkoper kon niet worden opgeslagen door geen verbinding met de database.');
        }
    }

    // Koppel een contactpersoon aan een verkoper
    public function koppel(Request $request)
    {
        $gegevens = $request->validate([
            'ContactpersoonId' => 'required|integer|exists:Contactpersoon,Id',
            'VerkoperId' => 'required|integer|exists:Verkoper,Id',
        ]);

        try {
            $contactpersoonModel = new Contactpersoon();

            $resultaat = $contactpersoonModel->koppelAanVerkoper(
                (int) $gegevens['ContactpersoonId'],
                (int) $gegevens['VerkoperId']
            );

            if (!$resultaat) {
                return redirect()
                    ->route('organisator.verkopers')
                    ->with('error', 'Deze contactpersoon is al gekoppeld aan deze verkoper.');
            }

            return redirect()
                ->route('organisator.verkopers')
                ->with('success', 'De contactpersoon is succesvol gekoppeld aan de verkoper.');
        } catch (\Throwable $e) {
            Log::error('Databasefout bij koppelen van contactpersoon aan verkoper.', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('organisator.verkopers')
            ->with('error', 'De verkoper kon niet worden opgeslagen door geen verbinding met de database.');
        }
    }

}
