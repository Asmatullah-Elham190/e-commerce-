<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSellerAdminRequestsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE `seller_admin_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `seller_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `category_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `brand_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `color_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `attribute_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `unit_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `measurement_point_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `warranty_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `seen` int NOT NULL DEFAULT '0',
  `message` longtext COLLATE utf8mb4_general_ci,
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
        Schema::dropIfExists('seller_admin_requests');
    }
}