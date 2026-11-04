<?php

namespace App\Providers\Verkoper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Wijzigen
{
    public function uitvoeren(int $id, array $gegevens): bool
    {
        try {
            return DB::table('Verkoper')
                ->where('Id', $id)
                ->where('Isactief', 1)
                ->update([
                    'Naam' => $gegevens['Naam'],
                    'SpecialeStatus' => $gegevens['SpecialeStatus'] ?? null,
                    'VerkooptSoort' => $gegevens['VerkooptSoort'],
                    'StandType' => $gegevens['StandType'],
                    'Dagen' => $gegevens['Dagen'],
                    'Datumgewijzigd' => now(),
                ]) > 0;
        } catch (\Throwable $e) {
            Log::error('Databasefout bij wijzigen van verkoper.', [
                'verkoper_id' => $id,
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
