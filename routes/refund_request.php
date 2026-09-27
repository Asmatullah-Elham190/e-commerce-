<?php

/*
|--------------------------------------------------------------------------
| Refund System Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Admin Panel

use App\Http\Controllers\RefundReasonController;
use App\Http\Controllers\RefundRequestController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::controller(RefundRequestController::class)->group(function () {
        Route::get('/refund-request-all', 'admin_index')->name('refund_requests_all');
        Route::get('/dispute-refund-request-all', 'admin_dispute_index')->name('dispute_refund_requests_all');
        Route::get('/refund-request-config', 'refund_config')->name('refund_time_config');
        Route::post('/admin/reject-refund-request', 'reject_refund_request')->name('admin.reject_refund_request');
        Route::post('/refund-request-pay', 'refund_pay')->name('refund_request_money_by_admin');
        Route::post('/dispute-refund-request-pay', 'dispute_refund_pay')->name('dispute_refund_request_money_by_admin');
        Route::post('/refund-request-time-store', 'refund_time_update')->name('refund_request_time_config');
        Route::post('/refund-request-sticker-store', 'refund_sticker_update')->name('refund_sticker_config');
        Route::get('/categories-wise-product-refund', 'categoriesWiseProductRefund')->name('categories_wise_product_refund');
        Route::get('/categories-filter', 'filter_categories')->name('refund_categories.filter');
        Route::post('/categories/update-refund-settings',  'updateRefundSettings')->name('categories.update-refund-settings');
        Route::post('/admin/products/check-refundable-category', 'checkRefundableCategory')->name('admin.products.check_refundable_category');
        Route::post('/offline-refund-request-pay', 'refund_offline_pay')->name('refund_request_offline_money_by_admin');
        Route::post('/offline-dispute-refund-request-pay', 'dispute_refund_offline_pay')->name('dispute_refund_request_offline_money_by_admin');
        Route::get('/refund-requests-filter', 'filter_refund_request')->name('refund_requests.filter');
        Route::get('/dispute-refund-requests-filter', 'filter_dispute_refund_request')->name('dispute_refund_requests.filter');
        Route::post('/payment-info-modal', 'payment_info_modal')->name('view_payment_info_modal');
        Route::post('/refund-request-view', 'refund_request_view')->name('refund_request_view');
        Route::post('/dispute-refund-request-time-store', 'dispute_refund_time_update')->name('dispute_refund_request_time_config');
        Route::post('/bulk-refund-days-assign', 'updateBulkRefundDaysAssign')->name('categories.bulk-refund-days-assign');    
    });
    Route::controller(RefundReasonController::class)->group(function () {
        Route::get('/refund-reason-index', 'index')->name('refund_reason_index');
        Route::get('/refund-reason-create', 'create')->name('refund_reason_create');
        Route::post('/refund-reason-store', 'store')->name('refund_reason_store');
        Route::get('/refund-reason-filter', 'filter')->name('refund_reason_filter');
        Route::get('/refund-reason-edit/{id}', 'edit')->name('refund_reason_edit');
        Route::post('/refund-reason-update/{id}', 'update')->name('refund_reason_update');
        Route::post('/refund-reason-store-ajax', 'storeAjax')->name('refund.reason.store.ajax');
        Route::post('/refund-reason-update-status', 'update_status')->name('refund_reason.update-status');
        Route::post('/refund-reason-bulk-update', 'bulk_update')->name('refund_reason_bulk_update');
    });
});


//FrontEnd User panel
Route::group(['middleware' => ['user', 'verified']], function () {
    Route::controller(RefundRequestController::class)->group(function () {
        Route::post('refund-request-send/{id}', 'request_store')->name('refund_request_send');
        Route::post('dispute-refund-request-send/{id}', 'dispute_request_store')->name('dispute_refund_request_send');
        Route::get('sent-refund-request', 'customer_index')->name('customer_refund_request');
        Route::get('refund-request/{id}', 'refund_request_send_page')->name('refund_request_send_page');
        Route::get('dispute-refund-request/{id}', 'dispute_refund_request_send_page')->name('dispute_refund_request_send_page');
        Route::post('/customer-refund-request-view', 'refund_request_view')->name('customer_refund_request_view');
    });
});


//Seller panel
Route::group(['middleware' => ['seller', 'user', 'verified']], function () {
    Route::controller(RefundRequestController::class)->group(function () {
        Route::get('/seller/refund-request', 'vendor_index')->name('seller.vendor_refund_request');
        Route::get('/seller/refund-request-filter', 'seller_filter')->name('seller.refund_requests.filter');
        Route::get('/seller/refund-configuration', 'seller_refund_configuration')->name('seller.refund_configuration');
        Route::get('/categories-wise-product-refund', 'sellerCategoriesWiseProductRefund')->name('seller.categories_wise_product_refund');
        Route::post('seller/refund-reuest-vendor-approval', 'request_approval_vendor')->name('seller.vendor_refund_approval');
        Route::post('/seller/reject-refund-request', 'reject_refund_request')->name('seller.reject_refund_request');
        Route::post('/seller/refund-request-view', 'refund_request_view')->name('seller.refund_request_view');
        Route::post('/seller/products/check-refundable-category', 'checkSellerRefundableCategory')->name('seller.products.check_refundable_category');
        Route::post('/seller/offline-refund-request-pay', 'refund_offline_pay')->name('refund_request_offline_money_by_seller');
        Route::post('/seller/refund-request-pay', 'refund_pay')->name('refund_request_money_by_seller');
        Route::post('/seller/payment-info-modal', 'payment_info_modal')->name('seller.view_payment_info_modal');
        Route::get('/seller/refund-categories-filter', 'seller_filter_categories')->name('seller.refund_categories.filter');
    });
});
