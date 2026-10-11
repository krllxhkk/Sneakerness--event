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
            ->join(
                'ContactPerVerkoper',
                'Contactpersoon.Id',
                '=',
                'ContactPerVerkoper.ContactpersoonId'
            )
            ->join(
                'Verkoper',
                'ContactPerVerkoper.VerkoperId',
                '=',
                'Verkoper.Id'
            )
            ->where('Contactpersoon.Isactief', 1)
            ->select(
                'Contactpersoon.*',
                'Verkoper.Naam as VerkoperNaam'
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
        // Controleer of de koppeling al bestaat
        $bestaat = DB::table('ContactPerVerkoper')
            ->where('ContactpersoonId', $contactpersoonId)
            ->where('VerkoperId', $verkoperId)
            ->where('Isactief', 1)
            ->exists();

        if ($bestaat) {
            return false;
        }

        // Maak de koppeling aan
        return DB::table('ContactPerVerkoper')->insert([
            'ContactpersoonId' => $contactpersoonId,
            'VerkoperId' => $verkoperId,
            'Isactief' => 1,
            'Opmerking' => null,
            'DatumAangemaakt' => now(),
            'Datumgewijzigd' => now(),
        ]);
    }

}
