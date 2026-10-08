<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // 1. Roep de Stored Procedure aan in MySQL voor alle actieve tickets
            $allTickets = DB::select('CALL sp_GetTicketsOverzicht(?)', [1]);

            // Tijdstip van VANDAAG (begin van de dag) ophalen
            $today = Carbon::today();

            // 2. Filter ZATERDAG: Datum moet Zaterdag zijn én gelijk aan of ná vandaag zijn
            $ticketsZaterdag = array_filter($allTickets, function ($ticket) use ($today) {
                $ticketDatum = Carbon::parse($ticket->datum);
                return $ticketDatum->dayOfWeek === Carbon::SATURDAY && $ticketDatum->gte($today);
            });

            // 3. Filter ZONDAG: Datum moet Zondag zijn én gelijk aan of ná vandaag zijn
            $ticketsZondag = array_filter($allTickets, function ($ticket) use ($today) {
                $ticketDatum = Carbon::parse($ticket->datum);
                return $ticketDatum->dayOfWeek === Carbon::SUNDAY && $ticketDatum->gte($today);
            });

            $errorMessage = null;

            return view('home.index', compact('ticketsZaterdag', 'ticketsZondag', 'errorMessage'));

        } catch (Exception $e) {
            // Unhappy Scenario: Bij een database- of systeemfout
            $ticketsZaterdag = [];
            $ticketsZondag = [];
            $errorMessage = 'De pagina kan momenteel niet geladen worden. Probeer het later opnieuw.';

            return view('home.index', compact('ticketsZaterdag', 'ticketsZondag', 'errorMessage'));
        }
    }
}