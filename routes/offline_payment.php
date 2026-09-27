<?php

use App\Http\Controllers\OfflinePayoutMethodController;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CustomerPackagePaymentController;
use App\Http\Controllers\ManualPaymentMethodController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SellerPackageController;
use App\Http\Controllers\SellerPackagePaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Offline Payment Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Admin
Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin']], function(){
    Route::resource('manual_payment_methods', ManualPaymentMethodController::class);
    Route::get('/manual_payment_methods/destroy/{id}', [ManualPaymentMethodController::class, 'destroy'])->name('manual_payment_methods.destroy');
    
    // Offile Orders
    Route::get('/offline-payment-orders', [OrderController::class, 'all_orders'])->name('offline_payment_orders.index');

    // Wallet Recharge Request
    Route::get('/offline-wallet-recharge-requests', [WalletController::class, 'offline_recharge_request'])->name('offline_wallet_recharge_request.index');
    Route::post('/offline-wallet-recharge/approved', [WalletController::class, 'updateApproved'])->name('offline_recharge_request.approved');
    Route::get('/requests-filter', [WalletController::class, 'filter_request'])->name('requests.filter');
    Route::post('/bulk-wallet-requests-approve', [WalletController::class, 'bulk_approve'])->name('bulk-wallet-requests-approve');

    // Seller Package purchase request
    Route::get('/offline-seller-package-payment-requests', [SellerPackagePaymentController::class, 'offline_payment_request'])->name('offline_seller_package_payment_request.index');
    Route::post('/offline-seller-package-payment/approved', [SellerPackagePaymentController::class, 'offline_payment_approval'])->name('offline_seller_package_payment.approved');

    // customer package purchase request
    Route::get('/offline-customer-package-payment-requests', [CustomerPackagePaymentController::class, 'offline_payment_request'])->name('offline_customer_package_payment_request.index');
    Route::post('/offline-customer-package-payment/approved', [CustomerPackagePaymentController::class, 'offline_payment_approval'])->name('offline_customer_package_payment.approved');
    Route::post('/wallet-recharge/modal', [ManualPaymentMethodController::class, 'wallet_recharge_modal'])->name('admin_offline_wallet_recharge_modal');
    Route::post('/wallet-recharge/make-payment', [ManualPaymentMethodController::class, 'wallet_recharge_make_payment'])->name('admin_wallet_recharge.make_payment');

    // Refund Method For Customer
    Route::controller(OfflinePayoutMethodController::class)->group(function () {
        Route::get('/refund-method-for-customer-index', 'index')->name('payout_method_for_customer_index');
        Route::get('/refund-method-for-customer-create', 'create')->name('payout_method_for_customer_create');
        Route::post('/refund-method-for-customer-store', 'store')->name('payout_method_for_customer_store');
        Route::get('/refund-method-for-customer-filter', 'filter')->name('payout_method_for_customer_filter');
        Route::get('/refund-method-for-customer-edit/{id}', 'edit')->name('payout_method_for_customer_edit');
        Route::post('/refund-method-for-customer-update/{id}', 'update')->name('payout_method_for_customer_update');
        Route::get('/refund-method-for-customer-delete/{id}', 'delete')->name('payout_method_for_customer_delete');
        Route::post('/refund-method-for-customer-bulk-delete', 'bulk_delete')->name('payout_method_for_customer_bulk_delete');
    });
});

//FrontEnd
Route::post('/purchase_history/make_payment/submit', [ManualPaymentMethodController::class, 'submit_offline_payment'])->name('purchase_history.make_payment');
Route::post('/offline-wallet-recharge-modal', [ManualPaymentMethodController::class, 'offline_recharge_modal'])->name('offline_wallet_recharge_modal');

Route::group(['middleware' => ['user', 'verified']], function(){
	Route::post('/offline-wallet-recharge', [WalletController::class, 'offline_recharge'])->name('wallet_recharge.make_payment');
    Route::get('/payment-receipt/show/{id}', [ManualPaymentMethodController::class, 'payment_receipt_show'])->name('payment_receipt.show');
});

// customer package purchase
Route::post('/offline-customer-package-purchase-modal', [ManualPaymentMethodController::class, 'offline_customer_package_purchase_modal'])->name('offline_customer_package_purchase_modal');
Route::post('/offline-customer-package-paymnet', [CustomerPackageController::class, 'purchase_package_offline'])->name('customer_package.make_offline_payment');

// Order Re-Payments
Route::post('/offline-order-re-payment-modal', [ManualPaymentMethodController::class, 'offline_order_re_payment_modal'])->name('offline_order_re_payment_modal');

Route::group(['prefix' => 'seller', 'middleware' => ['seller', 'verified', 'user'], 'as' => 'seller.'], function () {
    // Seller Package purchase
    Route::post('/offline-seller-package-purchase-modal', [ManualPaymentMethodController::class, 'offline_seller_package_purchase_modal'])->name('offline_seller_package_purchase_modal');
    Route::post('/offline-seller-package-paymnet',[SellerPackageController::class, 'purchase_package_offline'])->name('make_offline_payment');
});

