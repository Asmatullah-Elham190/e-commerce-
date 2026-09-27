<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSellerAdminPromotionParticipateProductsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE `seller_admin_promotion_participate_products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `seller_admin_promotion_participate_id` int NOT NULL,
  `product_id` int NOT NULL,
  `todays_deal` int NOT NULL DEFAULT '0',
  `featured` int NOT NULL DEFAULT '0',
  `flash_sale` int NOT NULL DEFAULT '0',
  `flash_sale_id` int DEFAULT NULL,
  `discount` double(20,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_admin_promotion_participate_products');
    }
}