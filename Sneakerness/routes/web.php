<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StandController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\VerkoperController;
use App\Http\Controllers\ContactpersoonController;
use App\Http\Controllers\TicketController;

// Homepagina
Route::get('/', [HomeController::class, 'index'])->name('home');

// Tickets stappenpagina (Kies dag / Kies tijdslot)
Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');

// Stands overzicht
Route::get('/stands', [StandController::class, 'index'])
    ->name('stands.index');

// Events overzicht
Route::get('/events', [EventController::class, 'index'])
    ->name('events.index');

// Verkopers overzicht
Route::get('/verkopers', [VerkoperController::class, 'index'])
    ->name('verkopers.index');

// Contactpersonen overzicht
Route::get('/contactpersonen', [ContactpersoonController::class, 'index'])
    ->name('contactpersonen.index');