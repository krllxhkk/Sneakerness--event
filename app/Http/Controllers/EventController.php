<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Providers\Events\Create;
use App\Providers\Events\Index;

class EventController extends Controller
{
    // Laat alle evenementen zien
    public function index(Index $index)
    {
        return $index->index();
    }

    // Stuurt de ingevulde gegevens door om een event toe te voegen
    public function store(StoreEventRequest $request, Create $create)
    {
        return $create->create($request->validated());
    }
}
