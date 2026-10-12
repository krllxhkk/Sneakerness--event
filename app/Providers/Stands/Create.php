<?php

namespace App\Providers\Stands;

use App\Models\Stand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class Create
{
    public function create(array $gegevens): RedirectResponse
    {
        try {
            // Kijk of de locatie al gebruikt wordt
            $bestaatAl = Stand::where('Locatie', $gegevens['Locatie'])
                ->exists();

            if ($bestaatAl) {
                throw ValidationException::withMessages([
                    'Locatie' => 'Deze locatie is al bezet',
                ]);
            }

            // Maak de stand aan binnen een transaction
            DB::transaction(function () use ($gegevens) {

                // Eerst opslaan met tijdelijk standnummer
                $stand = Stand::create([
                    'Standnummer' => 'TEMP-' . uniqid(),
                    'Standnaam' => $gegevens['Standnaam'],
                    'Locatie' => $gegevens['Locatie'],
                    'Prijs' => $gegevens['Prijs'],
                    'Kwaliteitsklasse' => $gegevens['Kwaliteitsklasse'] ?? null,
                    'StandType' => 'STD',
                    'VerkoperId' => null,
                    'VerhuurdStatus' => 0,
                    'Isactief' => 1,
                    'Opmerking' => null,
                    'Datumaangemaakt' => now(),
                    'Datumgewijzigd' => now(),
                ]);

                // Gebruik het automatisch gegenereerde ID
                $stand->Standnummer = $stand->Id;
                $stand->save();

                // Sla op dat de stand is toegevoegd
                Log::info('Stand toegevoegd', [
                    'stand_id' => $stand->Id,
                    'standnummer' => $stand->Standnummer,
                ]);
            });

            return redirect()
                ->route('organisator.stands')
                ->with('success', 'Stand succesvol toegevoegd!');

        } catch (ValidationException $fout) {
            throw $fout;

        } catch (QueryException $fout) {
            // Controleer of MySQL een dubbele locatie heeft gevonden
            if (
                ($fout->errorInfo[1] ?? null) === 1062 &&
                str_contains($fout->getMessage(), 'UQ_Stand_Locatie')
            ) {
                throw ValidationException::withMessages([
                    'Locatie' => 'Deze locatie is al bezet',
                ]);
            }

            Log::error('Databasefout bij stand toevoegen', [
                'melding' => $fout->getMessage(),
            ]);

            return back()->withErrors([
                'stand' => 'De stand kon niet worden toegevoegd.',
            ])->withInput();

        } catch (Throwable $fout) {
            Log::error('Stand toevoegen mislukt', [
                'melding' => $fout->getMessage(),
            ]);

            return back()->withErrors([
                'stand' => 'De stand kon niet worden toegevoegd.',
            ])->withInput();
        }
    }
}
