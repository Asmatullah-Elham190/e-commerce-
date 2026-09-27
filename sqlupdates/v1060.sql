INSERT INTO `business_settings` (`id`, `type`, `value`, `lang`, `created_at`, `updated_at`) 
VALUES 
(NULL, 'facebook_pixel_capi', 0, NULL, current_timestamp(), current_timestamp());


INSERT INTO `permissions` (`id`, `name`, `section`, `guard_name`, `created_at`, `updated_at`) VALUES
(NULL, 'manage_google_analytics', 'marketing_analytics', 'web', current_timestamp(), current_timestamp()),
(NULL, 'manage_pixel_analytics', 'marketing_analytics', 'web', current_timestamp(), current_timestamp());

UPDATE `business_settings` SET `value` = '10.6.0' WHERE `business_settings`.`type` = 'current_version';

COMMIT;