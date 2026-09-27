<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDynamicPopupsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE `dynamic_popups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `status` int NOT NULL DEFAULT '0',
  `title` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `summary` text COLLATE utf8mb4_general_ci NOT NULL,
  `banner` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `btn_link` varchar(191) COLLATE utf8mb4_general_ci NOT NULL,
  `btn_text` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `btn_text_color` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `btn_background_color` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `show_subscribe_form` varchar(191) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_popups');
    }
}