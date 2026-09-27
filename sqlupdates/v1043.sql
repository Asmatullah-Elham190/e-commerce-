INSERT INTO `permissions` (`id`, `name`, `section`, `guard_name`, `created_at`, `updated_at`) VALUES
(NULL, 'uddoktapay_configuration', 'uddoktapay', 'web', current_timestamp(), current_timestamp());

ALTER TABLE `orders`
ADD COLUMN `redx_tracking_id` VARCHAR(100) NULL AFTER `pathao_status`,
ADD COLUMN `redx_status` VARCHAR(100) NULL AFTER `redx_tracking_id`,
ADD COLUMN `redx_charge` VARCHAR(100) NULL AFTER `redx_status`;

UPDATE `business_settings` SET `value` = '10.4.3' WHERE `business_settings`.`type` = 'current_version';
COMMIT;