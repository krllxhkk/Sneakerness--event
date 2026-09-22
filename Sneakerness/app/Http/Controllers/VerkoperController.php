<?php

namespace App\Http\Controllers;

use App\Models\Verkoper;
use App\Models\Contactpersoon;

class VerkoperController extends Controller
{
    // Toon het overzicht van verkopers
    public function index()
    {
        // Maak de modellen aan
        $verkoperModel = new Verkoper();
        $contactpersoonModel = new Contactpersoon();

        // Haal de actieve gegevens op
        $verkopers = $verkoperModel->getVerkopersOverzicht();
        $contactpersonen = $contactpersoonModel->getContactpersonenOverzicht();

        // Stuur beide gegevens naar de view
        return view('verkopers.index', compact(
            'verkopers',
            'contactpersonen'
        ));
    }
}