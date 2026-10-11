<?php

use App\Http\Controllers\ContactpersoonController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MailboxController;
use App\Http\Controllers\OrganisatorDashboardController;
use App\Http\Controllers\OrganisatorTicketController;
use App\Http\Controllers\StandController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\VerkoperController;
use Illuminate\Support\Facades\Route;

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

// Controleer de ingevulde gegevens van de bezoeker.
Route::post('/tickets/gegevens', [TicketController::class, 'gegevensOpslaan'])
    ->name('tickets.gegevens');

// Toon het besteloverzicht van de bezoeker.
Route::get('/tickets/overzicht', [TicketController::class, 'overzicht'])
    ->name('tickets.overzicht');

// Verwerk de bestelling en maak de gekochte tickets aan.
Route::post('/tickets/bestellen', [TicketController::class, 'bestellen'])
    ->name('tickets.bestellen');

// Toon de bevestigingspagina na een succesvolle bestelling.
Route::get('/tickets/bevestiging', [TicketController::class, 'bevestiging'])
    ->name('tickets.bevestiging');

/*
|--------------------------------------------------------------------------
| Mailbox voor bezoekers
|--------------------------------------------------------------------------
*/

// Open een persoonlijk bevestigingsbericht uit de mailbox.
// Dit werkt met en zonder login in dezelfde browsersessie.
Route::get('/mailbox/bericht/{id}', [MailboxController::class, 'show'])
    ->whereNumber('id')
    ->name('mailbox.show');

/*
|--------------------------------------------------------------------------
| Stands
|--------------------------------------------------------------------------
*/

// Toon het overzicht van stands.
Route::get('/stands', [StandController::class, 'index'])
    ->name('stands.index');


// Stand toevoegen
Route::post('/stands', [StandController::class, 'store'])
    ->name('stands.store');

// Stand reserveren als ingelogde verkoper
Route::post('/stands/{stand}/reserveren', [StandController::class, 'reserveren'])
    ->middleware('auth')
    ->whereNumber('stand')
    ->name('stands.reserveren');
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
// Overzicht van verkopers en contactpersonen voor de organisator.
Route::get('/organisator/verkopers', [VerkoperController::class, 'organisator'])
    ->middleware(['auth', 'organisator'])
    ->name('organisator.verkopers');
// Sla een nieuwe verkoper op.
Route::post('/verkopers/toevoegen', [VerkoperController::class, 'store'])
    ->middleware(['auth', 'organisator'])
    ->name('verkopers.store');

/*
|--------------------------------------------------------------------------
| Contactpersonen
|--------------------------------------------------------------------------
*/

// Toon het overzicht van contactpersonen.
Route::get('/contactpersonen', [ContactpersoonController::class, 'index'])
    ->name('contactpersonen.index');

// Sla een nieuwe contactpersoon op.
Route::post('/contactpersonen/toevoegen', [ContactpersoonController::class, 'store'])
    ->middleware(['auth', 'organisator'])
    ->name('contactpersonen.store');

Route::post('/contactpersonen/koppelen', [ContactpersoonController::class, 'koppel'])
    ->middleware(['auth', 'organisator'])
    ->name('contactpersonen.koppel');


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

// Stand reserveren als ingelogde verkoper
Route::post('/stands/{stand}/reserveren', [StandController::class, 'reserveren'])
    ->middleware('auth')
    ->whereNumber('stand')
    ->name('stands.reserveren');


/*
|--------------------------------------------------------------------------
| Evenementen en stands beheren
|--------------------------------------------------------------------------
*/

// Evenementen beheren als organisator
Route::get('/organisator/events', [EventController::class, 'organisator'])
    ->middleware(['auth', 'organisator'])
    ->name('organisator.events');

// Stands beheren als organisator
Route::get('/organisator/stands', [StandController::class, 'organisator'])
    ->middleware(['auth', 'organisator'])
    ->name('organisator.stands');


// Laad de bestaande routes voor authenticatie.
require __DIR__ . '/auth.php';
