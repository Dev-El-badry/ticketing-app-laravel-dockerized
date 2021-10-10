<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\MovieController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\ShowTimeController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\ReservationController;
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


Route::get('/hi/val', function() {
    return response()->json(['message' => 'hi there'], 201);
});

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'App\Http\Controllers\Auth\AuthController@login');
    
    Route::group(['middleware'=> ['auth.jwt']], function() {
        Route::post('logout', 'App\Http\Controllers\Auth\AuthController@logout');
        Route::post('refresh', 'App\Http\Controllers\Auth\AuthController@refresh');
        Route::post('me', 'App\Http\Controllers\Auth\AuthController@me');
    });
});

Route::group(['middleware'=> ['auth.jwt']], function() {
    Route::resource('movies', MovieController::class, ['except'=>'create', 'edit']);
    Route::resource('halls', HallController::class, ['except'=>'create', 'edit']);
    Route::resource('seats', SeatController::class, ['except'=>'create', 'edit']);
    Route::resource('reservations', ReservationController::class, ['except'=>'create', 'edit']);
    Route::resource('showTimes', ShowTimeController::class, ['except'=>'create', 'edit']);
    
    Route::get('showTimes/show/{movieId}', 'App\Http\Controllers\ShowTimeController@getShowTimes');
    Route::get('seats/get-seats/hall/{hallId}/show/{showId}', 'App\Http\Controllers\SeatController@getSeatsByHallIdAndShowId');
});







