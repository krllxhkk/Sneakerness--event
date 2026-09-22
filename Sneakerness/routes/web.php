<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VerkoperController;

// Voeg jouw route toe voor de homepagina:
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/verkopers', [VerkoperController::class, 'index'])
    ->name('verkopers.index');