<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb3_unicode_ci NOT NULL,
  `added_by` varchar(6) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'admin',
  `user_id` int NOT NULL,
  `category_id` int NOT NULL,
  `brand_id` int DEFAULT NULL,
  `photos` varchar(2000) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `thumbnail_img` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `short_video` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `short_video_thumbnail` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `video_provider` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `video_link` longtext COLLATE utf8mb3_unicode_ci,
  `tags` varchar(500) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb3_unicode_ci,
  `unit_price` double(20,2) NOT NULL,
  `purchase_price` double(20,2) DEFAULT NULL,
  `variant_product` int NOT NULL DEFAULT '0',
  `attributes` varchar(1000) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '[]',
  `choice_options` mediumtext COLLATE utf8mb3_unicode_ci,
  `colors` mediumtext COLLATE utf8mb3_unicode_ci,
  `variations` text COLLATE utf8mb3_unicode_ci,
  `custom_label_id` varchar(1000) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `todays_deal` int NOT NULL DEFAULT '0',
  `published` int NOT NULL DEFAULT '1',
  `draft` tinyint(1) NOT NULL DEFAULT '0',
  `promotional` tinyint(1) NOT NULL DEFAULT '0',
  `gmc` tinyint(1) NOT NULL DEFAULT '0',
  `facebook_catalogue` tinyint(1) NOT NULL DEFAULT '0',
  `pos` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '1',
  `stock_visibility_state` varchar(10) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'quantity',
  `cash_on_delivery` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = On, 0 = Off',
  `featured` int NOT NULL DEFAULT '0',
  `seller_featured` int NOT NULL DEFAULT '0',
  `current_stock` int NOT NULL DEFAULT '0',
  `old_unit` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `unit` int DEFAULT NULL,
  `weight` double(8,2) NOT NULL DEFAULT '0.00',
  `min_qty` int NOT NULL DEFAULT '1',
  `low_stock_quantity` int DEFAULT NULL,
  `discount` double(20,2) NOT NULL DEFAULT '0.00',
  `discount_type` varchar(10) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'amount',
  `discount_start_date` int DEFAULT NULL,
  `discount_end_date` int DEFAULT NULL,
  `tax` double(20,2) DEFAULT NULL,
  `tax_type` varchar(10) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `hsn_code` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `gst_rate` double(20,2) DEFAULT '0.00',
  `shipping_type` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT 'flat_rate',
  `shipping_cost` double(20,2) NOT NULL DEFAULT '0.00',
  `is_quantity_multiplied` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = Mutiplied with shipping cost',
  `est_shipping_days` varchar(20) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `show_estimated_shipping_time` tinyint(1) DEFAULT '0',
  `shipping_note_id` bigint DEFAULT NULL,
  `show_shipping_note` tinyint(1) DEFAULT '0',
  `num_of_sale` int NOT NULL DEFAULT '0',
  `meta_title` mediumtext COLLATE utf8mb3_unicode_ci,
  `meta_description` longtext COLLATE utf8mb3_unicode_ci,
  `meta_keywords` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_img` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pdf` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `slug` mediumtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `rating` double(8,2) NOT NULL DEFAULT '0.00',
  `barcode` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `digital` int NOT NULL DEFAULT '0',
  `auction_product` int NOT NULL DEFAULT '0',
  `file_name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `external_link` varchar(500) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `external_link_btn` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT 'Buy Now',
  `wholesale_product` int NOT NULL DEFAULT '0',
  `frequently_bought_selection_type` varchar(19) COLLATE utf8mb3_unicode_ci DEFAULT 'product',
  `has_warranty` tinyint NOT NULL DEFAULT '0',
  `warranty_id` int DEFAULT NULL,
  `warranty_note_id` int DEFAULT NULL,
  `show_warranty_note` tinyint(1) DEFAULT '1',
  `delivery_note_id` bigint DEFAULT NULL,
  `show_delivery_notes` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `tags` (`tags`(255)),
  KEY `unit_price` (`unit_price`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci
SQL
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
}