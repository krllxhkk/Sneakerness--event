<?php

namespace App\Providers\Events;

use App\Models\Event;

class Index
{
    public function index()
    {
        // Haal alle evenementen op en sorteer op datum en tijd
        $events = Event::orderBy('Datum')
            ->orderBy('Tijd')
            ->get();

        return view('events.index', [
            'events' => $events,
        ]);
    }
}
