<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use App\Providers\Events\Create;
use App\Providers\Events\Index;
use App\Providers\Events\Update;
use App\Providers\Events\Delete;
use App\Http\Requests\DeleteEventRequest;
use App\Http\Requests\UpdateEventRequest;

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
        // Toon de laatst toegevoegde evenementen bovenaan.
        $events = Event::orderByDesc('Id')->get();

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
    public function update(UpdateEventRequest $request, int $event, Update $update)
    {
        return $update->update($event, $request->validated());
    }

    // Controleer de bevestiging en laat de provider het event verwijderen.
    // De Request controleert de bevestiging voordat de provider wordt aangeroepen.

    public function destroy(DeleteEventRequest $request, int $event, Delete $delete)
    {
        return $delete->delete($event);
    }
}