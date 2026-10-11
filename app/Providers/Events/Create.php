<?php

namespace App\Providers\Events;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class Create
{
    public function create(array $gegevens)
    {
        try {
            // Controleer of er al een event is op dezelfde datum en tijd
            $bestaatAl = Event::where('Datum', $gegevens['Datum'])
                ->where('Tijd', $gegevens['Tijd'])
                ->exists();

            if ($bestaatAl) {
                throw ValidationException::withMessages([
                    'Tijd' => 'Er staat al een event gepland op deze datum en tijd',
                ]);
            }

            // Sla het nieuwe event op in de database
            DB::table('Evenement')->insert([
                'Naam' => $gegevens['Naam'],
                'Datum' => $gegevens['Datum'],
                'Tijd' => $gegevens['Tijd'],
                'Locatie' => $gegevens['Locatie'],
                'AantalTicketsPerTijdslot' => $gegevens['AantalTicketsPerTijdslot'],
                'BeschikbareStands' => $gegevens['BeschikbareStands'],
                'Isactief' => 1,
                'Opmerking' => null,
                'Datumaangemaakt' => now(),
                'Datumgewijzigd' => now(),
            ]);

            // Houd bij wie het event heeft toegevoegd
            Log::info('Event succesvol toegevoegd', [
                'gebruiker_id' => Auth::id(),
                'Naam' => $gegevens['Naam'],
                'Datum' => $gegevens['Datum'],
                'Tijd' => $gegevens['Tijd'],
            ]);

            return redirect()
                ->route('organisator.events')
                ->with('success', 'Event succesvol toegevoegd!');

        } catch (ValidationException $fout) {
            // Laat de foutmelding zien bij een dubbele datum en tijd
            throw $fout;

        } catch (Throwable $fout) {
            // Sla technische fouten op in de log
            Log::error('Fout bij toevoegen van event', [
                'melding' => $fout->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'error' => 'Er is iets misgegaan bij het opslaan van het event.',
                ]);
        }
    }
}
