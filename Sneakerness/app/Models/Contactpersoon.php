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
}