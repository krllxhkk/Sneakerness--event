<?php

namespace App\Providers\Contactpersonen;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Loskoppelen
{
    public function uitvoeren(int $id): bool
    {
        try {
            return DB::table('ContactPerVerkoper')
                ->where('ContactpersoonId', $id)
                ->where('Isactief', 1)
                ->update([
                    'Isactief' => 0,
                    'Datumgewijzigd' => now(),
                ]) > 0;

        } catch (\Throwable $e) {
            Log::error('Databasefout bij loskoppelen van contactpersoon.', [
                'contactpersoon_id' => $id,
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
