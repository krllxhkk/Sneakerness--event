<?php

namespace App\Http\Controllers;

use App\Models\Verkoper;
use App\Models\Contactpersoon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class VerkoperController extends Controller
{
    // Toon het overzicht van verkopers en contactpersonen
    public function index()
    {
        $verkoperModel = new Verkoper();
        $contactpersoonModel = new Contactpersoon();

        $verkopers = collect();
        $contactpersonen = collect();

        $verkopersFout = null;
        $contactpersonenFout = null;

        // Haal verkopers op
        try {
            $verkopers = $verkoperModel->getVerkopersOverzicht();
        } catch (\Throwable $e) {
            Log::error('Databasefout bij ophalen van verkopers.', [
                'message' => $e->getMessage(),
            ]);

            $verkopersFout = 'De verkopers konden niet worden opgehaald.';
        }

        // Haal contactpersonen op
        try {
            $contactpersonen = $contactpersoonModel->getContactpersonenOverzicht();
        } catch (\Throwable $e) {
            Log::error('Databasefout bij ophalen van contactpersonen.', [
                'message' => $e->getMessage(),
            ]);

            $contactpersonenFout = 'De contactpersonen konden niet worden opgehaald.';
        }

        return view('verkopers.index', compact(
            'verkopers',
            'contactpersonen',
            'verkopersFout',
            'contactpersonenFout'
        ));
    }

    // Toon het overzicht voor de organisator
    public function organisator()
    {
        $verkoperModel = new Verkoper();
        $contactpersoonModel = new Contactpersoon();

        $verkopers = collect();
        $contactpersonen = collect();

        $verkopersFout = null;
        $contactpersonenFout = null;

        try {
            $verkopers = $verkoperModel->getVerkopersOverzicht();
        } catch (\Throwable $e) {
            Log::error('Databasefout bij ophalen van verkopers.', [
                'message' => $e->getMessage(),
            ]);

            $verkopersFout = 'De verkopers konden niet worden opgehaald.';
        }

        try {
            $contactpersonen = $contactpersoonModel->getContactpersonenOverzicht();
        } catch (\Throwable $e) {
            Log::error('Databasefout bij ophalen van contactpersonen.', [
                'message' => $e->getMessage(),
            ]);

            $contactpersonenFout = 'De contactpersonen konden niet worden opgehaald.';
        }

        return view('organisator.verkoper', compact(
            'verkopers',
            'contactpersonen',
            'verkopersFout',
            'contactpersonenFout'
        ));
    }

    // Sla een nieuwe verkoper op
    public function store(Request $request)
    {
        $gegevens = $request->validate([
            'Naam' => 'required|string|max:100',
            'SpecialeStatus' => 'nullable|string|max:50',
            'VerkooptSoort' => 'required|string|max:100',
            'StandType' => 'required|string|max:10',
            'Dagen' => 'required|string|max:50',
        ]);

        try {
            $verkoperModel = new Verkoper();
            $verkoperModel->store($gegevens);

            return redirect()
                ->route('organisator.verkopers')
                ->with('success', 'De verkoper is succesvol toegevoegd.');
        } catch (\Throwable $e) {
            // Log de fout en toon een melding aan de gebruiker
            Log::error('Databasefout bij toevoegen van verkoper.', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('organisator.verkopers')
                ->withInput()
                ->with('error', 'De verkoper kon niet worden opgeslagen door geen verbinding met de database.');
        }
    }
}
