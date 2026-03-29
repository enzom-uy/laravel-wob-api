<?php

use App\Http\Controllers\WobController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return 'Hello world';
});


Route::get('/wob', [WobController::class, 'test']);
Route::post('/wob/search', [WobController::class, 'search']);
