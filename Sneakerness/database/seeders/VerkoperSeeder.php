<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VerkoperSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('Verkoper')->insert([
            [
                'Id' => 1,
                'Naam' => 'Sneaker District',
                'SpecialeStatus' => 'Partner',
                'VerkooptSoort' => 'Sneakers',
                'StandType' => 'AA+',
                'Dagen' => 'Zaterdag',
                'Logo' => null,
                'Isactief' => 1,
                'Opmerking' => null,
                'Datumaangemaakt' => now(),
                'Datumgewijzigd' => now(),
            ],
            [
                'Id' => 2,
                'Naam' => 'Sole City',
                'SpecialeStatus' => null,
                'VerkooptSoort' => 'Sneakers en kleding',
                'StandType' => 'AA',
                'Dagen' => 'Zaterdag en zondag',
                'Logo' => null,
                'Isactief' => 1,
                'Opmerking' => null,
                'Datumaangemaakt' => now(),
                'Datumgewijzigd' => now(),
            ],
            [
                'Id' => 3,
                'Naam' => 'Street Kicks',
                'SpecialeStatus' => 'Premium',
                'VerkooptSoort' => 'Sneakers',
                'StandType' => 'A',
                'Dagen' => 'Zondag',
                'Logo' => null,
                'Isactief' => 1,
                'Opmerking' => null,
                'Datumaangemaakt' => now(),
                'Datumgewijzigd' => now(),
            ],
            [
                'Id' => 4,
                'Naam' => 'Urban Soles',
                'SpecialeStatus' => null,
                'VerkooptSoort' => 'Sneakers en accessoires',
                'StandType' => 'AA',
                'Dagen' => 'Zaterdag',
                'Logo' => null,
                'Isactief' => 1,
                'Opmerking' => null,
                'Datumaangemaakt' => now(),
                'Datumgewijzigd' => now(),
            ],
            [
                'Id' => 5,
                'Naam' => 'Kicks & Co',
                'SpecialeStatus' => 'Partner',
                'VerkooptSoort' => 'Sneakers en kleding',
                'StandType' => 'A',
                'Dagen' => 'Zaterdag en zondag',
                'Logo' => null,
                'Isactief' => 1,
                'Opmerking' => null,
                'Datumaangemaakt' => now(),
                'Datumgewijzigd' => now(),
            ],
        ]);
    }
}