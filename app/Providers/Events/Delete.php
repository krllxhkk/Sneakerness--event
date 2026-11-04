<?php

namespace App\Providers\Events;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class Delete
{
    // Verwijder een event alleen wanneer het niet vandaag plaatsvindt.
    public function delete(int $id): RedirectResponse
    {
        Event::findOrFail($id);

        try {
            DB::transaction(function () use ($id) {
                // Vergrendel het event tijdens controleren en verwijderen.
                $event = Event::where('Id', $id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Gebruik voor deze regel de Nederlandse kalenderdatum.
                $vandaag = Carbon::now('Europe/Amsterdam')->toDateString();
                $eventDatum = Carbon::parse($event->Datum)->toDateString();

                if ($eventDatum === $vandaag) {
                    throw ValidationException::withMessages([
                        'event' => 'Dit event vindt vandaag plaats en kan niet worden verwijderd.',
                    ])->errorBag('eventVerwijderen');
                }

                // De procedure controleert de datum opnieuw en verwijdert de rij.
                DB::statement('CALL DeleteEvent(?, ?)', [
                    $event->Id,
                    $vandaag,
                ]);
            });

            // Log succes nadat de verwijdering definitief is opgeslagen.
            Log::info('Event succesvol verwijderd', [
                'event_id' => $id,
                'gebruiker_id' => Auth::id(),
            ]);

            return redirect()
                ->route('organisator.events')
                ->with('success', 'Event succesvol verwijderd!');
        } catch (ValidationException $fout) {
            Log::warning('Event verwijderen geweigerd', [
                'event_id' => $id,
                'gebruiker_id' => Auth::id(),
                'fouten' => $fout->errors(),
            ]);

            throw $fout;
        } catch (QueryException $fout) {
            // Technische details komen in de log, niet op het scherm.
            Log::error('Databasefout bij event verwijderen', [
                'event_id' => $id,
                'gebruiker_id' => Auth::id(),
                'sqlstate' => $fout->errorInfo[0] ?? null,
                'databasecode' => $fout->errorInfo[1] ?? null,
                'melding' => $fout->getMessage(),
            ]);

            $melding = 'Het event kon niet worden verwijderd. Probeer het opnieuw.';

            if (str_contains($fout->getMessage(), 'EVENT_VANDAAG')) {
                $melding = 'Dit event vindt vandaag plaats en kan niet worden verwijderd.';
            } elseif ((int) ($fout->errorInfo[1] ?? 0) === 1451) {
                $melding = 'Dit event is nog gekoppeld aan andere gegevens en kan niet worden verwijderd.';
            }

            return redirect()
                ->route('organisator.events')
                ->withErrors([
                    'event' => $melding,
                ], 'eventVerwijderen');
        } catch (Throwable $fout) {
            Log::error('Onverwachte fout bij event verwijderen', [
                'event_id' => $id,
                'gebruiker_id' => Auth::id(),
                'melding' => $fout->getMessage(),
            ]);

            return redirect()
                ->route('organisator.events')
                ->withErrors([
                    'event' => 'Er is iets misgegaan bij het verwijderen van het event.',
                ], 'eventVerwijderen');
        }
    }
}