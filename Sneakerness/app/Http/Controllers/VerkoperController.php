<?php

namespace App\Http\Controllers;

use App\Models\Verkoper;
use App\Models\Contactpersoon;
use Illuminate\Support\Facades\Log;

class VerkoperController extends Controller
{
    // Toon het overzicht van verkopers en contactpersonen
    public function index()
    {
        $verkoperModel = new Verkoper();
        $contactpersoonModel = new Contactpersoon();

        $verkopers = collect();
        $contactpersonen = collect();

        $verkopersFout = null;
        $contactpersonenFout = null;

        // Haal verkopers op
        try {
            $verkopers = $verkoperModel->getVerkopersOverzicht();
        } catch (\Throwable $e) {
            Log::error('Databasefout bij ophalen van verkopers.', [
                'message' => $e->getMessage(),
            ]);

            $verkopersFout = 'De verkopers konden niet worden opgehaald.';
        }

        // Haal contactpersonen op
        try {
            $contactpersonen = $contactpersoonModel->getContactpersonenOverzicht();
        } catch (\Throwable $e) {
            Log::error('Databasefout bij ophalen van contactpersonen.', [
                'message' => $e->getMessage(),
            ]);

            $contactpersonenFout = 'De contactpersonen konden niet worden opgehaald.';
        }

        return view('verkopers.index', compact(
            'verkopers',
            'contactpersonen',
            'verkopersFout',
            'contactpersonenFout'
        ));
    }
}