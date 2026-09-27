<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `combined_order_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `guest_id` int DEFAULT NULL,
  `seller_id` int DEFAULT NULL,
  `shipping_address` longtext COLLATE utf8mb3_unicode_ci,
  `billing_address` longtext COLLATE utf8mb3_unicode_ci,
  `additional_info` longtext COLLATE utf8mb3_unicode_ci,
  `shipping_type` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `shipping_method` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `order_from` varchar(20) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'web',
  `pickup_point_id` int NOT NULL DEFAULT '0',
  `carrier_id` int DEFAULT NULL,
  `delivery_status` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT 'pending',
  `delivery_status_update_datetime` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `payment_type` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `payment_status` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT 'unpaid',
  `payment_details` longtext COLLATE utf8mb3_unicode_ci,
  `grand_total` double(20,2) DEFAULT NULL,
  `coupon_discount` double(20,2) NOT NULL DEFAULT '0.00',
  `code` mediumtext COLLATE utf8mb3_unicode_ci,
  `invoice_number` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `order_note` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `tracking_code` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `shiprocket_order_id` bigint unsigned DEFAULT NULL,
  `shiprocket_shipment_id` bigint unsigned DEFAULT NULL,
  `shiprocket_status` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `shiprocket_order_status` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `shiprocket_status_code` bigint NOT NULL DEFAULT '0',
  `pickup_address_id` bigint unsigned DEFAULT NULL,
  `shiprocket_awb` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `shiprocket_courier_id` bigint unsigned DEFAULT NULL,
  `shiprocket_courier_name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `awb_assigned_at` timestamp NULL DEFAULT NULL,
  `shiprocket_label_url` text COLLATE utf8mb3_unicode_ci,
  `shiprocket_manifest_url` text COLLATE utf8mb3_unicode_ci,
  `pickup_scheduled_at` timestamp NULL DEFAULT NULL,
  `pickup_token` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `steadfast_consignment_id` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `steadfast_tracking_code` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `steadfast_status` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pathao_consignment_id` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pathao_status` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `redx_tracking_id` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `redx_status` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `redx_charge` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pathao_delivery_fee` int DEFAULT '0',
  `date` int NOT NULL,
  `viewed` int NOT NULL DEFAULT '0',
  `delivery_viewed` int NOT NULL DEFAULT '1',
  `payment_status_viewed` int DEFAULT '1',
  `commission_calculated` int NOT NULL DEFAULT '0',
  `notified` tinyint(1) NOT NULL DEFAULT '0',
  `delivered_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci
SQL
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
}