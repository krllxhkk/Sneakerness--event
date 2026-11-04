<?php

namespace App\Http\Controllers;

use App\Models\Verkoper;
use App\Models\Contactpersoon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Providers\Verkoper\Wijzigen;
use App\Providers\Verkoper\Verwijderen;

class VerkoperController extends Controller
{
    protected $wijzigen;
protected $verwijderen;

public function __construct(Wijzigen $wijzigen, Verwijderen $verwijderen)
{
    $this->wijzigen = $wijzigen;
    $this->verwijderen = $verwijderen;
}
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

    // Alle actieve contactpersonen ophalen voor het koppelen
    $alleContactpersonen = collect();

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

        // Haal alle actieve contactpersonen op, ook zonder actieve koppeling
        $alleContactpersonen = $contactpersoonModel->getAlleActieveContactpersonen();

    } catch (\Throwable $e) {
        Log::error('Databasefout bij ophalen van contactpersonen.', [
            'message' => $e->getMessage(),
        ]);

        $contactpersonenFout = 'De contactpersonen konden niet worden opgehaald.';
    }

    return view('organisator.verkopers.verkoper', compact(
        'verkopers',
        'contactpersonen',
        'alleContactpersonen',
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
    // Wijzig een bestaande verkoper.
public function update(Request $request, int $id)
{
    $gegevens = $request->validate([
        'Naam' => 'required|string|max:100',
        'SpecialeStatus' => 'nullable|string|max:50',
        'VerkooptSoort' => 'required|string|max:100',
        'StandType' => 'required|string|max:10',
        'Dagen' => 'required|string|max:50',
    ]);

    try {
        $gewijzigd = $this->wijzigen->uitvoeren($id, $gegevens);

        if (!$gewijzigd) {
            return redirect()
                ->route('organisator.verkopers')
                ->with('error', 'De verkoper kon niet worden gewijzigd.');
        }

        return redirect()
            ->route('organisator.verkopers')
            ->with('success', 'De verkoper is succesvol gewijzigd.');
    } catch (\Throwable $e) {
        Log::error('Databasefout bij wijzigen van verkoper.', [
            'verkoper_id' => $id,
            'message' => $e->getMessage(),
        ]);

        return redirect()
            ->route('organisator.verkopers')
            ->withInput()
            ->with('error', 'De verkoper kon niet worden gewijzigd door geen verbinding met de database.');
    }
}

// Verwijder een verkoper.
public function destroy(int $id)
{
    try {
        $verwijderd = $this->verwijderen->uitvoeren($id);

        if (!$verwijderd) {
            return redirect()
                ->route('organisator.verkopers')
                ->with('error', 'De verkoper kon niet worden verwijderd.');
        }

        return redirect()
            ->route('organisator.verkopers')
            ->with('success', 'De verkoper is succesvol verwijderd.');
    } catch (\Throwable $e) {
        Log::error('Databasefout bij verwijderen van verkoper.', [
            'verkoper_id' => $id,
            'message' => $e->getMessage(),
        ]);

        return redirect()
            ->route('organisator.verkopers')
            ->with('error', 'De verkoper kon niet worden verwijderd door geen verbinding met de database.');
    }
}
}
