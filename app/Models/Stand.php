<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stand extends Model
{
    // Gebruik de bestaande standtabel
    protected $table = 'Stand';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    // Velden die opgeslagen mogen worden
    protected $fillable = [
        'VerkoperId',
        'Standnummer',
        'Standnaam',
        'Locatie',
        'Kwaliteitsklasse',
        'StandType',
        'Prijs',
        'VerhuurdStatus',
        'Isactief',
        'Opmerking',
        'Datumaangemaakt',
        'Datumgewijzigd',
    ];

    // Een stand kan gekoppeld zijn aan een verkoper
    public function verkoper()
    {
        return $this->belongsTo(Verkoper::class, 'VerkoperId', 'Id');
    }
}
