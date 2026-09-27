<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE `reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(10) COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'real',
  `product_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `custom_reviewer_name` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `custom_reviewer_image` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `rating` int NOT NULL DEFAULT '0',
  `comment` mediumtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `photos` varchar(191) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `viewed` int NOT NULL DEFAULT '0',
  `created_at_is_custom` tinyint(1) NOT NULL DEFAULT '0',
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
        Schema::dropIfExists('reviews');
    }
}