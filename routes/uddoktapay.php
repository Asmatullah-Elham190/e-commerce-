<?php

/*
|--------------------------------------------------------------------------
| Uddoktapay Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Payment\UddoktapayController;

//Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::controller(UddoktapayController::class)->group(function () {
        Route::get('/uddoktapay-configuration', 'credentials_index')->name('uddoktapay.configuration');
    });
});

Route::controller(UddoktapayController::class)->group(function () {
    Route::get('/uddoktapay/pay',  'pay')->name('uddoktapay.pay');
    Route::get('/uddoktapay/return',  'handle')->name('uddoktapay.return');
    Route::get('/uddoktapay/cancel',  'cancel')->name('uddoktapay.cancel');
});
