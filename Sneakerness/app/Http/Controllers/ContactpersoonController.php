<?php

namespace App\Http\Controllers;

use App\Models\Contactpersoon;

class ContactpersoonController extends Controller
{
    // Toon het overzicht van contactpersonen
    public function index()
    {
        // Maak het model aan
        $contactpersoonModel = new Contactpersoon();

        // Haal de actieve contactpersonen op
        $contactpersonen = $contactpersoonModel->getContactpersonenOverzicht();

        // Stuur de data naar de view
        return view('contactpersonen.index', compact('contactpersonen'));
    }
}