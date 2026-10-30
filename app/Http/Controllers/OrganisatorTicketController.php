<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class OrganisatorTicketController extends Controller
{
    /**
     * Toon alle tickets in het overzicht.
     */
    public function index(): View
    {
        try {
            // Haal alle tickets op uit de database.
            $tickets = DB::table('tickets')
                ->orderBy('datum')
                ->orderBy('tijdslot')
                ->get();

            $errorMessage = null;
        } catch (Throwable $fout) {
            // Registreer een eventuele databasefout.
            Log::error('Tickets ophalen mislukt.', [
                'fout' => $fout->getMessage(),
            ]);

            $tickets = collect();
            $errorMessage = 'Tickets kunnen momenteel niet worden opgehaald.';
        }

        return view(
            'organisator.tickets.index',
            compact('tickets', 'errorMessage')
        );
    }

    /**
     * Toon het formulier om een ticket toe te voegen.
     */
    public function create(): View
    {
        return view('organisator.tickets.create');
    }

    /**
     * Controleer de gegevens en voeg een nieuw ticket toe.
     */
    public function store(Request $verzoek): RedirectResponse
    {
        // Controleer de ingevulde gegevens.
        $gegevens = $verzoek->validate(
            [
                'datum' => ['required', 'date', 'after_or_equal:today'],
                'tijdslot' => ['required', 'in:11:00,12:00,14:00,16:00'],
                'tarief' => ['required', 'numeric', 'min:0', 'max:999999.99'],
                'aantal_tickets_per_tijdslot' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:2147483647',
                ],
            ],
            [
                'datum.required' => 'Vul een datum in.',
                'datum.date' => 'Vul een geldige datum in.',
                'datum.after_or_equal' => 'De datum mag niet in het verleden liggen.',
                'tijdslot.required' => 'Kies een tijdslot.',
                'tijdslot.in' => 'Kies een geldig tijdslot.',
                'tarief.required' => 'Vul een prijs in.',
                'tarief.numeric' => 'Vul een geldige prijs in.',
                'tarief.min' => 'De prijs mag niet negatief zijn.',
                'tarief.max' => 'De prijs is te hoog.',
                'aantal_tickets_per_tijdslot.required' => 'Vul het aantal tickets in.',
                'aantal_tickets_per_tijdslot.integer' => 'Vul een heel aantal tickets in.',
                'aantal_tickets_per_tijdslot.min' => 'Het aantal tickets moet minimaal 1 zijn.',
                'aantal_tickets_per_tijdslot.max' => 'Het aantal tickets is te hoog.',
            ]
        );

        try {
            // Controleer of deze datum en dit tijdslot al bestaan.
            $ticketBestaat = DB::table('tickets')
                ->where('datum', $gegevens['datum'])
                ->where('tijdslot', $gegevens['tijdslot'])
                ->exists();

            // Unhappy scenario: voorkom dubbele tickets.
            if ($ticketBestaat) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Er staat al een ticket gepland op deze datum en tijd.'
                    );
            }

            // Voeg het nieuwe ticket toe aan de database.
            DB::table('tickets')->insert([
                'datum' => $gegevens['datum'],
                'tijdslot' => $gegevens['tijdslot'],
                'tarief' => $gegevens['tarief'],
                'aantal_tickets_per_tijdslot' => $gegevens['aantal_tickets_per_tijdslot'],
                'is_actief' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Registreer de succesvolle toevoeging.
            Log::info('Ticket succesvol toegevoegd.', [
                'datum' => $gegevens['datum'],
                'tijdslot' => $gegevens['tijdslot'],
            ]);

            return redirect()
                ->route('organisator.tickets.index')
                ->with('success', 'Ticket succesvol toegevoegd.');
        } catch (Throwable $fout) {
            // Registreer de technische fout.
            Log::error('Ticket toevoegen mislukt.', [
                'fout' => $fout->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Het ticket kan momenteel niet worden toegevoegd. Probeer het later opnieuw.'
                );
        }
    }

    /**
     * Wijzig de gegevens van een bestaand ticket.
     *
     * Sprint 3 - Ticket wijzigen (UPDATE).
     */
    public function update(Request $verzoek, int $id): RedirectResponse
    {
        // =====================================
        // 1. CONTROLEER DE INGEVULDE GEGEVENS
        // =====================================

        $gegevens = $verzoek->validate(
            [
                'datum' => ['required', 'date', 'after_or_equal:today'],
                'tijdslot' => ['required', 'in:11:00,12:00,14:00,16:00'],
                'tarief' => ['required', 'numeric', 'min:0', 'max:999999.99'],
                'aantal_tickets_per_tijdslot' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:2147483647',
                ],
                'is_actief' => ['required', 'boolean'],
            ],
            [
                'datum.required' => 'Vul een datum in.',
                'datum.date' => 'Vul een geldige datum in.',
                'datum.after_or_equal' => 'De datum mag niet in het verleden liggen.',
                'tijdslot.required' => 'Kies een tijdslot.',
                'tijdslot.in' => 'Kies een geldig tijdslot.',
                'tarief.required' => 'Vul een prijs in.',
                'tarief.numeric' => 'Vul een geldige prijs in.',
                'tarief.min' => 'De prijs mag niet negatief zijn.',
                'tarief.max' => 'De prijs is te hoog.',
                'aantal_tickets_per_tijdslot.required' => 'Vul het aantal tickets in.',
                'aantal_tickets_per_tijdslot.integer' => 'Vul een heel aantal tickets in.',
                'aantal_tickets_per_tijdslot.min' => 'Het aantal tickets moet minimaal 1 zijn.',
                'aantal_tickets_per_tijdslot.max' => 'Het aantal tickets is te hoog.',
                'is_actief.required' => 'Kies een status.',
                'is_actief.boolean' => 'Kies een geldige status.',
            ]
        );

        try {
            // =====================================
            // 2. CONTROLEER OF HET TICKET BESTAAT
            // =====================================

            $ticket = DB::table('tickets')
                ->where('id', $id)
                ->first();

            if ($ticket === null) {
                Log::warning('Ticket wijzigen: ticket niet gevonden.', [
                    'ticket_id' => $id,
                ]);

                return redirect()
                    ->route('organisator.tickets.index')
                    ->with(
                        'error',
                        'Het geselecteerde ticket bestaat niet.'
                    );
            }

            // =====================================
            // 3. UNHAPPY SCENARIO: DUBBEL TIJDSLOT
            // =====================================

            // Controleer of een ander ticket dezelfde datum
            // en hetzelfde tijdslot heeft.
            $ticketBestaat = DB::table('tickets')
                ->where('datum', $gegevens['datum'])
                ->where('tijdslot', $gegevens['tijdslot'])
                ->where('id', '!=', $id)
                ->exists();

            if ($ticketBestaat) {
                Log::warning('Ticket wijzigen: dubbele datum en tijdslot.', [
                    'ticket_id' => $id,
                    'datum' => $gegevens['datum'],
                    'tijdslot' => $gegevens['tijdslot'],
                ]);

                // De bestaande gegevens blijven ongewijzigd.
                return redirect()
                    ->route('organisator.tickets.index')
                    ->with(
                        'error',
                        'Er bestaat al een ticket op deze datum en dit tijdslot.'
                    );
            }

            // =====================================
            // 4. JOIN: CONTROLEER VERKOCHTE TICKETS
            // =====================================

            // Gebruik LEFT JOIN om tickets en bestelde_tickets
            // met elkaar te verbinden via het ticket-ID.
            //
            // Ook wanneer er nog geen tickets verkocht zijn,
            // moet het ticket gevonden kunnen worden.

            $verkochteTickets = DB::table('tickets')
                ->leftJoin(
                    'bestelde_tickets',
                    'tickets.id',
                    '=',
                    'bestelde_tickets.ticket_id'
                )
                ->where('tickets.id', $id)
                ->count('bestelde_tickets.id');

            // Registreer de uitkomst van de JOIN.
            Log::info('Ticket wijzigen: verkochte tickets gecontroleerd.', [
                'ticket_id' => $id,
                'aantal_verkocht' => $verkochteTickets,
            ]);

            // =====================================
            // 5. UNHAPPY SCENARIO: AANTAL TE LAAG
            // =====================================

            // Het nieuwe totale aantal tickets mag niet
            // lager zijn dan het aantal verkochte tickets.

            if ($gegevens['aantal_tickets_per_tijdslot'] < $verkochteTickets) {
                Log::warning('Ticket wijzigen: aantal lager dan verkocht.', [
                    'ticket_id' => $id,
                    'aantal_verkocht' => $verkochteTickets,
                    'nieuw_aantal' => $gegevens['aantal_tickets_per_tijdslot'],
                ]);

                // Wijzig niets in de database.
                return redirect()
                    ->route('organisator.tickets.index')
                    ->with(
                        'error',
                        'Het aantal tickets mag niet lager zijn dan het aantal dat al verkocht is.'
                    );
            }

            // =====================================
            // 6. HAPPY SCENARIO: UPDATE
            // =====================================

            // Alle controles zijn geslaagd.
            // Sla de gewijzigde ticketgegevens op.

            DB::table('tickets')
                ->where('id', $id)
                ->update([
                    'datum' => $gegevens['datum'],
                    'tijdslot' => $gegevens['tijdslot'],
                    'tarief' => $gegevens['tarief'],
                    'aantal_tickets_per_tijdslot' => $gegevens['aantal_tickets_per_tijdslot'],
                    'is_actief' => (int) $gegevens['is_actief'],
                    'updated_at' => now(),
                ]);

            // =====================================
            // 7. TECHNISCHE LOGGING
            // =====================================

            // Registreer de succesvolle wijziging.
            Log::info('Ticket succesvol gewijzigd.', [
                'ticket_id' => $id,
                'datum' => $gegevens['datum'],
                'tijdslot' => $gegevens['tijdslot'],
                'tarief' => $gegevens['tarief'],
                'aantal' => $gegevens['aantal_tickets_per_tijdslot'],
                'aantal_verkocht' => $verkochteTickets,
                'is_actief' => $gegevens['is_actief'],
            ]);

            // =====================================
            // 8. SUCCESMELDING
            // =====================================

            return redirect()
                ->route('organisator.tickets.index')
                ->with('success', 'Wijzigingen opgeslagen.');
        } catch (Throwable $fout) {
            // =====================================
            // 9. FOUTAFHANDELING
            // =====================================

            // Bewaar de technische fout in het logbestand.
            Log::error('Ticket wijzigen mislukt.', [
                'ticket_id' => $id,
                'fout' => $fout->getMessage(),
            ]);

            // Toon een duidelijke melding aan de organisator.
            return redirect()
                ->route('organisator.tickets.index')
                ->with(
                    'error',
                    'Het ticket kan momenteel niet worden gewijzigd. Probeer het later opnieuw.'
                );
        }
    }
}
