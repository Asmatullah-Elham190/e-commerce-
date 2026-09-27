<?php

/*
|--------------------------------------------------------------------------
| Redx Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\RedxController;

//Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::controller(RedxController::class)->group(function () {
        Route::post('/redx-settings-update', 'redx_update')->name('redx.update');
        Route::get('/redx-pickup-store/index', 'index')->name('redx_pickup.index');
        Route::get('/redx-pickup-store/filter', 'filter')->name('redx_pickup.filter');
        Route::get('/redx-pickup-store/create', 'create')->name('redx_pickup.create');
        Route::post('/redx-pickup-store/store', 'store')->name('redx_pickup.store');
        Route::get('/get/redx-pickup-store', 'fetch')->name('redx_pickup.fetch');
        Route::get('/redx-all-pickup-storeAndDeliveryArea', 'allPickupStoreAndDeliveryArea')->name('redx.all_pickup_store.delivery_area');
        Route::post('/redx-order-create', 'orderCreate')->name('redx_order.create');
    });
});

Route::controller(RedxController::class)->group(function () {
    //cron job
    Route::get('/redx-order-status-fetch', 'statusFetch')->name('redx_status.fetch');
});