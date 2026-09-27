ALTER TABLE `orders`
ADD COLUMN `steadfast_consignment_id` VARCHAR(100) NULL AFTER `pickup_token`,
ADD COLUMN `steadfast_tracking_code` VARCHAR(100) NULL AFTER `steadfast_consignment_id`,
ADD COLUMN `steadfast_status` VARCHAR(100) NULL AFTER `steadfast_tracking_code`,
ADD COLUMN `pathao_consignment_id` VARCHAR(100) NULL AFTER `steadfast_status`,
ADD COLUMN `pathao_status` VARCHAR(100) NULL AFTER `pathao_consignment_id`,
ADD COLUMN `pathao_delivery_fee` INT(11) DEFAULT 0 AFTER `pathao_status`;

UPDATE `business_settings` SET `value` = '10.2.2' WHERE `business_settings`.`type` = 'current_version';
COMMIT;