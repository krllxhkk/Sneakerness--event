<?php

namespace App\Providers\Stands;

use App\Models\Stand;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class Update
{
    // Controleer de bedrijfsregels en wijzig de stand via een stored procedure.
    public function update(int $id, array $gegevens): RedirectResponse
    {
        // Een onbekende stand geeft een normale 404-pagina.
        Stand::findOrFail($id);

        try {
            DB::transaction(function () use ($id, $gegevens) {
                // Vergrendel de stand tijdens controleren en opslaan.
                $stand = Stand::where('Id', $id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $oudePrijs = (int) round((float) $stand->Prijs * 100);
                $nieuwePrijs = (int) round((float) $gegevens['Prijs'] * 100);

                $isVerhuurd = (bool) $stand->VerhuurdStatus
                    || (bool) $gegevens['VerhuurdStatus'];

                // Een verhuurde stand mag geen andere prijs krijgen.
                if ($isVerhuurd && $oudePrijs !== $nieuwePrijs) {
                    throw ValidationException::withMessages([
                        'Prijs' => 'Stand is Al Verhuurd Wijzigen van prijs niet meer mogelijk',
                    ])->errorBag('standWijzigen');
                }

                // Een verhuurde stand moet aan een verkoper gekoppeld zijn.
                if (
                    (bool) $gegevens['VerhuurdStatus']
                    && empty($gegevens['VerkoperId'])
                ) {
                    throw ValidationException::withMessages([
                        'VerkoperId' => 'Selecteer een verkoper voor deze verhuurde stand.',
                    ])->errorBag('standWijzigen');
                }

                // Andere stands mogen dezelfde locatie niet gebruiken.
                $locatieBezet = Stand::where('Locatie', $gegevens['Locatie'])
                    ->where('Id', '!=', $stand->Id)
                    ->exists();

                if ($locatieBezet) {
                    throw ValidationException::withMessages([
                        'Locatie' => 'Deze locatie is al bezet.',
                    ])->errorBag('standWijzigen');
                }

                // Parameters worden veilig afzonderlijk aan de procedure meegegeven.
                DB::statement(
                    'CALL UpdateStand(?, ?, ?, ?, ?, ?, ?)',
                    [
                        $stand->Id,
                        $gegevens['Standnaam'],
                        $gegevens['Locatie'],
                        $gegevens['Prijs'],
                        $gegevens['Kwaliteitsklasse'] ?? null,
                        (int) $gegevens['VerhuurdStatus'],
                        $gegevens['VerkoperId'] ?? null,
                    ]
                );
            });

            // Log succes nadat de transactie daadwerkelijk is afgerond.
            Log::info('Stand succesvol gewijzigd', [
                'stand_id' => $id,
                'gebruiker_id' => auth()->id(),
                'gewijzigde_velden' => array_keys($gegevens),
            ]);

            return redirect()
                ->route('organisator.stands')
                ->with('success', 'Stand succesvol gewijzigd!');
        } catch (ValidationException $fout) {
            // Laravel bewaart de invoer en toont de melding in de wijzigpop-up.
            Log::warning('Stand wijzigen geweigerd door bedrijfsregel', [
                'stand_id' => $id,
                'gebruiker_id' => auth()->id(),
                'fouten' => $fout->errors(),
            ]);

            throw $fout;
        } catch (QueryException $fout) {
            // Bewaar technische databasegegevens uitsluitend in de log.
            Log::error('Databasefout bij stand wijzigen', [
                'stand_id' => $id,
                'gebruiker_id' => auth()->id(),
                'sqlstate' => $fout->errorInfo[0] ?? null,
                'databasecode' => $fout->errorInfo[1] ?? null,
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'stand' => 'De stand kon niet worden gewijzigd. Probeer het opnieuw.',
                ], 'standWijzigen');
        } catch (Throwable $fout) {
            // Vang overige technische fouten op.
            Log::error('Onverwachte fout bij stand wijzigen', [
                'stand_id' => $id,
                'gebruiker_id' => auth()->id(),
                'fouttype' => get_class($fout),
                'melding' => $fout->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'stand' => 'Er is iets misgegaan bij het wijzigen van de stand.',
                ], 'standWijzigen');
        }
    }
}