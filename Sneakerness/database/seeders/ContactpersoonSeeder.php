<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactpersoonSeeder extends Seeder
{
    // Voeg testdata toe
    public function run(): void
    {
        $nu = now();

        // Voeg contactpersonen toe
        DB::table('Contactpersoon')->insert([
            [
                'Id' => 1,
                'Naam' => 'Lisa de Vries',
                'Telefoonnummer' => '0612345678',
                'E-mailadres' => 'lisa@sneakerdistrict.nl',
                'Isactief' => 1,
                'Opmerking' => null,
                'Datumaangemaakt' => $nu,
                'Datumgewijzigd' => $nu,
            ],
            [
                'Id' => 2,
                'Naam' => 'Daan Jansen',
                'Telefoonnummer' => '0623456789',
                'E-mailadres' => 'daan@solecity.nl',
                'Isactief' => 1,
                'Opmerking' => null,
                'Datumaangemaakt' => $nu,
                'Datumgewijzigd' => $nu,
            ],
            [
                'Id' => 3,
                'Naam' => 'Milan Bakker',
                'Telefoonnummer' => '0634567890',
                'E-mailadres' => 'milan@streetkicks.nl',
                'Isactief' => 1,
                'Opmerking' => null,
                'Datumaangemaakt' => $nu,
                'Datumgewijzigd' => $nu,
            ],
            [
                'Id' => 4,
                'Naam' => 'Sophie Smit',
                'Telefoonnummer' => '0645678901',
                'E-mailadres' => 'sophie@urbansoles.nl',
                'Isactief' => 1,
                'Opmerking' => null,
                'Datumaangemaakt' => $nu,
                'Datumgewijzigd' => $nu,
            ],
            [
                'Id' => 5,
                'Naam' => 'Noah Visser',
                'Telefoonnummer' => '0656789012',
                'E-mailadres' => 'noah@kicksco.nl',
                'Isactief' => 1,
                'Opmerking' => null,
                'Datumaangemaakt' => $nu,
                'Datumgewijzigd' => $nu,
            ],
        ]);

        // Koppel contactpersonen aan verkopers
        DB::table('ContactPerVerkoper')->insert([
            [
                'Id' => 1,
                'VerkoperId' => 1,
                'ContactpersoonId' => 1,
                'Isactief' => 1,
                'Opmerking' => null,
                'Datumaangemaakt' => $nu,
                'Datumgewijzigd' => $nu,
            ],
            [
                'Id' => 2,
                'VerkoperId' => 2,
                'ContactpersoonId' => 2,
                'Isactief' => 1,
                'Opmerking' => null,
                'Datumaangemaakt' => $nu,
                'Datumgewijzigd' => $nu,
            ],
            [
                'Id' => 3,
                'VerkoperId' => 3,
                'ContactpersoonId' => 3,
                'Isactief' => 1,
                'Opmerking' => null,
                'Datumaangemaakt' => $nu,
                'Datumgewijzigd' => $nu,
            ],
            [
                'Id' => 4,
                'VerkoperId' => 4,
                'ContactpersoonId' => 4,
                'Isactief' => 1,
                'Opmerking' => null,
                'Datumaangemaakt' => $nu,
                'Datumgewijzigd' => $nu,
            ],
            [
                'Id' => 5,
                'VerkoperId' => 5,
                'ContactpersoonId' => 5,
                'Isactief' => 1,
                'Opmerking' => null,
                'Datumaangemaakt' => $nu,
                'Datumgewijzigd' => $nu,
            ],
        ]);
    }
}