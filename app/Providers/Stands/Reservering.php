<?php

namespace App\Providers\Stands;

use App\Models\Stand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class Reservering
{
    public function reserveren(Request $request, int $stand)
    {
        // Alleen een ingelogde verkoper mag reserveren
        $gebruiker = $request->user();

        if (!$gebruiker || $gebruiker->rol !== 'verkoper') {
            abort(403, 'Alleen verkopers mogen reserveren.');
        }

        // JOIN: zoek het bedrijf van de ingelogde verkoper
        $verkoper = DB::table('verkoper as v')
            ->join('users as u', 'v.UserId', '=', 'u.id')
            ->where('u.id', $gebruiker->id)
            ->select('v.Id', 'v.Naam')
            ->first();

        if (!$verkoper) {
            return redirect()->route('stands.index')
                ->withErrors([
                    'stand' => 'Geen verkoper gekoppeld aan dit account.',
                ]);
        }

        try {
            return DB::transaction(function () use ($request, $stand, $verkoper, $gebruiker) {

                // Vergrendel de stand om dubbele reserveringen te voorkomen
                $gekozenStand = Stand::where('Id', $stand)
                    ->lockForUpdate()
                    ->first();

                if (!$gekozenStand) {
                    return redirect()->route('stands.index')
                        ->withErrors([
                            'stand' => 'Deze stand bestaat niet.',
                        ]);
                }

                // Stored procedure controleert de reserveringsstatus
                $status = DB::select(
                    'CALL sp_stand_reservering_status(?)',
                    [$stand]
                );

                // Unhappy Scenario: stand is al gereserveerd
                if (!$status || (int) $status[0]->status === 1) {
                    Log::warning('Dubbele standreservering geweigerd', [
                        'stand_id' => $stand,
                        'user_id' => $gebruiker->id,
                    ]);

                    return redirect()->route('stands.index')
                        ->withErrors([
                            'stand' => 'Deze stand is al gereserveerd',
                        ]);
                }

                // Valideer pas na de statuscontrole
                $gegevens = Validator::make($request->all(), [
                    'Naam' => 'required|string|max:100',
                    'Email' => 'required|email|max:150',
                    'Telefoonnummer' => 'required|string|max:20',
                ])->validate();

                // Contactpersoon opslaan
                $contactpersoonId = DB::table('contactpersoon')
                    ->insertGetId([
                        'Naam' => $gegevens['Naam'],
                        'E-mailadres' => $gegevens['Email'],
                        'Telefoonnummer' => $gegevens['Telefoonnummer'],
                        'Isactief' => 1,
                        'Datumaangemaakt' => now(),
                        'Datumgewijzigd' => now(),
                    ]);

                // Contactpersoon koppelen aan de verkoper
                DB::table('contactperverkoper')->insert([
                    'VerkoperId' => $verkoper->Id,
                    'ContactpersoonId' => $contactpersoonId,
                    'Isactief' => 1,
                    'Datumaangemaakt' => now(),
                    'Datumgewijzigd' => now(),
                ]);

                // Stand koppelen aan de verkoper
                $gekozenStand->VerkoperId = $verkoper->Id;
                $gekozenStand->VerhuurdStatus = 1;
                $gekozenStand->Datumgewijzigd = now();
                $gekozenStand->save();

                // Technische log voor een succesvolle reservering
                Log::info('Stand succesvol gereserveerd', [
                    'stand_id' => $stand,
                    'verkoper_id' => $verkoper->Id,
                    'user_id' => $gebruiker->id,
                ]);

                return redirect()->route('stands.index')
                    ->with('success', 'Stand succesvol gereserveerd');
            }, 3);
        } catch (\Illuminate\Validation\ValidationException $fout) {
            throw $fout;
        } catch (Throwable $fout) {
            // Bewaar de fout voor de ontwikkelaar
            Log::error('Fout bij standreservering', [
                'stand_id' => $stand,
                'user_id' => $gebruiker->id,
                'fout' => $fout->getMessage(),
            ]);

            // Toon een duidelijke melding aan de gebruiker
            return redirect()->route('stands.index')
                ->withErrors([
                    'stand' => 'Reserveren is mislukt. Probeer het opnieuw.',
                ]);
        }
    }
}
