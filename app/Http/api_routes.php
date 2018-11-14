<?php

use Illuminate\Http\Request;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user(); 
// });

Route::resource('v1/flights', v1\FlightController::class);