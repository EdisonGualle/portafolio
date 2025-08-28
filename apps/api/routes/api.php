<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

/**
 * Endpoints públicos
 */
Route::get('/profile', [ProfileController::class, 'show']);
