INSERT INTO `permissions` (`id`, `name`, `section`, `guard_name`, `created_at`, `updated_at`) VALUES
(NULL, 'view_unit', 'product_attribute', 'web', current_timestamp(), current_timestamp()),
(NULL, 'add_unit', 'product_attribute', 'web', current_timestamp(), current_timestamp()),
(NULL, 'edit_unit', 'product_attribute', 'web', current_timestamp(), current_timestamp()),
(NULL, 'delete_unit', 'product_attribute', 'web', current_timestamp(), current_timestamp()),
(NULL, 'add_gmc_product', 'marketing_analytics', 'web', current_timestamp(), current_timestamp()),
(NULL, 'edit_gmc_product', 'marketing_analytics', 'web', current_timestamp(), current_timestamp()),
(NULL, 'delete_gmc_product', 'marketing_analytics', 'web', current_timestamp(), current_timestamp());

CREATE TABLE `units` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
);

INSERT INTO units (name)
  SELECT DISTINCT unit
  FROM products
  WHERE unit IS NOT NULL
  AND unit <> '';

ALTER TABLE products
ADD COLUMN unit_id INT NULL AFTER unit;

UPDATE products p
INNER JOIN units u ON p.unit = u.name
SET p.unit_id = u.id;

SELECT p.id, p.unit, p.unit_id, u.name
FROM products p
LEFT JOIN units u ON p.unit_id = u.id;

ALTER TABLE products
CHANGE unit old_unit VARCHAR(191);

ALTER TABLE products CHANGE unit_id unit INT;

ALTER TABLE `products`
ADD COLUMN `gmc` TINYINT(1) NOT NULL DEFAULT 0 AFTER `promotional`,
ADD COLUMN `facebook_catalogue` TINYINT(1) NOT NULL DEFAULT 0 AFTER `gmc`;

ALTER TABLE `users` 
ADD `seller_monthly_token_limit` INT(2) DEFAULT 0 AFTER `banned`,
ADD `seller_monthly_token_limit_setup_date` DATETIME NULL AFTER `seller_monthly_token_limit`;

INSERT INTO `business_settings` (`type`, `value`) VALUES 
( 'seller_monthly_token_limit', '0' );

INSERT INTO `custom_labels` (`id`, `user_id`, `text`, `background_color`, `text_color`, `seller_access`, `created_at`, `updated_at`) VALUES
(200, 0, 'Flash Sale', '#FF5500', 'white', '0', current_timestamp(), current_timestamp()),
(201, 0, 'Todays Deal', '#00ffd5', 'dark', '0', current_timestamp(), current_timestamp()),
(202, 0, '-x%', '#f50909', 'white', '0', current_timestamp(), current_timestamp()),
(203, 0, 'Wholesale', '#455a64', 'white', '0', current_timestamp(), current_timestamp());

INSERT INTO `custom_label_translations` ( `id`, `custom_label_id` , `text` , `lang` , `created_at` , `updated_at` ) VALUES
(null, 200, 'Flash Sale', 'en', current_timestamp(), current_timestamp()),
(null, 201, 'Todays Deal', 'en', current_timestamp(), current_timestamp()),
(null, 202, '-x%', 'en', current_timestamp(), current_timestamp()),
(null, 203, 'Wholesale', 'en', current_timestamp(), current_timestamp());

ALTER TABLE `custom_labels`
ADD COLUMN `status` TINYINT(1) NOT NULL DEFAULT 1 AFTER `seller_access`;

UPDATE `business_settings` SET `value` = '11.1.0' WHERE `business_settings`.`type` = 'current_version';

COMMIT;