
ALTER TABLE `blogs`
ADD COLUMN `news` int(1) NOT NULL DEFAULT 0 AFTER `status`,
ADD COLUMN `event` int(1) NOT NULL DEFAULT 0 AFTER `news`,
ADD COLUMN `going_on` int(1) NOT NULL DEFAULT 0 AFTER `event`;

ALTER TABLE `users`
ADD COLUMN `verification_status` int(1) NOT NULL DEFAULT 1 AFTER `verification_code`,
ADD COLUMN `verification_info` longtext DEFAULT NULL AFTER `verification_status`;

ALTER TABLE `orders`
ADD COLUMN `shiprocket_order_status` VARCHAR(255) NULL AFTER `shiprocket_status`;

INSERT INTO `permissions` (`id`, `name`, `section`, `guard_name`, `created_at`, `updated_at`) VALUES
(NULL, 'knet_configuration', 'knet', 'web', current_timestamp(), current_timestamp());


UPDATE `business_settings` SET `value` = '10.3.0' WHERE `business_settings`.`type` = 'current_version';

COMMIT;