<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStandRequest;
use App\Models\Stand;
use App\Providers\Stands\Create;
use App\Providers\Stands\Index;
use App\Providers\Stands\Reservering;
use Illuminate\Http\Request;


class StandController extends Controller
{
    // Stands op de normale website laten zien
    public function index(Index $index)
    {
        return $index->index();
    }

    // Stands in het organisator dashboard laten zien
    public function organisator()
    {
        $stands = Stand::with('verkoper')
            ->orderBy('VerhuurdStatus', 'asc')
            ->get();

        return view('organisator.stands.index', [
            'stands' => $stands,
        ]);
    }

    // Nieuwe stand opslaan
    public function store(StoreStandRequest $request, Create $create)
    {
        return $create->create($request->validated());
    }

    // Verwerk de reservering van een stand
    public function reserveren(Request $request, int $stand, Reservering $reservering)
    {
        return $reservering->reserveren($request, $stand);
    }
}