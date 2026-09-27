
ALTER TABLE `products`
ADD COLUMN `draft` TINYINT(1) NOT NULL DEFAULT 0 AFTER `published`;

UPDATE `business_settings` SET `value` = '10.1.0' WHERE `business_settings`.`type` = 'current_version';

COMMIT;