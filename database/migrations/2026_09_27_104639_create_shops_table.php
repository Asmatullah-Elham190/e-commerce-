<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE `shops` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `sliders` longtext COLLATE utf8mb3_unicode_ci,
  `top_banner` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `banner_full_width_1` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `banners_half_width` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `banner_full_width_2` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `address` varchar(500) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `rating` double(3,2) NOT NULL DEFAULT '0.00',
  `num_of_reviews` int NOT NULL DEFAULT '0',
  `num_of_sale` int NOT NULL DEFAULT '0',
  `seller_package_id` int DEFAULT NULL,
  `product_upload_limit` int NOT NULL DEFAULT '0',
  `package_invalid_at` date DEFAULT NULL,
  `registration_approval` tinyint(1) NOT NULL DEFAULT '0',
  `verification_status` int NOT NULL DEFAULT '0',
  `verification_info` longtext COLLATE utf8mb3_unicode_ci,
  `business_info` longtext COLLATE utf8mb3_unicode_ci,
  `gst_verification` tinyint NOT NULL DEFAULT '0',
  `cash_on_delivery_status` int NOT NULL DEFAULT '0',
  `admin_to_pay` double(20,2) NOT NULL DEFAULT '0.00',
  `facebook` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `google` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `youtube` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `navbar_bg_color` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `navbar_text_color` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb3_unicode_ci,
  `pick_up_point_id` text COLLATE utf8mb3_unicode_ci,
  `shipping_cost` double(20,2) NOT NULL DEFAULT '0.00',
  `commission_percentage` double(8,2) NOT NULL DEFAULT '0.00',
  `delivery_pickup_latitude` float(17,15) DEFAULT NULL,
  `delivery_pickup_longitude` float(17,15) DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `bank_acc_name` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `bank_acc_no` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `bank_routing_no` int DEFAULT NULL,
  `bank_payment_status` int NOT NULL DEFAULT '0',
  `top_banner_image` longtext COLLATE utf8mb3_unicode_ci,
  `top_banner_link` longtext COLLATE utf8mb3_unicode_ci,
  `slider_images` longtext COLLATE utf8mb3_unicode_ci,
  `slider_links` longtext COLLATE utf8mb3_unicode_ci,
  `banner_full_width_1_images` longtext COLLATE utf8mb3_unicode_ci,
  `banner_full_width_1_links` longtext COLLATE utf8mb3_unicode_ci,
  `banners_half_width_images` longtext COLLATE utf8mb3_unicode_ci,
  `banners_half_width_links` longtext COLLATE utf8mb3_unicode_ci,
  `banner_full_width_2_images` longtext COLLATE utf8mb3_unicode_ci,
  `banner_full_width_2_links` longtext COLLATE utf8mb3_unicode_ci,
  `custom_followers` int DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci
SQL
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
}