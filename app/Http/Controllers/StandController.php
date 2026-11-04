<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStandRequest;
use App\Models\Stand;
use App\Providers\Stands\Create;
use App\Providers\Stands\Index;
use App\Providers\Stands\Reservering;
use Illuminate\Http\Request;
use App\Models\Verkoper;
use App\Providers\Stands\Update;
use App\Providers\Stands\Delete;
use App\Http\Requests\DeleteStandRequest;
use App\Http\Requests\UpdateStandRequest;

class StandController extends Controller
{
    // Stands op de normale website laten zien
    public function index(Index $index)
    {
        return $index->index();
    }

    // Stands in het organisator dashboard laten zien
// Haal de stands en verkopers op voor het beheeroverzicht.

    // Haal de stands met hun verkoper op voor het wijzigoverzicht.
    public function organisator()
    {
        $stands = Stand::query()
            ->leftJoin('Verkoper', 'Stand.VerkoperId', '=', 'Verkoper.Id')
            ->select('Stand.*', 'Verkoper.Naam as VerkoperNaam')
            ->orderByDesc('Stand.Id')
            ->get();

        $verkopers = Verkoper::orderBy('Naam')->get();

        return view('organisator.stands.index', [
            'stands' => $stands,
            'verkopers' => $verkopers,
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


    // Controleer het wijzigformulier en laat de provider de stand opslaan.
// Geef de gecontroleerde standgegevens door aan de provider.
public function update(UpdateStandRequest $request, int $stand, Update $update)
{
    return $update->update($stand, $request->validated());
}

    // Controleer de bevestiging en laat de provider de stand verwijderen.
// De Request controleert de bevestiging voordat de provider wordt aangeroepen.
public function destroy(DeleteStandRequest $request, int $stand, Delete $delete)
{
    return $delete->delete($stand);
}
}