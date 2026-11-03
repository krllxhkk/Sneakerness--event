<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use App\Providers\Events\Create;
use App\Providers\Events\Index;
use App\Providers\Events\Update;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // Evenementen op de normale website laten zien
    public function index(Index $index)
    {
        return $index->index();
    }

    // Evenementen in het organisator dashboard laten zien
    public function organisator()
    {
        $events = Event::orderBy('Datum')
            ->orderBy('Tijd')
            ->get();

        return view('organisator.events.index', [
            'events' => $events,
        ]);
    }

    // Nieuw evenement opslaan
    public function store(StoreEventRequest $request, Create $create)
    {
        return $create->create($request->validated());
    }

    // Controleer het formulier en laat de provider het evenement wijzigen.
    public function update(Request $request, int $event, Update $update)
    {
        $gegevens = $request->validateWithBag('eventWijzigen', [
            'Naam' => ['required', 'string', 'max:100'],
            'Datum' => ['required', 'date_format:Y-m-d'],
            'Tijd' => ['required', 'date_format:H:i'],
            'Locatie' => ['required', 'string', 'max:150'],
        ], [
            'Naam.required' => 'Vul een naam in.',
            'Naam.max' => 'De naam mag maximaal 100 tekens bevatten.',
            'Datum.required' => 'Vul een datum in.',
            'Datum.date_format' => 'Vul een geldige datum in.',
            'Tijd.required' => 'Vul een tijd in.',
            'Tijd.date_format' => 'Vul een geldige tijd in.',
            'Locatie.required' => 'Vul een locatie in.',
            'Locatie.max' => 'De locatie mag maximaal 150 tekens bevatten.',
        ]);

        return $update->update($event, $gegevens);
    }
}