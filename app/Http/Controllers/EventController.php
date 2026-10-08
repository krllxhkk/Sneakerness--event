<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class EventController extends Controller
{
    // Haalt alle evenementen op voor het overzicht.
    public function index()
    {
        $events = Event::orderBy('Datum')
            ->orderBy('Tijd')
            ->get();

        return view('events.index', compact('events'));
    }

    // Controleert de invoer en slaat een nieuw evenement op.
    public function store(Request $request)
    {
        // Controleer alle verplichte velden.
        $validated = $request->validate([
            'Naam' => 'required|string|max:100',
            'Datum' => 'required|date|after_or_equal:today',
            'Tijd' => 'required|date_format:H:i',
            'Locatie' => 'required|string|max:150',
        ], [
            'Datum.after_or_equal' => 'De datum mag niet in het verleden liggen.',
            'Tijd.required' => 'Vul een tijd in.',
        ]);

        try {
            // Controleer of datum en tijd al bezet zijn.
            $bestaatAl = Event::where('Datum', $validated['Datum'])
                ->where('Tijd', $validated['Tijd'])
                ->exists();

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
                'AantalTicketsPerTijdslot' => 0,
                'BeschikbareStands' => 0,
                'Isactief' => 1,
                'Opmerking' => null,
                'Datumaangemaakt' => Carbon::now(),
                'Datumgewijzigd' => Carbon::now(),
            ]);

            Log::info('Event succesvol toegevoegd', [
                'Naam' => $validated['Naam'],
                'Datum' => $validated['Datum'],
                'Tijd' => $validated['Tijd'],
            ]);

            return redirect()
                ->route('events.index')
                ->with('success', 'Event succesvol toegevoegd!');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            // Registreer technische fouten in Laravel.
            Log::error('Fout bij toevoegen van event', [
                'error' => $exception->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'error' => 'Er is iets misgegaan bij het opslaan van het event.',
                ]);
        }
    }
}