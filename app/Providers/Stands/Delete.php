<?php

namespace App\Providers\Stands;

use App\Models\Stand;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class Delete
{
    // Verwijder een stand alleen als deze niet gereserveerd is.
    public function delete(int $id): RedirectResponse
    {
        // Een onbekende stand geeft een normale 404-pagina.
        Stand::findOrFail($id);

        try {
            DB::transaction(function () use ($id) {
                // Haal de stand en gekoppelde verkoper op met een JOIN.
                // Vergrendel de gegevens tijdens controleren en verwijderen.
                $stand = Stand::query()
                    ->leftJoin(
                        'Verkoper',
                        'Stand.VerkoperId',
                        '=',
                        'Verkoper.Id'
                    )
                    ->select(
                        'Stand.*',
                        'Verkoper.Naam as VerkoperNaam'
                    )
                    ->where('Stand.Id', $id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Een verhuurde of aan een verkoper gekoppelde stand blijft bestaan.
                if (
                    (bool) $stand->VerhuurdStatus
                    || $stand->VerkoperId !== null
                ) {
                    throw ValidationException::withMessages([
                        'stand' => 'Deze stand is al gereserveerd en kan niet worden verwijderd',
                    ])->errorBag('standVerwijderen');
                }

                // Voer de daadwerkelijke verwijdering uit via de procedure.
                DB::statement('CALL DeleteStand(?)', [$id]);
            });

            // Log het resultaat nadat de transactie is afgerond.
            Log::info('Stand succesvol verwijderd', [
                'stand_id' => $id,
                'gebruiker_id' => auth()->id(),
            ]);

            return redirect()
                ->route('organisator.stands')
                ->with('success', 'Stand succesvol verwijderd!');
        } catch (ValidationException $fout) {
            // Bewaar de reden waarom verwijderen niet is toegestaan.
            Log::warning('Stand verwijderen geweigerd', [
                'stand_id' => $id,
                'gebruiker_id' => auth()->id(),
                'fouten' => $fout->errors(),
            ]);

            throw $fout;
        } catch (QueryException $fout) {
            // Log technische databasegegevens zonder deze aan de gebruiker te tonen.
            Log::error('Databasefout bij stand verwijderen', [
                'stand_id' => $id,
                'gebruiker_id' => auth()->id(),
                'sqlstate' => $fout->errorInfo[0] ?? null,
                'databasecode' => $fout->errorInfo[1] ?? null,
            ]);

            // Andere tabellen kunnen nog naar deze stand verwijzen.
            $melding = (int) ($fout->errorInfo[1] ?? 0) === 1451
                ? 'Deze stand is nog gekoppeld aan andere gegevens en kan niet worden verwijderd.'
                : 'De stand kon niet worden verwijderd. Probeer het opnieuw.';

            return redirect()
                ->route('organisator.stands')
                ->withErrors([
                    'stand' => $melding,
                ], 'standVerwijderen');
        } catch (Throwable $fout) {
            // Vang onverwachte fouten op en geef een begrijpelijke melding.
            Log::error('Onverwachte fout bij stand verwijderen', [
                'stand_id' => $id,
                'gebruiker_id' => auth()->id(),
                'fouttype' => get_class($fout),
                'melding' => $fout->getMessage(),
            ]);

            return redirect()
                ->route('organisator.stands')
                ->withErrors([
                    'stand' => 'Er is iets misgegaan bij het verwijderen van de stand.',
                ], 'standVerwijderen');
        }
    }
}