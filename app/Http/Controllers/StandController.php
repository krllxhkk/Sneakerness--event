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
            ->orderBy('Stand.VerhuurdStatus', 'asc')
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
    public function update(Request $request, int $stand, Update $update)
    {
        $gegevens = $request->validateWithBag('standWijzigen', [
            'Standnaam' => ['required', 'string', 'max:100'],
            'Locatie' => ['required', 'string', 'max:100'],
            'Prijs' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'Kwaliteitsklasse' => ['nullable', 'string', 'max:30'],
            'VerhuurdStatus' => ['required', 'boolean'],
            'VerkoperId' => ['nullable', 'integer', 'exists:Verkoper,Id'],
        ], [
            'Standnaam.required' => 'Vul een standnaam in.',
            'Locatie.required' => 'Vul een locatie in.',
            'Prijs.required' => 'Vul een prijs in.',
            'Prijs.numeric' => 'Vul een geldige prijs in.',
            'Prijs.min' => 'De prijs mag niet negatief zijn.',
            'Prijs.max' => 'De prijs is te hoog.',
            'VerhuurdStatus.required' => 'Selecteer een status.',
            'VerkoperId.exists' => 'Selecteer een bestaande verkoper.',
        ]);

        return $update->update($stand, $gegevens);
    }

    // Controleer de bevestiging en laat de provider de stand verwijderen.
    public function destroy(Request $request, int $stand, Delete $delete)
    {
        $request->validateWithBag('standVerwijderen', [
            'bevestiging' => ['required', 'accepted'],
        ], [
            'bevestiging.required' => 'Bevestig eerst het verwijderen.',
            'bevestiging.accepted' => 'Bevestig eerst het verwijderen.',
        ]);

        return $delete->delete($stand);
    }
}