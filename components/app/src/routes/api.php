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

Route::prefix('device')->group(function () {
    Route::get('/', 'DeviceController@index')->name('device.index');
    Route::put('/', 'DeviceController@store')->name('device.store');
    Route::put('{id}/approve', 'DeviceController@approve')->name('device.approve');
    Route::delete('{id}', 'DeviceController@delete')->name('device.delete');
});

