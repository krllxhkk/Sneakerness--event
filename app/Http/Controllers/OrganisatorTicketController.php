<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrganisatorTicketController extends Controller
{
    /**
     * Toon alle tickets in het overzicht.
     */
    public function index()
    {
        try {
            $tickets = DB::table('tickets')
                ->orderBy('datum')
                ->orderBy('tijdslot')
                ->get();

            $errorMessage = null;
        } catch (Throwable $fout) {
            Log::error('Tickets ophalen mislukt.', [
                'fout' => $fout->getMessage(),
            ]);

            $tickets = collect();
            $errorMessage = 'Tickets kunnen momenteel niet worden opgehaald.';
        }

        return view('organisator.tickets.index', compact('tickets', 'errorMessage'));
    }

    /**
     * Toon het formulier om een ticket toe te voegen.
     */
    public function create()
    {
        return view('organisator.tickets.create');
    }

    /**
     * Controleer de gegevens en sla het ticket op.
     */
    public function store(Request $verzoek)
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

            // Unhappy scenario: voorkom een dubbel ticket.
            if ($ticketBestaat) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Er staat al een ticket gepland op deze datum en tijd'
                    );
            }

            // Voeg het ticket toe aan de bestaande database.
            DB::table('tickets')->insert([
                'datum' => $gegevens['datum'],
                'tijdslot' => $gegevens['tijdslot'],
                'tarief' => $gegevens['tarief'],
                'aantal_tickets_per_tijdslot' => $gegevens['aantal_tickets_per_tijdslot'],
                'is_actief' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Registreer de succesvolle actie.
            Log::info('Ticket succesvol toegevoegd.', [
                'datum' => $gegevens['datum'],
                'tijdslot' => $gegevens['tijdslot'],
            ]);

            return redirect()
                ->route('organisator.tickets.index')
                ->with('success', 'Ticket succesvol toegevoegd.');
        } catch (Throwable $fout) {
            // Registreer de fout voor de ontwikkelaar.
            Log::error('Ticket toevoegen mislukt.', [
                'fout' => $fout->getMessage(),
            ]);

            // Toon een duidelijke melding aan de organisator.
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Het ticket kan momenteel niet worden toegevoegd. Probeer het later opnieuw.'
                );
        }
    }
}
