<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class TicketController extends Controller
{
    /**
     * Toon de stappen voor het kopen van tickets.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $gekozenDag = $request->query('dag');
        $stap = (int) $request->query('stap', 1);

        // Controleer of de gekozen dag geldig is.
        if (
            $gekozenDag !== null
            && ! in_array($gekozenDag, ['zaterdag', 'zondag'], true)
        ) {
            return redirect()->route('tickets.index');
        }

        // Alleen stap 1, 2 en 3 worden op deze pagina getoond.
        if ($stap < 1 || $stap > 3) {
            return redirect()->route('tickets.index');
        }

        // De bezoeker moet eerst een dag kiezen.
        if ($stap > 1 && $gekozenDag === null) {
            return redirect()->route('tickets.index');
        }

        $ticketsZaterdag = [];
        $ticketsZondag = [];
        $errorMessage = null;
        $gekozenTicket = null;

        try {
            // Haal de tickets op via de bestaande stored procedure.
            $alleTickets = DB::select('CALL sp_GetTicketsOverzicht(?)', [1]);

            $vandaag = Carbon::today();

            // Toon alleen actieve tickets die nog beschikbaar zijn.
            $beschikbareTickets = array_filter(
                $alleTickets,
                function ($ticket) use ($vandaag) {
                    $datum = Carbon::parse($ticket->datum);

                    return $datum->gte($vandaag)
                        && (int) $ticket->is_actief === 1
                        && (int) $ticket->aantal_tickets_per_tijdslot > 0;
                }
            );

            // Verdeel de tickets over zaterdag en zondag.
            $ticketsZaterdag = array_filter(
                $beschikbareTickets,
                function ($ticket) {
                    return Carbon::parse($ticket->datum)->dayOfWeek
                        === Carbon::SATURDAY;
                }
            );

            $ticketsZondag = array_filter(
                $beschikbareTickets,
                function ($ticket) {
                    return Carbon::parse($ticket->datum)->dayOfWeek
                        === Carbon::SUNDAY;
                }
            );
        } catch (Exception $fout) {
            Log::error('Fout bij ophalen van tickets', [
                'melding' => $fout->getMessage(),
            ]);

            $errorMessage = 'Er is een fout opgetreden bij het ophalen van de tickets. Probeer het later opnieuw.';
        }

        // Controleer of het geselecteerde tijdslot bij de gekozen dag hoort.
        if ($stap === 3) {
            $ticketId = $request->query('ticket');

            if (! filter_var($ticketId, FILTER_VALIDATE_INT)) {
                return redirect()->route('tickets.index', [
                    'dag' => $gekozenDag,
                    'stap' => 2,
                ])->with('error', 'Kies eerst een tijdslot.');
            }

            $ticketsVanDag = $gekozenDag === 'zaterdag'
                ? $ticketsZaterdag
                : $ticketsZondag;

            foreach ($ticketsVanDag as $ticket) {
                if ((int) $ticket->id === (int) $ticketId) {
                    $gekozenTicket = $ticket;
                    break;
                }
            }

            if ($gekozenTicket === null) {
                return redirect()->route('tickets.index', [
                    'dag' => $gekozenDag,
                    'stap' => 2,
                ])->with(
                    'error',
                    $errorMessage
                        ?? 'Dit tijdslot is niet beschikbaar. Kies een ander tijdslot.'
                );
            }
        }

        return view('tickets.index', compact(
            'gekozenDag',
            'stap',
            'ticketsZaterdag',
            'ticketsZondag',
            'errorMessage',
            'gekozenTicket'
        ));
    }

    /**
     * Controleer de gegevens van de bezoeker.
     *
     * Bewaar de gegevens tijdelijk in de sessie.
     * Er wordt nog geen bestelling in de database opgeslagen.
     */
    public function gegevensOpslaan(Request $request): RedirectResponse
    {
        // Verwijder een eventueel oud besteloverzicht.
        $request->session()->forget('ticket_besteloverzicht');

        // Controleer de gegevens aan de serverkant.
        $gegevens = $request->validate([
            'ticket_id' => ['required', 'integer', 'min:1'],
            'naam' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'aantal' => ['required', 'integer', 'between:1,10'],
            'betaalmethode' => ['required', 'in:ideal,contant'],
        ], [
            'ticket_id.required' => 'Kies eerst een tijdslot.',
            'ticket_id.integer' => 'Het geselecteerde ticket is ongeldig.',
            'naam.required' => 'Vul je naam in.',
            'naam.max' => 'Je naam mag maximaal 100 tekens bevatten.',
            'email.required' => 'Vul je e-mailadres in.',
            'email.email' => 'Vul een geldig e-mailadres in.',
            'aantal.required' => 'Vul het aantal tickets in.',
            'aantal.integer' => 'Vul een geldig aantal tickets in.',
            'aantal.between' => 'Je kunt minimaal 1 en maximaal 10 tickets bestellen.',
            'betaalmethode.required' => 'Kies een betaalmethode.',
            'betaalmethode.in' => 'Kies iDEAL of contant.',
        ]);

        try {
            /*
             * UNHAPPY SCENARIO TEST
             *
             * Laat de onderstaande regel normaal uitgeschakeld.
             *
             * Verwijder de twee schuine strepen om tijdelijk
             * een databasefout te simuleren.
             *
             * Na de test moet je de regel weer uitschakelen.
             */

            // throw new Exception('Test: database niet bereikbaar');

            // Controleer het geselecteerde ticket opnieuw in de database.
            $alleTickets = DB::select('CALL sp_GetTicketsOverzicht(?)', [1]);

            $gekozenTicket = collect($alleTickets)->first(
                function ($ticket) use ($gegevens) {
                    return (int) $ticket->id
                        === (int) $gegevens['ticket_id'];
                }
            );

            // Controleer of het ticket bestaat en beschikbaar is.
            if (
                $gekozenTicket === null
                || (int) $gekozenTicket->is_actief !== 1
                || Carbon::parse($gekozenTicket->datum)->lt(Carbon::today())
                || (int) $gekozenTicket->aantal_tickets_per_tijdslot
                    < (int) $gegevens['aantal']
            ) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Dit tijdslot heeft niet genoeg beschikbare tickets.'
                    );
            }

            $datum = Carbon::parse($gekozenTicket->datum);

            // Tickets mogen alleen voor zaterdag of zondag zijn.
            if (
                ! in_array(
                    $datum->dayOfWeek,
                    [Carbon::SATURDAY, Carbon::SUNDAY],
                    true
                )
            ) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Het geselecteerde ticket is ongeldig.'
                    );
            }

            // De prijs wordt altijd uit de database gehaald.
            $prijsPerTicket = (float) $gekozenTicket->tarief;

            // Bereken de totaalprijs.
            $totaalprijs = round(
                $prijsPerTicket * (int) $gegevens['aantal'],
                2
            );

            // Bewaar de gecontroleerde gegevens tijdelijk in de sessie.
            $request->session()->put('ticket_besteloverzicht', [
                'ticket_id' => (int) $gekozenTicket->id,
                'dag' => $datum->dayOfWeek === Carbon::SATURDAY
                    ? 'zaterdag'
                    : 'zondag',
                'datum' => $datum->format('Y-m-d'),
                'tijdslot' => substr($gekozenTicket->tijdslot, 0, 5),
                'naam' => $gegevens['naam'],
                'email' => $gegevens['email'],
                'aantal' => (int) $gegevens['aantal'],
                'betaalmethode' => $gegevens['betaalmethode'],
                'prijs_per_ticket' => $prijsPerTicket,
                'totaalprijs' => $totaalprijs,
            ]);

            // Ga alleen bij succes naar het besteloverzicht.
            return redirect()->route('tickets.overzicht');
        } catch (Exception $fout) {
            // Bewaar technische informatie in het Laravel-logbestand.
            Log::error('Fout bij verwerken van ticketgegevens', [
                'melding' => $fout->getMessage(),
            ]);

            // Zorg dat geen oude bestelling zichtbaar blijft.
            $request->session()->forget('ticket_besteloverzicht');

            // Blijf op stap 3 en toon een duidelijke foutmelding.
            return back()
                ->withInput()
                ->with(
                    'error',
                    'De gegevens kunnen momenteel niet worden verwerkt. Probeer het later opnieuw.'
                );
        }
    }

    /**
     * Toon het besteloverzicht van de bezoeker.
     */
    public function overzicht(Request $request): View|RedirectResponse
    {
        $besteloverzicht = $request->session()->get(
            'ticket_besteloverzicht'
        );

        // Zonder gecontroleerde gegevens mag de bezoeker niet verder.
        if ($besteloverzicht === null) {
            return redirect()
                ->route('tickets.index')
                ->with(
                    'error',
                    'Vul eerst je gegevens in.'
                );
        }

        return view(
            'tickets.overzicht',
            compact('besteloverzicht')
        );
    }
}
