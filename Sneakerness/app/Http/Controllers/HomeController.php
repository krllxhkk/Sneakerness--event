<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Haal alle actieve tickets op uit de database
        $allTickets = DB::select('CALL sp_GetTicketsOverzicht(?)', [1]);

        $today = Carbon::today();

        // Zaterdag tickets
        $ticketsZaterdag = array_filter($allTickets, function ($ticket) use ($today) {
            $ticketDatum = Carbon::parse($ticket->datum);

            return $ticketDatum->dayOfWeek === Carbon::SATURDAY
                && $ticketDatum->gte($today);
        });

        // Zondag tickets
        $ticketsZondag = array_filter($allTickets, function ($ticket) use ($today) {
            $ticketDatum = Carbon::parse($ticket->datum);

            return $ticketDatum->dayOfWeek === Carbon::SUNDAY
                && $ticketDatum->gte($today);
        });

        return view('home.index', compact(
            'ticketsZaterdag',
            'ticketsZondag'
        ));
    }
}