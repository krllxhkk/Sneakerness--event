<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Voeg jouw route toe voor de homepagina:
Route::get('/', [HomeController::class, 'index'])->name('home');