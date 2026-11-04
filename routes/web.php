
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

// Stand toevoegen.
Route::post('/stands', [StandController::class, 'store'])
    ->name('stands.store');

// Stand reserveren als ingelogde verkoper.
Route::post('/stands/{stand}/reserveren', [StandController::class, 'reserveren'])
    ->middleware('auth')
    ->whereNumber('stand')
    ->name('stands.reserveren');

// Bestaande stand wijzigen als organisator
Route::put('/organisator/stands/{stand}', [StandController::class, 'update'])
    ->middleware(['auth', 'organisator'])
    ->whereNumber('stand')
    ->name('stands.update');

// Alleen een organisator mag een stand verwijderen.
Route::delete('/organisator/stands/{stand}', [StandController::class, 'destroy'])
    ->middleware(['auth', 'organisator'])
    ->whereNumber('stand')
    ->name('stands.destroy');
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


// Een organisator mag bestaande evenementen wijzigen.
Route::put('/organisator/events/{event}', [EventController::class, 'update'])
    ->middleware(['auth', 'organisator'])
    ->whereNumber('event')
    ->name('events.update');

// Alleen organisatoren mogen evenementen verwijderen.
Route::delete('/organisator/events/{event}', [EventController::class, 'destroy'])
    ->middleware(['auth', 'organisator'])
    ->whereNumber('event')
    ->name('events.destroy');
/*
|--------------------------------------------------------------------------
| Verkopers
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Verkopers
|--------------------------------------------------------------------------
*/

// Toon het overzicht van verkopers.
Route::get('/verkopers', [VerkoperController::class, 'index'])
    ->name('verkopers.index');

// Overzicht voor de organisator.
Route::get('/organisator/verkopers', [VerkoperController::class, 'organisator'])
    ->middleware(['auth', 'organisator'])
    ->name('organisator.verkopers');

// Sla een nieuwe verkoper op.
Route::post('/verkopers/toevoegen', [VerkoperController::class, 'store'])
    ->middleware(['auth', 'organisator'])
    ->name('verkopers.store');

// Wijzig een bestaande verkoper.
Route::put('/verkopers/{id}', [VerkoperController::class, 'update'])
    ->middleware(['auth', 'organisator'])
    ->whereNumber('id')
    ->name('verkopers.update');

// Verwijder een verkoper.
Route::delete('/verkopers/{id}', [VerkoperController::class, 'destroy'])
    ->middleware(['auth', 'organisator'])
    ->whereNumber('id')
    ->name('verkopers.destroy');

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

// Koppel een contactpersoon.
Route::post('/contactpersonen/koppelen', [ContactpersoonController::class, 'koppel'])
    ->middleware(['auth', 'organisator'])
    ->name('contactpersonen.koppel');
// Wijzig een bestaande contactpersoon
Route::put('/contactpersonen/{id}', [ContactpersoonController::class, 'update'])
    ->middleware(['auth', 'organisator'])
    ->whereNumber('id')
    ->name('contactpersonen.update');

// Verwijder een contactpersoon
Route::delete('/contactpersonen/{id}', [ContactpersoonController::class, 'destroy'])
    ->middleware(['auth', 'organisator'])
    ->whereNumber('id')
    ->name('contactpersonen.destroy');

// Koppel een contactpersoon los van een verkoper
Route::delete('/contactpersonen/{id}/loskoppelen', [ContactpersoonController::class, 'loskoppelen'])
    ->middleware(['auth', 'organisator'])
    ->whereNumber('id')
    ->name('contactpersonen.loskoppelen');

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

// =====================================
// SPRINT 3 - TICKET WIJZIGEN (UPDATE)
// =====================================

// Wijzig de gegevens van een bestaand ticket.
Route::put('/organisator/tickets/{id}', [OrganisatorTicketController::class, 'update'])
    ->middleware(['auth', 'organisator'])
    ->whereNumber('id')
    ->name('organisator.tickets.update');

// =====================================
// SPRINT 3 - TICKET ANNULEREN (DELETE)
// =====================================

// Annuleer een bestaand ticket.
// Alleen ingelogde organisatoren mogen tickets annuleren.
Route::delete('/organisator/tickets/{id}', [OrganisatorTicketController::class, 'destroy'])
    ->middleware(['auth', 'organisator'])
    ->whereNumber('id')
    ->name('organisator.tickets.destroy');

/*
|--------------------------------------------------------------------------
| Inloggen en registreren
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Evenementen en stands beheren
|--------------------------------------------------------------------------
*/

// Evenementen beheren als organisator.
Route::get('/organisator/events', [EventController::class, 'organisator'])
    ->middleware(['auth', 'organisator'])
    ->name('organisator.events');

// Stands beheren als organisator.
Route::get('/organisator/stands', [StandController::class, 'organisator'])
    ->middleware(['auth', 'organisator'])
    ->name('organisator.stands');

// Laad de bestaande routes voor authenticatie.
require __DIR__ . '/auth.php';
