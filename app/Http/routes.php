<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// api/v1/flights
Route::group(['prefix' => 'api','middleware' => 'api'], function () {
    Route::resource('v1/flights', v1\FlightController::class);
});

