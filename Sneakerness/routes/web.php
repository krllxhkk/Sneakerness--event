<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StandController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\VerkoperController;
use App\Http\Controllers\ContactpersoonController;

// Homepagina
Route::get('/', [HomeController::class, 'index'])->name('home');

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