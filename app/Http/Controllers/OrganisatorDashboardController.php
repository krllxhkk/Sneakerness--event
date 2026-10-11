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

        // Huidige beschikbare voorraad.
        $beschikbareTickets = (int) $tickets
            ->sum('aantal_tickets_per_tijdslot');

        // Verkochte tickets uit bevestigde bestellingen.
        $verkochteTickets = (int) DB::table('bestellingen')
            ->where('status', 'bevestigd')
            ->sum('aantal');

        // Totale capaciteit op basis van voorraad en verkopen.
        $totaalTickets = $beschikbareTickets + $verkochteTickets;

        // Totale omzet uit bevestigde bestellingen.
        $totaleOmzet = (float) DB::table('bestellingen')
            ->where('status', 'bevestigd')
            ->sum('totaalprijs');

        // De 5 nieuwste ticketbestellingen.
        $recenteBestellingen = DB::table('bestellingen')
            ->join(
                'tickets',
                'bestellingen.ticket_id',
                '=',
                'tickets.id'
            )
            ->select(
                'bestellingen.id',
                'bestellingen.naam',
                'bestellingen.email',
                'bestellingen.aantal',
                'bestellingen.totaalprijs',
                'bestellingen.status',
                'tickets.datum',
                'tickets.tijdslot'
            )
            ->orderByDesc('bestellingen.id')
            ->limit(5)
            ->get();

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
            'recenteBestellingen' => $recenteBestellingen,
            'totaalStands' => $totaalStands,
            'verhuurdeStands' => $verhuurdeStands,
            'beschikbareStands' => $beschikbareStands,
            'aantalBezoekers' => $aantalBezoekers,
        ]);
    }
}
