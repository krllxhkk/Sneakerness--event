<?php
namespace App\Providers\Contactpersonen;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Wijzigen
{
    public function uitvoeren(int $id, array $gegevens): bool
    {
        try {
            return DB::transaction(function () use ($id, $gegevens) {
                $gewijzigd = DB::table('Contactpersoon')
                    ->where('Id', $id)
                    ->where('Isactief', 1)
                    ->update([
                        'Naam' => $gegevens['Naam'],
                        'Telefoonnummer' => $gegevens['Telefoonnummer'],
                        'E-mailadres' => $gegevens['E-mailadres'],
                        'Datumgewijzigd' => now(),
                    ]);

                if ($gewijzigd === 0) {
                    return false;
                }

                // Wijzig de gekoppelde verkoper
                $koppeling = DB::table('ContactPerVerkoper')
                    ->where('ContactpersoonId', $id)
                    ->where('Isactief', 1)
                    ->update([
                        'VerkoperId' => $gegevens['VerkoperId'],
                        'Datumgewijzigd' => now(),
                    ]);

                return true;
            });
        } catch (\Throwable $e) {
            Log::error('Databasefout bij wijzigen van contactpersoon.', [
                'contactpersoon_id' => $id,
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}