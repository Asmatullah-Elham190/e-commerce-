    
INSERT INTO `permissions` (`id`, `name`, `section`, `guard_name`, `created_at`, `updated_at`) VALUES
(NULL, 'manage_shipping_areas', 'setup_configurations', 'web', '2025-07-13 00:13:41', '2025-07-13 15:31:31'),
(NULL, 'select_shipping_methods', 'setup_configurations', 'web', '2022-06-20 04:41:29', '2022-06-20 04:41:29');

CREATE TABLE `areas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city_id` int(11) NOT NULL,
  `cost` double(20,2) NOT NULL DEFAULT '0.00',
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `area_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lang` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `addresses` 
ADD COLUMN `area_id` INT(11) NULL AFTER `city_id`;

ALTER TABLE `cities`
ADD COLUMN `country_id` INT(11) NULL AFTER `state_id`,
MODIFY COLUMN `state_id` INT NULL;

UPDATE `cities` AS `c`
JOIN `states` AS `s` ON `c`.`state_id` = `s`.`id`
SET `c`.`country_id` = `s`.`country_id`;

ALTER TABLE `addresses`
MODIFY COLUMN `state_id` INT NULL;
INSERT INTO `business_settings` (`id`, `type`, `value`, `lang`, `created_at`, `updated_at`) 
VALUES 
(NULL, 'whatsapp_order_seller_prods', 0, NULL, current_timestamp(), current_timestamp()),
(NULL, 'has_state', 1, NULL, current_timestamp(), current_timestamp());

ALTER TABLE `categories`
ADD COLUMN `refund_request_time` INT UNSIGNED NULL AFTER `slug`;

ALTER TABLE `order_details`
ADD COLUMN `refund_days` INT NOT NULL DEFAULT 0
AFTER `delivery_status`;

UPDATE `business_settings` SET `value` = '9.9.7' WHERE `business_settings`.`type` = 'current_version';

COMMIT;