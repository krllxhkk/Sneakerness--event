<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();

        return view('events.index', compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Naam' => 'required|string|max:100',
            'Datum' => 'required|date|after_or_equal:today',
            'Locatie' => 'required|string|max:150',
        ]);

        $bestaatAl = Event::where('Datum', $validated['Datum'])
            ->where('Naam', $validated['Naam'])
            ->exists();

        if ($bestaatAl) {
            return back()->withErrors([
                'Datum' => 'Dit event bestaat al op deze datum.',
            ])->withInput();
        }

        DB::table('Evenement')->insert([
            'Naam' => $validated['Naam'],
            'Datum' => $validated['Datum'],
            'Locatie' => $validated['Locatie'],
            'AantalTicketsPerTijdslot' => 0,
            'BeschikbareStands' => 0,
            'Isactief' => 1,
            'Opmerking' => null,
            'Datumaangemaakt' => Carbon::now(),
            'Datumgewijzigd' => Carbon::now(),
        ]);

        return redirect()
            ->route('events.index')
            ->with('success', 'Event succesvol toegevoegd!');
    }
}
