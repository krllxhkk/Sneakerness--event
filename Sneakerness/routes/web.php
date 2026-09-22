<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StandController;

// Voeg jouw route toe voor de homepagina:
Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/stands', [StandController::class, 'index'])
    ->name('stands.index');