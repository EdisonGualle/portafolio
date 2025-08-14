<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/**
 * Endpoints públicos
 */
Route::get('/profile', [ProfileController::class, 'show']);

/**
 * Endpoints protegidos
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/profile/{id}', [ProfileController::class, 'update']);
});
