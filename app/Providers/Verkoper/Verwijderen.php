<?php

namespace App\Providers\Verkoper;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Verwijderen
{
    public function uitvoeren(int $id): bool
    {
        try {
            return DB::table('Verkoper')
                ->where('Id', $id)
                ->where('Isactief', 1)
                ->update([
                    'Isactief' => 0,
                    'Datumgewijzigd' => now(),
                ]) > 0;
        } catch (\Throwable $e) {
            Log::error('Databasefout bij verwijderen van verkoper.', [
                'verkoper_id' => $id,
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
