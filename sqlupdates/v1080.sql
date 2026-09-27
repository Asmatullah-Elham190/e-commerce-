INSERT INTO `business_settings` (`id`, `type`, `value`, `lang`, `created_at`, `updated_at`) 
VALUES 
(NULL, 'seller_product_refund_approval', 'admin_approval_required', NULL, current_timestamp(), current_timestamp());

UPDATE `business_settings` SET `value` = '10.8.0' WHERE `business_settings`.`type` = 'current_version';

COMMIT;