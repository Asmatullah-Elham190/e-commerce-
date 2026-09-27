
ALTER TABLE `orders`
ADD COLUMN `shiprocket_awb` VARCHAR(255) NULL AFTER `pickup_address_id`,
ADD COLUMN `shiprocket_courier_id` BIGINT UNSIGNED NULL AFTER `shiprocket_awb`,
ADD COLUMN `shiprocket_courier_name` VARCHAR(255) NULL AFTER `shiprocket_courier_id`,
ADD COLUMN `awb_assigned_at` TIMESTAMP NULL AFTER `shiprocket_courier_name`,
ADD COLUMN `shiprocket_label_url` TEXT NULL AFTER `awb_assigned_at`,
ADD COLUMN `shiprocket_manifest_url` TEXT NULL AFTER `shiprocket_label_url`,
ADD COLUMN `pickup_scheduled_at` TIMESTAMP NULL AFTER `shiprocket_manifest_url`,
ADD COLUMN `pickup_token` VARCHAR(100) NULL AFTER `pickup_scheduled_at`;

UPDATE `business_settings` SET `value` = '10.2.1' WHERE `business_settings`.`type` = 'current_version';

COMMIT;