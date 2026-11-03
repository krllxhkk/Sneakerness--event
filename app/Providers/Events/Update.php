<?php

namespace App\Providers\Events;

use App\Models\Event;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class Update
{
    // Controleer de bedrijfsregel en sla het evenement op via de procedure.
    public function update(int $id, array $gegevens): RedirectResponse
    {
        Event::findOrFail($id);

        try {
            DB::transaction(function () use ($id, $gegevens) {
                // Vergrendel het evenement tijdens controleren en wijzigen.
                $event = Event::where('Id', $id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Sluit het huidige evenement uit van de dubbele datum/tijd-controle.
                $bestaatAl = Event::where('Datum', $gegevens['Datum'])
                    ->where('Tijd', $gegevens['Tijd'] . ':00')
                    ->where('Id', '!=', $event->Id)
                    ->exists();

                if ($bestaatAl) {
                    throw ValidationException::withMessages([
                        'Tijd' => 'Er bestaat al een ander event op deze datum en tijd.',
                    ])->errorBag('eventWijzigen');
                }

                // Wijzig uitsluitend de vier velden uit de user story.
                DB::statement('CALL UpdateEvent(?, ?, ?, ?, ?)', [
                    $event->Id,
                    $gegevens['Naam'],
                    $gegevens['Datum'],
                    $gegevens['Tijd'] . ':00',
                    $gegevens['Locatie'],
                ]);
            });

            // Registreer de geslaagde wijziging na het afronden van de transactie.
            Log::info('Event succesvol gewijzigd', [
                'event_id' => $id,
                'gebruiker_id' => auth()->id(),
                'gewijzigde_velden' => array_keys($gegevens),
            ]);

            return redirect()
                ->route('organisator.events')
                ->with('success', 'Event succesvol gewijzigd!');
        } catch (ValidationException $fout) {
            Log::warning('Event wijzigen geweigerd', [
                'event_id' => $id,
                'gebruiker_id' => auth()->id(),
                'fouten' => $fout->errors(),
            ]);

            // Laravel bewaart de invoer en opent de wijzigpop-up opnieuw.
            throw $fout;
        } catch (QueryException $fout) {
            Log::error('Databasefout bij event wijzigen', [
                'event_id' => $id,
                'gebruiker_id' => auth()->id(),
                'sqlstate' => $fout->errorInfo[0] ?? null,
                'databasecode' => $fout->errorInfo[1] ?? null,
            ]);

            // Zet de bedrijfsfout uit de procedure om naar de voorgeschreven melding.
            if (str_contains($fout->getMessage(), 'EVENT_DATUM_TIJD_BEZET')) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'Tijd' => 'Er bestaat al een ander event op deze datum en tijd.',
                    ], 'eventWijzigen');
            }

            return back()
                ->withInput()
                ->withErrors([
                    'event' => 'Het event kon niet worden gewijzigd. Probeer het opnieuw.',
                ], 'eventWijzigen');
        } catch (Throwable $fout) {
            Log::error('Onverwachte fout bij event wijzigen', [
                'event_id' => $id,
                'gebruiker_id' => auth()->id(),
                'fouttype' => get_class($fout),
                'melding' => $fout->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'event' => 'Er is iets misgegaan bij het wijzigen van het event.',
                ], 'eventWijzigen');
        }
    }
}