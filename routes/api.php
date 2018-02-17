<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// ROOM *********************************************

Route::post('rooms', ['uses' => 'RoomController@store','middleware'=>'auth.basic']);

Route::put('rooms', ['uses' => 'RoomController@update','middleware'=>'auth.basic']);

Route::get('rooms', ['uses' => 'RoomController@show','middleware'=>'auth.basic']);

Route::get('rooms/{id}', ['uses' => 'RoomController@showSelected','middleware'=>'auth.basic']);


// EVENT *********************************************

Route::post('events', ['uses' => 'EventController@store','middleware'=>'auth.basic']);