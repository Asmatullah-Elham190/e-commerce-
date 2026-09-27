INSERT INTO `permissions` (`id`, `name`, `section`, `guard_name`, `created_at`, `updated_at`) VALUES
(NULL, 'pos_order', 'pos_system', 'web', current_timestamp(), current_timestamp()),
(NULL, 'add_pos_products', 'pos_system', 'web', current_timestamp(), current_timestamp()),
(NULL, 'remove_pos_products', 'pos_system', 'web', current_timestamp(), current_timestamp());

ALTER TABLE `products`
ADD COLUMN `pos` TINYINT(1) NOT NULL DEFAULT 0 AFTER `draft`;

UPDATE `business_settings` SET `value` = '10.4.0' WHERE `business_settings`.`type` = 'current_version';

COMMIT;