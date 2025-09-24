<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController as PublicTagController;
use App\Http\Controllers\Api\SkillController as PublicSkillController;

/**
 * Endpoints públicos
 */
Route::get('/profile', [ProfileController::class, 'show']);

// Listado público de contenido
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{project:slug}', [ProjectController::class, 'show']);

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post:slug}', [PostController::class, 'show']);

// Catálogos
Route::get('/tags', [PublicTagController::class, 'index']);
Route::get('/skills', [PublicSkillController::class, 'index']);
