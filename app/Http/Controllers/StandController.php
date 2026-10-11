<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStandRequest;
use App\Providers\Stands\Create;
use App\Providers\Stands\Index;

class StandController extends Controller
{
    // Laat alle stands zien
    public function index(Index $index)
    {
        return $index->index();
    }

    // Stuurt de ingevulde gegevens door om een stand toe te voegen
    public function store(StoreStandRequest $request, Create $create)
    {
        return $create->create($request->validated());
    }
}
