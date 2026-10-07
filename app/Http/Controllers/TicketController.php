<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $gekozenDag = $request->query('dag');
        $stap = (int) $request->query('stap', 1);

        try {
            // 1. Roep exact dezelfde Stored Procedure aan als in HomeController
            $allTickets = DB::select('CALL sp_GetTicketsOverzicht(?)', [1]);

            $today = Carbon::today();

            // 2. Filter ZATERDAG
            $ticketsZaterdag = array_filter($allTickets, function ($ticket) use ($today) {
                $ticketDatum = Carbon::parse($ticket->datum ?? $ticket->Datum);
                return $ticketDatum->dayOfWeek === Carbon::SATURDAY && $ticketDatum->gte($today);
            });

            // 3. Filter ZONDAG
            $ticketsZondag = array_filter($allTickets, function ($ticket) use ($today) {
                $ticketDatum = Carbon::parse($ticket->datum ?? $ticket->Datum);
                return $ticketDatum->dayOfWeek === Carbon::SUNDAY && $ticketDatum->gte($today);
            });

            $errorMessage = null;

        } catch (Exception $e) {
            $ticketsZaterdag = [];
            $ticketsZondag = [];
            $errorMessage = 'Er is een fout opgetreden bij het ophalen van de tickets. Probeer het later opnieuw.';
        }

        return view('tickets.index', compact(
            'gekozenDag',
            'stap',
            'ticketsZaterdag',
            'ticketsZondag',
            'errorMessage'
        ));
    }
}