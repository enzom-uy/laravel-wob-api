<?php

use App\Http\Controllers\WobController;
use Illuminate\Support\Facades\Route;

Route::get('/wob', [WobController::class, 'test']);
Route::get('/wob/search', [WobController::class, 'search']);
Route::get('/wob/random', [WobController::class, 'random']);
