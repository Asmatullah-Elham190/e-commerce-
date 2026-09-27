<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCustomerProductsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE `customer_products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `published` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0',
  `added_by` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `subcategory_id` int DEFAULT NULL,
  `subsubcategory_id` int DEFAULT NULL,
  `brand_id` int DEFAULT NULL,
  `photos` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `thumbnail_img` varchar(150) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `conditon` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `location` text COLLATE utf8mb3_unicode_ci,
  `video_provider` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `video_link` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `unit` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `tags` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8mb3_unicode_ci,
  `unit_price` double(20,2) DEFAULT '0.00',
  `meta_title` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_description` varchar(500) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `meta_img` varchar(150) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pdf` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `slug` varchar(200) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
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
        Schema::dropIfExists('customer_products');
    }
}