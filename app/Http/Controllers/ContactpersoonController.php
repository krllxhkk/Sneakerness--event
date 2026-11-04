<?php

namespace App\Http\Controllers;

use App\Models\Contactpersoon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Providers\Contactpersonen\Wijzigen;
use App\Providers\Contactpersonen\Verwijderen;
use App\Providers\Contactpersonen\Loskoppelen;

class ContactpersoonController extends Controller
{
    protected $wijzigen;
protected $verwijderen;
protected $loskoppelen;

public function __construct(
    Wijzigen $wijzigen,
    Verwijderen $verwijderen,
    Loskoppelen $loskoppelen
) {
    $this->wijzigen = $wijzigen;
    $this->verwijderen = $verwijderen;
    $this->loskoppelen = $loskoppelen;
}
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
            ->with('error', 'Er is een fout opgetreden door een verbindingsprobleem met de database.');
        }
    }
// Wijzig een bestaande contactpersoon
public function update(Request $request, int $id)
{
    $gegevens = $request->validate([
        'Naam' => 'required|string|max:100',
        'Telefoonnummer' => 'required|string|max:20',
        'E-mailadres' => 'required|email|max:150',
        'VerkoperId' => 'required|integer|exists:Verkoper,Id',
    ]);

    try {
        $gewijzigd = $this->wijzigen->uitvoeren($id, $gegevens);

        if (!$gewijzigd) {
            return redirect()->route('organisator.verkopers')
                ->with('error', 'De contactpersoon kon niet worden gewijzigd.');
        }

        return redirect()->route('organisator.verkopers')
            ->with('success', 'De contactpersoon is succesvol gewijzigd.');
    } catch (\Throwable $e) {
        Log::error('Databasefout bij wijzigen van contactpersoon.', [
            'contactpersoon_id' => $id,
            'message' => $e->getMessage(),
        ]);

        return redirect()->route('organisator.verkopers')
            ->withInput()
            ->with('error', 'Er is een fout opgetreden door een verbindingsprobleem met de database.');
    }
}

// Verwijder een contactpersoon
public function destroy(int $id)
{
    try {
        $verwijderd = $this->verwijderen->uitvoeren($id);

        if (!$verwijderd) {
            return redirect()->route('organisator.verkopers')
                ->with('error', 'De contactpersoon kon niet worden verwijderd.');
        }

        return redirect()->route('organisator.verkopers')
            ->with('success', 'De contactpersoon is succesvol verwijderd.');
    } catch (\Throwable $e) {
        Log::error('Databasefout bij verwijderen van contactpersoon.', [
            'contactpersoon_id' => $id,
            'message' => $e->getMessage(),
        ]);

        return redirect()->route('organisator.verkopers')
            ->with('error', 'Er is een fout opgetreden door een verbindingsprobleem met de database.');
    }
}

// Koppel een contactpersoon los van een verkoper
public function loskoppelen(int $id)
{
    try {
        $losgekoppeld = $this->loskoppelen->uitvoeren($id);

        if (!$losgekoppeld) {
            return redirect()->route('organisator.verkopers')
                ->with('error', 'De contactpersoon kon niet worden losgekoppeld.');
        }

        return redirect()->route('organisator.verkopers')
            ->with('success', 'De contactpersoon is succesvol losgekoppeld.');
    } catch (\Throwable $e) {
        Log::error('Databasefout bij loskoppelen van contactpersoon.', [
            'contactpersoon_id' => $id,
            'message' => $e->getMessage(),
        ]);

        return redirect()->route('organisator.verkopers')
            ->with('error', 'De contactpersoon kon niet worden losgekoppeld.');
    }
}
}
