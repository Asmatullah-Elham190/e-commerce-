<?php

/*
|--------------------------------------------------------------------------
| Steadfast Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\SteadfastController;

//Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::controller(SteadfastController::class)->group(function () {
        Route::post('/steadfast-settings-update', 'steadfast_update')->name('steadfast.update');
        Route::post('/steadfast/order-create', 'createOrder')->name('steadfast.create.order');
    });
});

Route::controller(SteadfastController::class)->group(function () {
    //for cron job
    Route::get('/steadfast/delivery-status', 'deliveryStatus')->name('steadfast.delivery-status');
});