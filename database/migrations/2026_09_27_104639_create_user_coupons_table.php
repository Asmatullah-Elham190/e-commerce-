<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserCouponsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(<<<'SQL'
CREATE TABLE `user_coupons` (
  `user_id` int NOT NULL,
  `coupon_id` int NOT NULL,
  `coupon_code` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `min_buy` double(20,2) NOT NULL,
  `validation_days` int NOT NULL,
  `discount` double(20,2) NOT NULL,
  `discount_type` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `expiry_date` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
SQL
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_coupons');
    }
}