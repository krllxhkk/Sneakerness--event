<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Verkoper extends Model
{
    // Gebruik de juiste tabel
    protected $table = 'Verkoper';

    // Gebruik Id als primary key
    protected $primaryKey = 'Id';

    // Gebruik geen standaard timestamps
    public $timestamps = false;

    // Haal alle actieve verkopers op
    public function getVerkopersOverzicht()
    {
        return DB::table('Verkoper')
            ->where('Isactief', 1)
            ->get();
    }
}