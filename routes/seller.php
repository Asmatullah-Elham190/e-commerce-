<?php

use App\Http\Controllers\AizUploadController;
use App\Http\Controllers\Seller\CustomLabelController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\GSTController;
use App\Http\Controllers\Seller\NoteController;
use App\Http\Controllers\Seller\PaymentController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\PromotionalProductController;
use App\Http\Controllers\Seller\SellerHubController;
use App\Http\Controllers\Seller\ShopController;
use App\Http\Controllers\Seller\SizeChartController;
use Illuminate\Support\Facades\Route;

//Upload
Route::group(['prefix' => 'seller', 'middleware' => ['seller', 'verified', 'user', 'prevent-back-history'], 'as' => 'seller.'], function () {
    Route::controller(AizUploadController::class)->group(function () {
        Route::any('/uploads', 'index')->name('uploaded-files.index');
        Route::any('/uploads/create', 'create')->name('uploads.create');
        Route::any('/uploads/file-info', 'file_info')->name('my_uploads.info');
        Route::get('/uploads/destroy/{id}', 'destroy')->name('my_uploads.destroy');
        Route::post('/bulk-uploaded-files-delete', 'bulk_uploaded_files_delete')->name('bulk-uploaded-files-delete');
    });
});

Route::group(['namespace' => 'App\Http\Controllers\Seller', 'prefix' => 'seller', 'middleware' => ['seller', 'verified', 'user', 'prevent-back-history'], 'as' => 'seller.'], function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
    });

    Route::get('/clear-cache', [DashboardController::class, 'clearCache'])->name('cache.clear');

    // Product
    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index')->name('products');
        Route::get('/product/create', 'create')->name('products.create');
        Route::post('/products/store/', 'store')->name('products.store');
        Route::get('/product/{id}/edit', 'edit')->name('products.edit');
        Route::post('/products/update/{product}', 'update')->name('products.update');
        Route::get('/products/duplicate/{id}', 'duplicate')->name('products.duplicate');
        Route::post('/products/sku_combination', 'sku_combination')->name('products.sku_combination');
        Route::post('/products/sku_combination_edit', 'sku_combination_edit')->name('products.sku_combination_edit');
        Route::post('/products/add-more-choice-option', 'add_more_choice_option')->name('products.add-more-choice-option');
        Route::post('/products/seller/featured', 'updateFeatured')->name('products.featured');
        Route::post('/products/published', 'updatePublished')->name('products.published');
        Route::get('/products/destroy/{id}', 'destroy')->name('products.destroy');
        Route::get('/products/single_destroy/{id}', 'single_destroy')->name('products.single_destroy');
        Route::post('/products/bulk-delete', 'bulk_product_delete')->name('products.bulk-delete');
        Route::post('/product-search', 'product_search')->name('product.search');
        Route::post('/get-selected-products', 'get_selected_products')->name('get-selected-products');

        // category-wise discount set
        Route::get('/categories-wise-product-discount', 'categoriesWiseProductDiscount')->name('categories_wise_product_discount');
        Route::post('/set-product-discount', 'setProductDiscount')->name('set_product_discount');
        Route::get('/filter-products', 'get_filter_products')->name('products.filter');
        Route::post('/bulk-product-stock-update', 'bulk_product_stock_update')->name('bulk-product-stock-update');
        Route::get('/stock/show/{id}', 'stockShow')->name('stock.show');
        Route::post('/save-as-draft', 'store_as_draft')->name('products.store_as_draft');
        Route::post('/products-search', 'products_search')->name('products.search');
        Route::post('/products/generate-with-ai', 'generateWithAI')->name('products.generate-with-ai');
    });



    // Product Bulk Upload
    Route::controller(ProductBulkUploadController::class)->group(function () {
        Route::get('/product-bulk-upload/index', 'index')->name('product_bulk_upload.index');
        Route::post('/product-bulk-upload/store', 'bulk_upload')->name('bulk_product_upload');
        Route::group(['prefix' => 'bulk-upload/download'], function() {
            Route::get('/category', 'pdf_download_category')->name('pdf.download_category');
            Route::get('/brand', 'pdf_download_brand')->name('pdf.download_brand');
        });
    });

    // Digital Product
    Route::controller(DigitalProductController::class)->group(function () {
        Route::get('/digitalproducts', 'index')->name('digitalproducts');
        Route::get('/digitalproducts/create', 'create')->name('digitalproducts.create');
        Route::post('/digitalproducts/store', 'store')->name('digitalproducts.store');
        Route::get('/digitalproducts/{id}/edit', 'edit')->name('digitalproducts.edit');
        Route::post('/digitalproducts/update/{product}', 'update')->name('digitalproducts.update');
        Route::get('/digitalproducts/destroy/{id}', 'destroy')->name('digitalproducts.destroy');
        Route::get('/digitalproducts/download/{id}', 'download')->name('digitalproducts.download');
    });

    // custom label
    Route::controller(CustomLabelController::class)->group(function () {
        Route::get('/custom-label-list', 'index')->name('custom_label.index');
        Route::get('/custom-label-create', 'create')->name('custom_label.create');
        Route::post('/custom-label-store', 'store')->name('custom_label.store');
        Route::get('/custom-label-edit/{id}', 'edit')->name('custom_label.edit');
        Route::post('/custom-label-update/{id}', 'update')->name('custom_label.update');
        Route::get('/custom-label-delete/{id}', 'destroy')->name('custom_label.delete');
        
        Route::post('/bulk-custom-label-delete', 'bulk_custom_label_delete')->name('bulk-custom-label-delete');
        Route::post('/custom-label-status-update', 'status_update')->name('custom_label.status_update');
        Route::get('/custom-labels-filter', 'filter')->name('custom_labels.filter');  
        Route::post('/custom-labels/product-search',  'custom_label_product_search')->name('custom_labels.product_search'); 
        Route::post('/custom-labels/product-add', 'product_add')->name('custom_labels.product_add');
        Route::post('/custom-labels/product-edit', 'product_edit')->name('custom_labels.product_edit');
    });
    
    // Note
    Route::resource('note', NoteController::class);
    Route::controller(NoteController::class)->group(function () {
        Route::get('/note/edit/{id}', 'edit')->name('note.edit');
        Route::get('note/delete/{note}', 'destroy')->name('note.delete');

        Route::post('/ajax-add-note/modal', 'seller_ajax_add_note_modal')->name('ajax_add_note_modal');
        Route::post('/ajax-add-note/store', 'seller_ajax_add_note_store')->name('ajax_add_note_store');
    });

    // Size Chart
    Route::controller(SizeChartController::class)->group(function () {  
        Route::get('/size-charts', 'index')->name('size-charts.index');
        Route::get('/size-chart-create', 'create')->name('size-charts.create');
        Route::get('/size-chart-show/{id}', 'show')->name('size-charts.show');
        Route::post('/size-chart-store', 'store')->name('size-charts.store');
        Route::get('/size-chart-edit/{size_chart}', 'edit')->name('size-charts.edit');
        Route::post('/size-chart-update/{size_chart}', 'update')->name('size-charts.update');
        Route::get('/size-charts/destroy/{id}',  'destroy')->name('size-charts.destroy');
        Route::get('/size-charts-filter', 'filter')->name('size_charts.filter'); 
        Route::post('/sizeChart-products-search', 'search')->name('sizeChart_products.search');  
        Route::post('/sizeChart-category-products-update', 'updateCategoryOrProducts')->name('assignCategoryOrProducts.update');        
        Route::post('/bulk-size-chart-delete', 'bulk_size_chart_delete')->name('bulk-size-chart-delete');  
        Route::post('size-charts/get-combination',  'get_combination')->name('size-charts.get-combination');
    });

    //Coupon
    Route::resource('coupon', CouponController::class);
    Route::controller(CouponController::class)->group(function () {
        Route::post('/coupon/get_form', 'get_coupon_form')->name('coupon.get_coupon_form');
        Route::post('/coupon/get_form_edit', 'get_coupon_form_edit')->name('coupon.get_coupon_form_edit');
        Route::get('/coupon/destroy/{id}', 'destroy')->name('coupon.destroy');
    });

    //Order
    Route::resource('orders', OrderController::class);
    Route::controller(OrderController::class)->group(function () {
        Route::post('/orders/update_delivery_status', 'update_delivery_status')->name('orders.update_delivery_status');
        Route::post('/orders/update_payment_status', 'update_payment_status')->name('orders.update_payment_status');

        // Order bulk export
        Route::get('/order-bulk-export', 'orderBulkExport')->name('order-bulk-export');
         Route::get('/filtered-orders', 'get_filter_orders')->name('orders.filter');
    });

    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/invoice/{order_id}', 'invoice_download')->name('invoice.download');
    });
    
    //Review
    Route::controller(ReviewController::class)->group(function () {
        Route::get('/product-reviews', 'index')->name('product-reviews');
        Route::get('/product/detail-reviews/{id}', 'detailReviews')->name('detail-reviews');
        
    });

    //Shop
    Route::controller(ShopController::class)->group(function () {
        Route::get('/shop', 'index')->name('shop.index');
        Route::get('/shop-design', 'shop_design')->name('shop.design');
        Route::post('/shop/update', 'update')->name('shop.update');
        Route::post('/shop/banner-update', 'bannerUpdate')->name('shop.banner.update');
        Route::get('/shop/apply-for-verification', 'verify_form')->name('shop.verify');
        Route::post('/shop/verification_info_store', 'verify_form_store')->name('shop.verify.store');
        Route::get('/category-wise-commission', 'categoriesWiseCommission')->name('categories-wise-commission');
    });

    //Payments
    Route::resource('payments', PaymentController::class);

    // Profile Settings
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::post('/profile/update/{id}', 'update')->name('profile.update');
    });

    // Address
    Route::resource('addresses', AddressController::class);
    Route::controller(AddressController::class)->group(function () {
        Route::post('/get-states', 'getStates')->name('get-state');
        Route::post('/get-cities', 'getCities')->name('get-city');
        Route::post('/address/update/{id}', 'update')->name('addresses.update');
        Route::get('/addresses/destroy/{id}', 'destroy')->name('addresses.destroy');
        Route::get('/addresses/set_default/{id}', 'set_default')->name('addresses.set_default');
    });

    // Money Withdraw Requests
    Route::controller(SellerWithdrawRequestController::class)->group(function () {
        Route::get('/money-withdraw-requests', 'index')->name('money_withdraw_requests.index');
        Route::post('/money-withdraw-request/store', 'store')->name('money_withdraw_request.store');
    });

    // Commission History
    Route::controller(CommissionHistoryController::class)->group(function () {
        Route::get('/commission-history', 'index')->name('commission-history.index');
    });

    //Conversations
    Route::controller(ConversationController::class)->group(function () {
        Route::get('/conversations', 'index')->name('conversations.index');
        Route::get('/conversations/show/{id}', 'show')->name('conversations.show');
        Route::post('conversations/refresh', 'refresh')->name('conversations.refresh');
        Route::post('conversations/message/store', 'message_store')->name('conversations.message_store');
    });

    // product query (comments) show on seller panel
    Route::controller(ProductQueryController::class)->group(function () {
        Route::get('/product-queries', 'index')->name('product_query.index');
        Route::get('/product-queries/{id}', 'show')->name('product_query.show');
        Route::put('/product-queries/{id}', 'reply')->name('product_query.reply');
    });

    // Support Ticket
    Route::controller(SupportTicketController::class)->group(function () {
        Route::get('/support_ticket', 'index')->name('support_ticket.index');
        Route::post('/support_ticket/store', 'store')->name('support_ticket.store');
        Route::get('/support_ticket/show/{id}', 'show')->name('support_ticket.show');
        Route::post('/support_ticket/reply', 'ticket_reply_store')->name('support_ticket.reply_store');
    });

    // Notifications
    Route::controller(NotificationController::class)->group(function () {
        Route::get('/all-notification', 'index')->name('all-notification');
        Route::post('/notifications/bulk-delete', 'bulkDelete')->name('notifications.bulk_delete');
        Route::get('/notification/read-and-redirect/{id}', 'readAndRedirect')->name('notification.read-and-redirect');

    });

    Route::controller(GSTController::class)->group(function () {
        Route::get('/gst-configuration', 'configure_index')->name('gst.configconfiguration');
        Route::post('/gst-docs-update', 'update_documents')->name('update_gst_docs');
        Route::get('/product-hsn-gst-assign', 'hsn_gst_assign')->name('products.hsn-gst.assigns');
        Route::get('/wholesale-product-hsn-gst-assign', 'wholesale_hsn_gst_assign')->name('products.wholesale-hsn-gst.assigns');
        Route::get('/auction-product-hsn-gst-assign', 'auction_hsn_gst_assign')->name('products.auction-hsn-gst.assigns');
        Route::get('/preorder-product-hsn-gst-assign', 'preorder_hsn_gst_assign')->name('products.preorder-hsn-gst.assigns');
        Route::post('/products-hsn-gst-single-update', 'updateHsnGstRate')->name('products.single-hsn-gst.update');
        Route::post('/bulk-product-gst-assign', 'updateBulkHsnGstRate')->name('products.bulk-product-gst-assign');
        Route::get('/products/gst/products/{type}', 'get_filter_products')->name('products.gst.filter');
    });

    Route::post('/all-chat-modal', [DashboardController::class, 'view_all_chat_modal'])->name('admin_view_all_chat_modal');
    
    Route::controller(SellerHubController::class)->group(function () {
        Route::post('/chat', 'view_chat_tab')->name('admin_seller_chat.show');
        Route::post('/chat/send-message', 'send_chat_message')->name('admin_seller_chat.send_message');
        Route::post('/seller-hub/notices', 'view_notices_tab')->name('admin_seller_hub.notices');
        Route::post('/seller-hub/requests', 'view_requests_tab')->name('admin_seller_hub.requests');
        Route::post('/seller-hub/promotions', 'view_promotions_tab')->name('admin_seller_hub.promotions');
        Route::post('/seller-hub/plus', 'view_plus_tab')->name('admin_seller_hub.plus');
        Route::post('seller-hub/request-store', 'storeRequest')->name('admin_seller_hub.request_store');    
    });

    // Promotional Products
    Route::controller(PromotionalProductController::class)->group(function () {
        Route::get('/promotional-products-index', 'index')->name('promotional_products.index');     
        Route::post('/promotional-products-update', 'update')->name('promotional_products.update');     
        Route::post('/promotional-products-search', 'search')->name('promotional_products.search');     
        Route::get('/promotional-products-filter', 'filter')->name('promotional_products.filter');   
        Route::get('/promotional-products-select-modal', 'productSelectModal')->name('promotional_products.select_modal');
        Route::post('/promotional-products-mark-todays-deal', 'markAsTodaysDeal')->name('promotional_products.mark_todays_deal');
        Route::post('/promotional-products-mark-featured', 'markAsFeatured')->name('promotional_products.mark_featured');  
        Route::get('/promotional-products-flash-sale-modal', 'flashSaleModal')->name('promotional_products.flash_sale_modal');
        Route::post('/promotional-products-active-flash-sales', 'getActiveFlashSalesForSeller')->name('promotional_products.active_flash_sales');
        Route::post('/promotional-products-mark-flash-sale', 'markAsFlashSale')->name('promotional_products.mark_flash_sale');
    });

});

