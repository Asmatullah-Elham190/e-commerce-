<?php

/*
|--------------------------------------------------------------------------
| Shiprocket Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\ShiprocketController;

//Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::controller(ShiprocketController::class)->group(function () {
        Route::post('/shiprocket-settings-update', 'update')->name('shiprocket_settings.update');
        Route::post('/orders/confirm-shiprocket-info', 'createOrderShiprocket')->name('orders.confirm_shiprocket_info');
        Route::post('shiprocket/couriers',  'getCouriers')->name('shiprocket.couriers');
        Route::post('shiprocket/assign-awb', 'assignAWB')->name('shiprocket.assign.awb');
        Route::get('shiprocket/download-label/{order}',  'downloadLabel')->name('shiprocket.download.label');
        Route::get('shiprocket/download-manifest/{order}',  'downloadManifest')->name('shiprocket.download.manifest');
        Route::post('/shiprocket/request-pickup','requestPickup')->name('shiprocket.request.pickup');

    });
});

Route::controller(ShiprocketController::class)->group(function () {
    // for cron job of shiprocket delivery status
    Route::get('/shiprocket/delivery-status', 'deliveryStatus')->name('shiprocket.delivery-status');
    Route::get('/shiprocket/order-status', 'orderStatus')->name('shiprocket.order-status');
});