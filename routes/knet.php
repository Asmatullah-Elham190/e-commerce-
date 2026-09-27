<?php

/*
|--------------------------------------------------------------------------
| Knet Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Payment\KnetController;

//Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::controller(KnetController::class)->group(function () {
        Route::get('/knet-configuration', 'credentials_index')->name('knet.configuration');
    });
});

Route::controller(KnetController::class)->group(function () {
    Route::get('/knet/pay', 'pay')->name('knet.pay');
    Route::get('/knet/callback',  'callback')->name('knet.callback');
});
