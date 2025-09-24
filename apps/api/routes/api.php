<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SeriesController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\TagController;

/**
 * Endpoints públicos
 */
Route::get('/profile', [ProfileController::class, 'show']);

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post:slug}', [PostController::class, 'show']);

Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{project:slug}', [ProjectController::class, 'show']);

Route::get('/skills', [SkillController::class, 'index']);
Route::get('/skills/{skill}', [SkillController::class, 'show']);

Route::get('/tags', [TagController::class, 'index']);
Route::get('/tags/{tag:slug}', [TagController::class, 'show']);

Route::get('/series', [SeriesController::class, 'index']);
Route::get('/series/{series:slug}', [SeriesController::class, 'show']);
