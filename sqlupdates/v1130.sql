INSERT INTO `permissions` (`id`, `name`, `section`, `guard_name`, `created_at`, `updated_at`) VALUES
(NULL, 'view_seller_promotional_product', 'promotion_and_offers', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_seller_requests', 'support', 'web', current_timestamp(), current_timestamp()),
(NULL, 'product_details_section', 'website_setup', 'web', current_timestamp(), current_timestamp()),
(NULL, 'select_all_category_layout', 'website_setup', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_uploaded_files', 'uploaded_files', 'web', current_timestamp(), current_timestamp());

CREATE TABLE `seller_admin_conversations` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `sender_id` INT(11) NOT NULL,
  `receiver_id` INT(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
);

CREATE TABLE `seller_admin_messages` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `seller_admin_conversation_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `message` LONGTEXT NOT NULL,
  `seen` INT(2) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
);

CREATE TABLE `seller_admin_promotions` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `todays_deal` INT(2) NOT NULL DEFAULT 0,
  `featured_products` INT(2) NOT NULL DEFAULT 0,
  `flash_sale_id` INT(11) NULL,
  `message` LONGTEXT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
);

CREATE TABLE `seller_admin_promotion_sellers` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `seller_admin_promotion_id` INT(11) NOT NULL,
  `seller_id` INT(11) NOT NULL,
  `seen` INT(2) NOT NULL DEFAULT 0,
  `responded` INT(2) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
);

CREATE TABLE `seller_admin_notices` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `notice_type` VARCHAR(255) NOT NULL DEFAULT 'permanent',
  `notice_datetime` VARCHAR(255) NULL,
  `message` LONGTEXT NULL,
  `bg_color` VARCHAR(255) NULL,
  `save_as_preset` INT(2) NOT NULL DEFAULT 0,
  `status` INT(2) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
);

CREATE TABLE `seller_admin_notice_sellers` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `seller_admin_notice_id` INT(11) NOT NULL,
  `seller_id` INT(11) NOT NULL,
  `seen` INT(2) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
);

CREATE TABLE `seller_admin_requests` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `seller_id` INT(11) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `category_name` VARCHAR(255) NULL,
  `brand_name` VARCHAR(255) NULL,
  `color_name` VARCHAR(255) NULL,
  `attribute_name` VARCHAR(255) NULL,
  `unit_name` VARCHAR(255) NULL,
  `measurement_point_name` VARCHAR(255) NULL,
  `warranty_name` VARCHAR(255) NULL,
  `seen` INT(2) NOT NULL DEFAULT 0,
  `message` LONGTEXT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
);

INSERT INTO `seller_admin_notices` (`id`, `notice_type`, `notice_datetime`, `message`, `bg_color`, `save_as_preset`, `created_at`, `updated_at`) VALUES
(1, 'default', '', 'Verify your account to earn a verified badge in your seller profile, increasing customer trust. Complete the verification from', 'Light Blue', 1, current_timestamp(), current_timestamp()),
(2, 'default', '', 'We are enabling GST on our platform. Please upload documents and complete', 'Light Pink', 1, current_timestamp(), current_timestamp()),
(3, 'default', '', 'Your Package will be expired on', 'Light Yellow', 1, current_timestamp(), current_timestamp()),
(4, 'default', '', 'Your Package is already expired. Please upgrade your package', 'Light Yellow', 1, current_timestamp(), current_timestamp());

UPDATE products p
INNER JOIN users u ON p.user_id = u.id
SET p.promotional = 1
WHERE p.featured = 1
  AND u.user_type = 'seller';

CREATE TABLE `seller_admin_promotion_participates` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `seller_admin_promotion_id` INT(11) NOT NULL,
  `seller_id` INT(11) NOT NULL,
  `seen` INT(2) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
);

CREATE TABLE `seller_admin_promotion_participate_products` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `seller_admin_promotion_participate_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `todays_deal` INT(2) NOT NULL DEFAULT 0,
  `featured` INT(2) NOT NULL DEFAULT 0,
  `flash_sale` INT(2) NOT NULL DEFAULT 0,
  `flash_sale_id` INT(11) NULL,
  `discount` double(20,2) NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
);

INSERT INTO `elements` (`id`, `name`, `created_at`, `updated_at`) VALUES
(4, 'All Category Page Layout', '2025-07-28 00:02:29', '2025-07-28 00:02:29');

INSERT INTO `element_types` (`id`, `element_id`, `name`, `is_default`, `created_at`, `updated_at`) VALUES
(13, 3, 'Megamenu 3', 0, current_timestamp(), current_timestamp()),
(14, 3, 'Megamenu 4', 0, current_timestamp(), current_timestamp()),
(15, 4, 'All Category Layout 1', 0, current_timestamp(), current_timestamp()),
(16, 4, 'All Category Layout 2', 0, current_timestamp(), current_timestamp());

INSERT INTO `business_settings` (`type`, `value`) VALUES 
( 'enable_product_description_section', '1' ),
( 'enable_product_related_section', '1' ),
( 'enable_ratings_and_review_section', '1' ),
( 'enable_product_queries_section', '1' ),
( 'enable_frequently_bought_section', '1' ),
( 'enable_more_from_this_seller_section', '1' ),
( 'all_category_element', '16' );

UPDATE `business_settings` SET `value` = '11.3.0' WHERE `business_settings`.`type` = 'current_version';

COMMIT;