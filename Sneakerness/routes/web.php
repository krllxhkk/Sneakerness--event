<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
<<<<<<< HEAD
use App\Http\Controllers\StandController;
use App\Http\Controllers\EventController;

// Voeg jouw route toe voor de homepagina:
Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/stands', [StandController::class, 'index'])
    ->name('stands.index');

Route::get('/events', [EventController::class, 'index'])
    ->name('events.index');
=======
use App\Http\Controllers\VerkoperController;

// Voeg jouw route toe voor de homepagina:
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/verkopers', [VerkoperController::class, 'index'])
    ->name('verkopers.index');
>>>>>>> a01db18 (Finish verkopers overview)
