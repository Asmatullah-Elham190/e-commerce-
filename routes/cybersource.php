<?php


//Admin

use App\Http\Controllers\Cybersource\CybersourceSettingController;

Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin']], function(){
    Route::controller(CybersourceSettingController::class)->group(function () {
        Route::get('/cybersource-configuration', 'configuration')->name('cybersource_configuration');
    });
});