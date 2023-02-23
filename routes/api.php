<?php

use App\Http\Controllers\DogEventController;
use App\Http\Controllers\HorseEventController;
use App\Http\Controllers\MotorraceEventController;
use App\Http\Controllers\WinandplaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::resource('motorrace',MotorraceEventController::class);
Route::resource('dog',DogEventController::class);
Route::resource('horse',HorseEventController::class);
//Route::resource('market',MarketController::class);
Route::resource('winandplace',WinandplaceController::class);
