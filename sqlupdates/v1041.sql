INSERT INTO `permissions` (`id`, `name`, `section`, `guard_name`, `created_at`, `updated_at`) VALUES
(NULL, 'customer_delete', 'customer', 'web', current_timestamp(), current_timestamp());

ALTER TABLE `wallets`
ADD COLUMN `added_by` VARCHAR(100) DEFAULT 'customer' AFTER `user_id`;

CREATE TABLE `payment_informations` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(11) NOT NULL, 
    `payment_type` VARCHAR(255) NOT NULL, 
    `payment_name` VARCHAR(255) NULL, 
    `payment_instruction` VARCHAR(255) NULL, 
    `bank_name` VARCHAR(255) NULL, 
    `account_name` VARCHAR(255) NULL, 
    `account_number` VARCHAR(255) NULL, 
    `routing_number` VARCHAR(255) NULL, 
    `set_default` VARCHAR(20) NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
);

UPDATE `business_settings` SET `value` = '10.4.1' WHERE `business_settings`.`type` = 'current_version';

COMMIT;