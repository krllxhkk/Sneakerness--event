<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Contactpersoon extends Model
{
    // Gebruik de juiste tabel
    protected $table = 'Contactpersoon';

    // Gebruik Id als primary key
    protected $primaryKey = 'Id';

    // Gebruik geen standaard timestamps
    public $timestamps = false;

    // Haal alle actieve contactpersonen op
   public function getContactpersonenOverzicht()
{
    return DB::table('Contactpersoon')
        ->leftJoin('ContactPerVerkoper', function ($join) {
            $join->on('Contactpersoon.Id', '=', 'ContactPerVerkoper.ContactpersoonId')
                ->where('ContactPerVerkoper.Isactief', 1);
        })
        ->leftJoin('Verkoper', 'ContactPerVerkoper.VerkoperId', '=', 'Verkoper.Id')
        ->where('Contactpersoon.Isactief', 1)
        ->select(
            'Contactpersoon.*',
            'Verkoper.Naam as VerkoperNaam',
            'ContactPerVerkoper.VerkoperId as VerkoperId'
        )
        ->get();
}
    // Voeg een nieuwe contactpersoon toe
    public function store(array $gegevens)
    {
        return DB::transaction(function () use ($gegevens) {
            $nu = now();

            // Voeg de contactpersoon toe
            $contactpersoonId = DB::table('Contactpersoon')->insertGetId([
                'Naam' => $gegevens['Naam'],
                'Telefoonnummer' => $gegevens['Telefoonnummer'],
                'E-mailadres' => $gegevens['E-mailadres'],
                'Isactief' => 1,
                'Opmerking' => $gegevens['Opmerking'] ?? null,
                'DatumAangemaakt' => $nu,
                'Datumgewijzigd' => $nu,
            ]);

            // Koppel de contactpersoon aan de verkoper
            DB::table('ContactPerVerkoper')->insert([
                'VerkoperId' => $gegevens['VerkoperId'],
                'ContactpersoonId' => $contactpersoonId,
                'Isactief' => 1,
                'Opmerking' => $gegevens['Opmerking'] ?? null,
                'DatumAangemaakt' => $nu,
                'Datumgewijzigd' => $nu,
            ]);

            return $contactpersoonId;
        });
    }

    
// Koppel een bestaande contactpersoon aan een verkoper
public function koppelAanVerkoper(int $contactpersoonId, int $verkoperId)
{
    // Controleer of er al een actieve koppeling bestaat
    $koppeling = DB::table('ContactPerVerkoper')
        ->where('ContactpersoonId', $contactpersoonId)
        ->where('VerkoperId', $verkoperId)
        ->first();

    if ($koppeling && $koppeling->Isactief == 1) {
        return false;
    }

    // Activeer een bestaande koppeling opnieuw
    if ($koppeling) {
        return DB::table('ContactPerVerkoper')
            ->where('ContactpersoonId', $contactpersoonId)
            ->where('VerkoperId', $verkoperId)
            ->update([
                'Isactief' => 1,
                'Datumgewijzigd' => now(),
            ]);
    }

    // Maak een nieuwe koppeling aan als deze nog niet bestaat
    return DB::table('ContactPerVerkoper')->insert([
        'ContactpersoonId' => $contactpersoonId,
        'VerkoperId' => $verkoperId,
        'Isactief' => 1,
        'Opmerking' => null,
        'DatumAangemaakt' => now(),
        'Datumgewijzigd' => now(),
    ]);
}
    // Wijzig een bestaande contactpersoon
    public function updateContactpersoon(int $id, array $gegevens)
    {
        return DB::transaction(function () use ($id, $gegevens) {
            $nu = now();

            // Werk de gegevens van de contactpersoon bij
            DB::table('Contactpersoon')
                ->where('Id', $id)
                ->update([
                    'Naam' => $gegevens['Naam'],
                    'Telefoonnummer' => $gegevens['Telefoonnummer'],
                    'E-mailadres' => $gegevens['E-mailadres'],
                    'Opmerking' => $gegevens['Opmerking'] ?? null,
                    'Datumgewijzigd' => $nu,
                ]);

            // Werk de koppeling met de verkoper bij
            DB::table('ContactPerVerkoper')
                ->where('ContactpersoonId', $id)
                ->where('Isactief', 1)
                ->update([
                    'VerkoperId' => $gegevens['VerkoperId'],
                    'Datumgewijzigd' => $nu,
                ]);
        });
    }

    // Verwijder een contactpersoon door deze te deactiveren
    public function destroyContactpersoon(int $id)
    {
        return DB::transaction(function () use ($id) {
            $nu = now();

            DB::table('Contactpersoon')
                ->where('Id', $id)
                ->update([
                    'Isactief' => 0,
                    'Datumgewijzigd' => $nu,
                ]);

            DB::table('ContactPerVerkoper')
                ->where('ContactpersoonId', $id)
                ->update([
                    'Isactief' => 0,
                    'Datumgewijzigd' => $nu,
                ]);
        });
    }
    // Haal alle actieve contactpersonen op
public function getAlleActieveContactpersonen()
{
    return DB::table('Contactpersoon')
        ->where('Isactief', 1)
        ->get();
}
}
