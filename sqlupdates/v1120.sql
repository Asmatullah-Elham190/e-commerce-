INSERT INTO `permissions` (`id`, `name`, `section`, `guard_name`, `created_at`, `updated_at`) VALUES
(NULL, 'category_bulk_upload', 'product_category', 'web', current_timestamp(), current_timestamp()),
(NULL, 'assign_category_or_products', 'size_guide', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_assigned_category_or_products', 'size_guide', 'web', current_timestamp(), current_timestamp()),
(NULL, 'brand_bulk_export', 'brand', 'web', current_timestamp(), current_timestamp()),
(NULL, 'category_bulk_export', 'category', 'web', current_timestamp(), current_timestamp()),
(NULL, 'can_published_reviews', 'product_review', 'web', current_timestamp(), current_timestamp());

ALTER TABLE `size_charts`
MODIFY COLUMN `category_id` INT(11) NULL DEFAULT NULL;


ALTER TABLE `size_charts`
ADD COLUMN `product_ids` VARCHAR(255) NULL DEFAULT NULL AFTER `category_id`,
ADD COLUMN `user_id` INT(11) NULL AFTER `product_ids`,
ADD COLUMN `seller_access` INT(2) NOT NULL DEFAULT 0 AFTER `user_id`;

SET @admin_id = (
    SELECT id
    FROM users
    WHERE user_type = 'admin'
    LIMIT 1
);

UPDATE `size_charts`
SET `user_id` = @admin_id;

ALTER TABLE `size_charts`
MODIFY COLUMN `user_id` INT(11) NOT NULL;

ALTER TABLE `shops`
ADD COLUMN `navbar_bg_color` VARCHAR(255) NULL DEFAULT NULL AFTER `slug`,
ADD COLUMN `navbar_text_color` VARCHAR(255) NULL DEFAULT NULL AFTER `navbar_bg_color`;

CREATE TABLE `guest_otp_verifications` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `phone` VARCHAR(255) NOT NULL,
  `otp_code` VARCHAR(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
);

ALTER TABLE `guest_otp_verifications`
  ADD COLUMN `otp_sent_time` TIMESTAMP NULL DEFAULT NULL AFTER `otp_code`,
  ADD COLUMN `is_verified` TINYINT(1) NOT NULL DEFAULT 0 AFTER `otp_sent_time`;

UPDATE `business_settings` SET `value` = '11.2.0' WHERE `business_settings`.`type` = 'current_version';

COMMIT;