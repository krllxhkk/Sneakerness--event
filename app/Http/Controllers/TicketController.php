<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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

        // Alleen stap 1, 2 en 3 worden hier getoond.
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
            $alleTickets = DB::select(
                'CALL sp_GetTicketsOverzicht(?)',
                [1]
            );

            $vandaag = Carbon::today();

            // Toon alleen actieve en beschikbare tickets.
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

        // Controleer of het tijdslot bij de gekozen dag hoort.
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
     * Er wordt nog geen bestelling opgeslagen.
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
             * Schakel deze alleen tijdelijk in om een fout te testen.
             */

            // throw new Exception('Test: database niet bereikbaar');

            // Controleer het geselecteerde ticket opnieuw.
            $alleTickets = DB::select(
                'CALL sp_GetTicketsOverzicht(?)',
                [1]
            );

            $gekozenTicket = collect($alleTickets)->first(
                function ($ticket) use ($gegevens) {
                    return (int) $ticket->id
                        === (int) $gegevens['ticket_id'];
                }
            );

            // Controleer of het ticket beschikbaar is.
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

            // Haal de prijs uit de database.
            $prijsPerTicket = (float) $gekozenTicket->tarief;

            // Bereken de totaalprijs.
            $totaalprijs = round(
                $prijsPerTicket * (int) $gegevens['aantal'],
                2
            );

            // Bewaar de gegevens tijdelijk in de sessie.
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

            // Ga naar het besteloverzicht.
            return redirect()->route('tickets.overzicht');
        } catch (Exception $fout) {
            Log::error('Fout bij verwerken van ticketgegevens', [
                'melding' => $fout->getMessage(),
            ]);

            $request->session()->forget('ticket_besteloverzicht');

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

        // Zonder gegevens mag de bezoeker niet verder.
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

    /**
     * Verwerk de bestelling en maak unieke tickets aan.
     */
    public function bestellen(Request $request): RedirectResponse
    {
        // Haal de bestelgegevens uit de sessie.
        $besteloverzicht = $request->session()->get(
            'ticket_besteloverzicht'
        );

        if (! is_array($besteloverzicht)) {
            return redirect()
                ->route('tickets.index')
                ->with('error', 'Vul eerst je gegevens in.');
        }

        // Controleer de gegevens opnieuw aan de serverkant.
        $validator = Validator::make($besteloverzicht, [
            'ticket_id' => ['required', 'integer', 'min:1'],
            'naam' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'aantal' => ['required', 'integer', 'between:1,10'],
            'betaalmethode' => ['required', 'in:ideal,contant'],
        ]);

        if ($validator->fails()) {
            $request->session()->forget('ticket_besteloverzicht');

            return redirect()
                ->route('tickets.index')
                ->with(
                    'error',
                    'De bestelgegevens zijn ongeldig. Probeer opnieuw.'
                );
        }

        try {
            /*
             * UNHAPPY SCENARIO TEST
             *
             * Schakel de volgende regel tijdelijk in
             * om een databasefout te simuleren.
             */

            // throw new Exception('Test: database niet bereikbaar');

            // Gebruik een transactie zodat er geen halve bestelling ontstaat.
            $bestelling = DB::transaction(function () use ($besteloverzicht) {

                // Vergrendel het tijdslot tijdens de bestelling.
                $ticket = DB::table('tickets')
                    ->where('id', $besteloverzicht['ticket_id'])
                    ->lockForUpdate()
                    ->first();

                // Controleer of het tijdslot beschikbaar is.
                if (
                    $ticket === null
                    || (int) $ticket->is_actief !== 1
                    || Carbon::parse($ticket->datum)->lt(Carbon::today())
                    || (int) $ticket->aantal_tickets_per_tijdslot
                    < (int) $besteloverzicht['aantal']
                ) {
                    throw new \DomainException(
                        'Dit tijdslot heeft niet genoeg beschikbare tickets.'
                    );
                }

                $datum = Carbon::parse($ticket->datum);

                // Alleen zaterdag en zondag zijn toegestaan.
                if (! in_array(
                    $datum->dayOfWeek,
                    [Carbon::SATURDAY, Carbon::SUNDAY],
                    true
                )) {
                    throw new \DomainException(
                        'Het geselecteerde tijdslot is ongeldig.'
                    );
                }

                // Bereken de prijs opnieuw vanuit de database.
                $aantal = (int) $besteloverzicht['aantal'];
                $prijsPerTicket = (float) $ticket->tarief;
                $totaalprijs = round($prijsPerTicket * $aantal, 2);

                /// Maak de bestelling aan.
                $bestellingId = DB::table('bestellingen')->insertGetId([
                    'user_id' => request()->user()?->id,
                    'ticket_id' => (int) $ticket->id,
                    'naam' => $besteloverzicht['naam'],
                    'email' => $besteloverzicht['email'],
                    'aantal' => $aantal,
                    'prijs_per_ticket' => $prijsPerTicket,
                    'totaalprijs' => $totaalprijs,
                    'betaalmethode' => $besteloverzicht['betaalmethode'],
                    'status' => 'bevestigd',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $ticketnummers = [];

                // Maak een uniek ticketnummer voor elk gekocht ticket.
                for ($nummer = 0; $nummer < $aantal; $nummer++) {
                    $ticketnummer = 'TK-' . strtoupper(Str::random(12));

                    DB::table('bestelde_tickets')->insert([
                        'bestelling_id' => $bestellingId,
                        'ticket_id' => (int) $ticket->id,
                        'ticketnummer' => $ticketnummer,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $ticketnummers[] = $ticketnummer;
                }

                // Verminder het aantal beschikbare tickets.
                DB::table('tickets')
                    ->where('id', $ticket->id)
                    ->decrement(
                        'aantal_tickets_per_tijdslot',
                        $aantal
                    );

                // Geef de gegevens terug voor de bevestiging.
                return [
                    'id' => $bestellingId,
                    'naam' => $besteloverzicht['naam'],
                    'email' => $besteloverzicht['email'],
                    'datum' => $datum->format('d-m-Y'),
                    'dag' => $datum->dayOfWeek === Carbon::SATURDAY
                        ? 'Zaterdag'
                        : 'Zondag',
                    'tijdslot' => substr($ticket->tijdslot, 0, 5),
                    'aantal' => $aantal,
                    'betaalmethode' => $besteloverzicht['betaalmethode'],
                    'totaalprijs' => $totaalprijs,
                    'ticketnummers' => $ticketnummers,
                ];
            });
        } catch (\DomainException $fout) {
            return redirect()
                ->route('tickets.overzicht')
                ->with('error', $fout->getMessage());
        } catch (\Throwable $fout) {
            Log::error('Fout bij opslaan van ticketbestelling', [
                'melding' => $fout->getMessage(),
            ]);

            return redirect()
                ->route('tickets.overzicht')
                ->with(
                    'error',
                    'De bestelling kan momenteel niet worden verwerkt. Probeer het later opnieuw.'
                );
        }

        // De bestelling is succesvol opgeslagen.
        $request->session()->forget('ticket_besteloverzicht');

        // Bewaar de gegevens voor de bevestigingspagina.
        $request->session()->put(
            'ticket_bevestiging',
            $bestelling
        );

        /*
         * NIEUW: BEVESTIGINGSBERICHT IN DE MAILBOX
         *
         * De bezoeker ontvangt een intern websitebericht.
         * Dit werkt met en zonder login in dezelfde sessie.
         */

        // Haal de bestaande berichten op.
        $berichten = $request->session()->get('ticket_mailbox', []);

        // Voeg het nieuwe bevestigingsbericht toe.
        $berichten[] = [
            'id' => (int) $bestelling['id'],
            'onderwerp' => 'Je Sneakerness® tickets',
            'bestelling' => $bestelling,
            'gelezen' => false,
            'ontvangen_op' => now()->format('d-m-Y H:i'),
        ];

        // Bewaar de mailbox in de sessie.
        $request->session()->put('ticket_mailbox', $berichten);

        // Ga naar de bevestigingspagina.
        return redirect()->route('tickets.bevestiging');
    }

    /**
     * Toon de bevestigingspagina na een succesvolle bestelling.
     */
    public function bevestiging(Request $request): View|RedirectResponse
    {
        // Haal de bevestigde bestelling op uit de sessie.
        $bestelling = $request->session()->get('ticket_bevestiging');

        // Zonder bevestigde bestelling mag de bezoeker
        // de bevestigingspagina niet bekijken.
        if (! is_array($bestelling)) {
            return redirect()
                ->route('tickets.index')
                ->with(
                    'error',
                    'Er is geen bevestigde bestelling gevonden.'
                );
        }

        // Toon de bevestigingspagina met de bestelgegevens.
        return view(
            'tickets.bevestiging',
            compact('bestelling')
        );
    }
}
