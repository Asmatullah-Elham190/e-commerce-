INSERT INTO `permissions` (`id`, `name`, `section`, `guard_name`, `created_at`, `updated_at`) VALUES
(NULL, 'view_custom_label', 'product', 'web', current_timestamp(), current_timestamp()),
(NULL, 'custom_label_create', 'product', 'web', current_timestamp(), current_timestamp()),
(NULL, 'custom_label_edit', 'product', 'web', current_timestamp(), current_timestamp()),
(NULL, 'custom_label_delete', 'product', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_top_banner', 'website_setup', 'web', current_timestamp(), current_timestamp()),
(NULL, 'top_banner_setting', 'website_setup', 'web', current_timestamp(), current_timestamp()),
(NULL, 'top_banner_create', 'website_setup', 'web', current_timestamp(), current_timestamp()),
(NULL, 'top_banner_edit', 'website_setup', 'web', current_timestamp(), current_timestamp()),
(NULL, 'top_banner_delete', 'website_setup', 'web', current_timestamp(), current_timestamp()),
(NULL, 'publish_top_banner', 'website_setup', 'web', current_timestamp(), current_timestamp()),
(NULL, 'seller_can_access_label', 'website_setup', 'web', current_timestamp(), current_timestamp()),
(NULL, 'select_font_family', 'website_setup', 'web', current_timestamp(), current_timestamp()),
(NULL, 'product-bar', 'product', 'web', current_timestamp(), current_timestamp()),
(NULL, 'custom_visitors_setup', 'marketing', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_custom_sale_alert', 'marketing', 'web', current_timestamp(), current_timestamp());

CREATE TABLE `custom_labels` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` int(11) NOT NULL,
    `text` VARCHAR(255) NOT NULL, 
    `background_color` VARCHAR(255) NOT NULL,
    `text_color` VARCHAR(255) NOT NULL,
    `seller_access` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Seller can access admin custom label; 0 = No 1 = Yes',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `custom_label_translations` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `custom_label_id` bigint(20) NOT NULL,
  `text` VARCHAR(255) NOT NULL, 
  `lang` VARCHAR(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `products`
ADD COLUMN `custom_label_id` VARCHAR(1000) NULL AFTER `variations`;

ALTER TABLE `products`
ADD COLUMN `meta_keywords` VARCHAR(255) NULL AFTER `meta_description`;

ALTER TABLE `brands`
ADD COLUMN `meta_keywords` VARCHAR(255) NULL AFTER `meta_description`;

ALTER TABLE `categories`
ADD COLUMN `meta_keywords` VARCHAR(255) NULL AFTER `meta_description`,
ADD COLUMN `hot_category` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `featured`;

CREATE TABLE `top_banners` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `text` longtext NOT NULL,
  `link` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `top_banner_translations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `top_banner_id` bigint(20) NOT NULL,
  `text` longtext NOT NULL,
  `lang` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `custom_sale_alerts` (
  `id` int(11) NOT  NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `element_types` (`id`, `element_id`, `name`, `created_at`, `updated_at`) VALUES
(6, 1, 'Header 6', current_timestamp(), current_timestamp());

INSERT INTO element_styles (id, element_type_id, name, value, created_at, updated_at) VALUES
(29, 6, 'top_header_bg_color', '#ffffff', current_timestamp(), current_timestamp()),
(30, 6, 'bottom_header_bg_color', '#ffffff', current_timestamp(), current_timestamp()),
(31, 6, 'top_header_text_color', '#000000', current_timestamp(), current_timestamp()),
(32, 6, 'bottom_header_text_color', '#000000', current_timestamp(), current_timestamp());

INSERT INTO `custom_alerts` (`id`, `status`, `type`, `banner`, `link`, `description`, `text_color`, `background_color`, `created_at`, `updated_at`) VALUES
(200, 0, 'small', NULL, '#', 
'<p>You can earn club points by reviewing the products you’ve purchased. Submit your review <a href="https://demo.activeitzone.com/purchase_history?to_review=1">here.</a></p>', 
'dark', '#ffffff', '2024-03-26 20:02:20', '2025-06-22 08:46:59');

INSERT INTO `business_settings` (`id`, `type`, `value`, `lang`, `created_at`, `updated_at`) VALUES 
  (NULL, 'featured_category_section_bg_color', '#f9e0df', NULL, current_timestamp(), current_timestamp()),
  (NULL, 'featured_category_section_outline', 1, NULL, current_timestamp(), current_timestamp()),
  (NULL, 'featured_category_section_outline_color', '#d42d2a', NULL, current_timestamp(), current_timestamp()),
  (NULL, 'featured_category_btn_color', '#F94C10', NULL, current_timestamp(), current_timestamp()),
  (NULL, 'featured_category_section_btn_text_color', '#f5f5f5', NULL, current_timestamp(), current_timestamp()),
  (NULL, 'auction_product_bg_color', '#F0ECE3', NULL, current_timestamp(), current_timestamp()),
  (NULL, 'auction_product_btn_color', '#C7B198', NULL, current_timestamp(), current_timestamp()),
  (NULL, 'classified_bg_color', '#f5f5f5', NULL, current_timestamp(), current_timestamp()),
  (NULL, 'hero_bg_color', '#f5f5f5', NULL, current_timestamp(), current_timestamp());

UPDATE `business_settings` SET `value` = '10.0.0' WHERE `business_settings`.`type` = 'current_version';

COMMIT;