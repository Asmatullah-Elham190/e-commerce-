INSERT INTO `permissions` (`id`, `name`, `section`, `guard_name`, `created_at`, `updated_at`) 
VALUES
(NULL, 'set_category_wise_commission', 'seller', 'web', current_timestamp(), current_timestamp()),
(NULL, 'set_seller_based_commission', 'seller', 'web', current_timestamp(), current_timestamp());

UPDATE `business_settings` SET `value` = '9.9.1' WHERE `business_settings`.`type` = 'current_version';

COMMIT;