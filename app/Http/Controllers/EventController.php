<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use App\Providers\Events\Create;
use App\Providers\Events\Index;

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
}