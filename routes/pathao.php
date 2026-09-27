<?php

/*
|--------------------------------------------------------------------------
| Pathao Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\PathaoController;

//Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::controller(PathaoController::class)->group(function () {
        Route::post('/pathao-settings-update', 'pathao_update')->name('pathao.update');
        Route::get('/pathao/all-store', 'allStore')->name('pathao.all.store');
        Route::post('/pathao/order-create', 'createOrder')->name('pathao.create.order');
    });
});

Route::controller(PathaoController::class)->group(function () {
    //for cron job
    Route::get('/pathao/delivery-status', 'deliveryStatus')->name('pathao.delivery-status');
});