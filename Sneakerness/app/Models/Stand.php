<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stand extends Model
{
    protected $table = 'Stand';

    public $timestamps = false;

    protected $fillable = [
        'VerkoperId',
        'StandType',
        'Prijs',
        'VerhuurdStatus',
        'Isactief',
        'Opmerking',
        'Datumaangemaakt',
        'Datumgewijzigd',
    ];
}