<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class EventController extends Controller
{
    // Haal alle evenementen op voor het overzicht.
    public function index()
    {
        $events = Event::orderBy('Datum')
            ->orderBy('Tijd')
            ->get();

        return view('events.index', compact('events'));
    }

    // Voeg een nieuw evenement toe.
    public function store(Request $request)
    {
        // Controleer eerst of de gebruiker toestemming heeft.
        if (
            !Auth::check() ||
            Auth::user()->email !== 'organisator@sneakerness.nl'
        ) {
            abort(403, 'Je hebt geen toestemming om evenementen toe te voegen.');
        }

        // Controleer of alle velden goed zijn ingevuld.
        $validated = $request->validate([
            'Naam' => 'required|string|max:100',
            'Datum' => 'required|date|after_or_equal:today',
            'Tijd' => 'required|date_format:H:i',
            'Locatie' => 'required|string|max:150',
            'AantalTicketsPerTijdslot' => 'required|integer|min:0',
            'BeschikbareStands' => 'required|integer|min:0',
        ], [
            'Datum.after_or_equal' => 'De datum mag niet in het verleden liggen.',
            'Tijd.required' => 'Vul een tijd in.',
            'AantalTicketsPerTijdslot.required' => 'Vul het aantal tickets per tijdslot in.',
            'BeschikbareStands.required' => 'Vul het aantal beschikbare stands in.',
        ]);

        try {
            // Kijk of er al een evenement is op dezelfde datum en tijd.
            $bestaatAl = Event::where('Datum', $validated['Datum'])
                ->where('Tijd', $validated['Tijd'])
                ->exists();

            // Als er al een event bestaat, geef een foutmelding.
            if ($bestaatAl) {
                throw ValidationException::withMessages([
                    'Tijd' => 'Er staat al een event gepland op deze datum en tijd',
                ]);
            }

            // Sla het nieuwe evenement op in de database.
            DB::table('Evenement')->insert([
                'Naam' => $validated['Naam'],
                'Datum' => $validated['Datum'],
                'Tijd' => $validated['Tijd'],
                'Locatie' => $validated['Locatie'],
                'AantalTicketsPerTijdslot' => $validated['AantalTicketsPerTijdslot'],
                'BeschikbareStands' => $validated['BeschikbareStands'],
                'Isactief' => 1,
                'Opmerking' => null,
                'Datumaangemaakt' => Carbon::now(),
                'Datumgewijzigd' => Carbon::now(),
            ]);

            // Houd bij welke gebruiker het evenement heeft toegevoegd.
            Log::info('Event succesvol toegevoegd', [
                'gebruiker_id' => Auth::id(),
                'Naam' => $validated['Naam'],
                'Datum' => $validated['Datum'],
                'Tijd' => $validated['Tijd'],
                'AantalTicketsPerTijdslot' => $validated['AantalTicketsPerTijdslot'],
                'BeschikbareStands' => $validated['BeschikbareStands'],
            ]);

            // Ga terug naar het overzicht met een succesmelding.
            return redirect()
                ->route('events.index')
                ->with('success', 'Event succesvol toegevoegd!');

        } catch (ValidationException $exception) {

            // Laat de foutmelding zien bij een dubbele datum en tijd.
            throw $exception;

        } catch (Throwable $exception) {

            // Sla technische fouten op in de Laravel log.
            Log::error('Fout bij toevoegen van event', [
                'error' => $exception->getMessage(),
            ]);

            // Ga terug met een foutmelding en behoud de ingevulde gegevens.
            return back()
                ->withInput()
                ->withErrors([
                    'error' => 'Er is iets misgegaan bij het opslaan van het event.',
                ]);
        }
    }
}
