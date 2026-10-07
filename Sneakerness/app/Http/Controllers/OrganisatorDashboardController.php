<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrganisatorDashboardController extends Controller
{
    /**
     * Toont het dashboard van de organisator.
     */
    public function index(): View
    {
        // Alle actieve tickets ophalen.
        $tickets = DB::table('tickets')
            ->where('is_actief', 1)
            ->orderBy('datum')
            ->orderBy('tijdslot')
            ->get();

        // Ticketgegevens.
        $totaalTickets = $tickets->sum('aantal_tickets_per_tijdslot');
        $verkochteTickets = 0;
        $beschikbareTickets = $totaalTickets - $verkochteTickets;
        $totaleOmzet = 0;

        // Standgegevens.
        $totaalStands = DB::table('stand')
            ->where('Isactief', 1)
            ->count();

        $verhuurdeStands = DB::table('stand')
            ->where('Isactief', 1)
            ->where('VerhuurdStatus', 1)
            ->count();

        $beschikbareStands = $totaalStands - $verhuurdeStands;

        // Alleen gebruikers met de rol bezoeker tellen.
        $aantalBezoekers = DB::table('users')
            ->where('rol', 'bezoeker')
            ->count();

        return view('organisator.dashboard', [
            'tickets' => $tickets,
            'totaalTickets' => $totaalTickets,
            'verkochteTickets' => $verkochteTickets,
            'beschikbareTickets' => $beschikbareTickets,
            'totaleOmzet' => $totaleOmzet,
            'totaalStands' => $totaalStands,
            'verhuurdeStands' => $verhuurdeStands,
            'beschikbareStands' => $beschikbareStands,
            'aantalBezoekers' => $aantalBezoekers,
        ]);
    }
}
