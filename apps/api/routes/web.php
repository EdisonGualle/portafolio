<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreviewController;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/liquidaciones', 'app')->name('liquidaciones.index');

Route::get('/preview/{token}', [PreviewController::class, 'show'])
    ->name('preview.show'); // sin auth, noindex en la vista