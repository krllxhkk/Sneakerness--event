
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StandController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\VerkoperController;
use App\Http\Controllers\ContactpersoonController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\OrganisatorDashboardController;
use App\Http\Controllers\OrganisatorTicketController;

/*
|--------------------------------------------------------------------------
| Homepagina
|--------------------------------------------------------------------------
*/

// Toon de homepagina.
Route::get('/', [HomeController::class, 'index'])
    ->name('home');

/*
|--------------------------------------------------------------------------
| Tickets voor bezoekers
|--------------------------------------------------------------------------
*/

// Toon de stappenpagina voor het kiezen van een dag en tijdslot.
Route::get('/tickets', [TicketController::class, 'index'])
    ->name('tickets.index');

/*
|--------------------------------------------------------------------------
| Stands
|--------------------------------------------------------------------------
*/

// Toon het overzicht van stands.
Route::get('/stands', [StandController::class, 'index'])
    ->name('stands.index');

/*
|--------------------------------------------------------------------------
| Evenementen
|--------------------------------------------------------------------------
*/

// Toon het overzicht van evenementen.
Route::get('/events', [EventController::class, 'index'])
    ->name('events.index');

// Sla een evenement op.
Route::post('/events', [EventController::class, 'store'])
    ->name('events.store');

/*
|--------------------------------------------------------------------------
| Verkopers
|--------------------------------------------------------------------------
*/

// Toon het overzicht van verkopers.
Route::get('/verkopers', [VerkoperController::class, 'index'])
    ->name('verkopers.index');

/*
|--------------------------------------------------------------------------
| Contactpersonen
|--------------------------------------------------------------------------
*/

// Toon het overzicht van contactpersonen.
Route::get('/contactpersonen', [ContactpersoonController::class, 'index'])
    ->name('contactpersonen.index');

/*
|--------------------------------------------------------------------------
| Organisator Dashboard
|--------------------------------------------------------------------------
*/

// Alleen ingelogde organisatoren mogen het dashboard bekijken.
Route::get('/organisator/dashboard', [OrganisatorDashboardController::class, 'index'])
    ->middleware(['auth', 'organisator'])
    ->name('organisator.dashboard');

/*
|--------------------------------------------------------------------------
| Tickets beheren door de organisator
|--------------------------------------------------------------------------
*/

// Toon het ticketoverzicht.
Route::get('/organisator/tickets', [OrganisatorTicketController::class, 'index'])
    ->middleware(['auth', 'organisator'])
    ->name('organisator.tickets.index');

// Toon het formulier om een ticket toe te voegen.
Route::get('/organisator/tickets/create', [OrganisatorTicketController::class, 'create'])
    ->middleware(['auth', 'organisator'])
    ->name('organisator.tickets.create');

// Sla een nieuw ticket op in de database.
Route::post('/organisator/tickets', [OrganisatorTicketController::class, 'store'])
    ->middleware(['auth', 'organisator'])
    ->name('organisator.tickets.store');

/*
|--------------------------------------------------------------------------
| Inloggen en registreren
|--------------------------------------------------------------------------
*/

// Laad de bestaande routes voor authenticatie.
require __DIR__ . '/auth.php';
