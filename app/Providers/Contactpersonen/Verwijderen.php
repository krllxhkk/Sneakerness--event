<?php

namespace App\Providers\Contactpersonen;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Verwijderen
{
    public function uitvoeren(int $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                $verwijderd = DB::table('Contactpersoon')
                    ->where('Id', $id)
                    ->where('Isactief', 1)
                    ->update([
                        'Isactief' => 0,
                        'Datumgewijzigd' => now(),
                    ]);

                if ($verwijderd === 0) {
                    return false;
                }

                // Deactiveer de koppeling met de verkoper
                DB::table('ContactPerVerkoper')
                    ->where('ContactpersoonId', $id)
                    ->where('Isactief', 1)
                    ->update([
                        'Isactief' => 0,
                        'Datumgewijzigd' => now(),
                    ]);

                return true;
            });
        } catch (\Throwable $e) {
            Log::error('Databasefout bij verwijderen van contactpersoon.', [
                'contactpersoon_id' => $id,
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}