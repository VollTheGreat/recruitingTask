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

Route::get('device', 'DeviceController@index')->name('device.index');
Route::put('device', 'DeviceController@store')->name('device.store');
Route::put('device/{id}/approve', 'DeviceController@approve')->name('device.approve');
Route::delete('device/{id}', 'DeviceController@delete')->name('device.delete');
