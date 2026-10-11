<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MailboxController extends Controller
{
    /**
     * Toon alleen een mailboxbericht van de juiste gebruiker.
     */
    public function show(Request $request, int $id): View|RedirectResponse
    {
        $gebruiker = $request->user();

        // Ingelogde gebruikers: controleer de eigenaar in de database.
        if ($gebruiker !== null) {
            $eigenBestelling = DB::table('bestellingen')
                ->where('id', $id)
                ->where('user_id', $gebruiker->id)
                ->exists();

            if (! $eigenBestelling) {
                return redirect()
                    ->route('home')
                    ->with('error', 'Dit bericht is niet beschikbaar.');
            }
        } else {
            // Gasten: alleen de laatst bevestigde bestelling
            // van deze browsersessie mag geopend worden.
            $bevestiging = $request->session()->get('ticket_bevestiging');

            if (
                ! is_array($bevestiging)
                || (int) ($bevestiging['id'] ?? 0) !== $id
            ) {
                return redirect()
                    ->route('home')
                    ->with('error', 'Dit bericht is niet beschikbaar.');
            }

            // Controleer dat het echt een gastbestelling is.
            $gastBestelling = DB::table('bestellingen')
                ->where('id', $id)
                ->whereNull('user_id')
                ->exists();

            if (! $gastBestelling) {
                return redirect()
                    ->route('home')
                    ->with('error', 'Dit bericht is niet beschikbaar.');
            }
        }

        // Haal het bericht uit de sessie.
        $berichten = $request->session()->get('ticket_mailbox', []);

        foreach ($berichten as $index => $bericht) {
            if ((int) ($bericht['id'] ?? 0) === $id) {
                $berichten[$index]['gelezen'] = true;

                $request->session()->put('ticket_mailbox', $berichten);

                return view('mailbox.show', [
                    'bericht' => $berichten[$index],
                ]);
            }
        }

        return redirect()
            ->route('home')
            ->with('error', 'Dit bericht is niet beschikbaar.');
    }
}