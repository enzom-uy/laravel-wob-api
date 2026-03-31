<?php

use App\Http\Controllers\WobController;
use Illuminate\Support\Facades\Route;

Route::get('/wob/search', [WobController::class, 'search']);
Route::get('/wob/random', [WobController::class, 'random']);

Route::post('/wob', [WobController::class, 'insert']);
