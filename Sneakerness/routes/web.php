<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactpersoonController;
// Voeg jouw route toe voor de homepagina:
Route::get('/', [HomeController::class, 'index'])->name('home');
// Voeg jouw route toe voor de contactpersonen:
Route::get('/contactpersonen', [ContactpersoonController::class, 'index'])->name('contactpersonen');