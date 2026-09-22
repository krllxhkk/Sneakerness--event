<?php

namespace App\Http\Controllers;

use App\Models\Verkoper;

class VerkoperController extends Controller
{
    // Toon het overzicht van verkopers
    public function index()
    {
        $verkoperModel = new Verkoper();

        // Haal de actieve verkopers op
        $verkopers = $verkoperModel->getVerkopersOverzicht();

        // Stuur de data naar de view
        return view('verkopers.index', compact('verkopers'));
    }
}