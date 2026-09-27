
INSERT INTO `permissions` (`id`, `name`, `section`, `guard_name`, `created_at`, `updated_at`) VALUES
(NULL, 'business_settings', 'setup_configurations', 'web', current_timestamp(), current_timestamp()),
(NULL, 'gst_assign', 'product', 'web', current_timestamp(), current_timestamp()),
(NULL, 'manage_shipping_system', 'shipping_system', 'web', current_timestamp(), current_timestamp()),
(NULL, 'pickup_address_index', 'setup_configurations', 'web', current_timestamp(), current_timestamp()),
(NULL, 'pickup_address_create', 'setup_configurations', 'web', current_timestamp(), current_timestamp()),
(NULL, 'pickup_address_edit', 'setup_configurations', 'web', current_timestamp(), current_timestamp()),
(NULL, 'pickup_address_delete', 'setup_configurations', 'web', current_timestamp(), current_timestamp()),
(NULL, 'shipping_box_size_index', 'setup_configurations', 'web', current_timestamp(), current_timestamp()),
(NULL, 'shipping_box_size_create', 'setup_configurations', 'web', current_timestamp(), current_timestamp()),
(NULL, 'shipping_box_size_edit', 'setup_configurations', 'web', current_timestamp(), current_timestamp()),
(NULL, 'shipping_box_size_delete', 'setup_configurations', 'web', current_timestamp(), current_timestamp());

ALTER TABLE `products`
ADD COLUMN `hsn_code` VARCHAR(20) NULL AFTER `tax_type`,
ADD COLUMN `gst_rate` DOUBLE(20,2) DEFAULT 0 AFTER `hsn_code`;

ALTER TABLE `shops`
ADD COLUMN `business_info` longtext DEFAULT NULL AFTER `verification_info`,
ADD COLUMN `gst_verification`  tinyint(2) NOT NULL DEFAULT 0 AFTER `business_info`;

ALTER TABLE `order_details`
ADD COLUMN `gst_rate` DOUBLE(20,2) NULL DEFAULT NULL AFTER `tax`,
ADD COLUMN `gst_amount` DOUBLE(20,2) NULL DEFAULT NULL AFTER `gst_rate`,
ADD COLUMN `coupon_discount` DOUBLE(20,2) DEFAULT 0 AFTER `price`;

ALTER TABLE `addresses`
ADD COLUMN `set_billing` VARCHAR(20) NULL AFTER `set_default`;

ALTER TABLE `carts`
ADD COLUMN `billing_address` int(11) NOT NULL DEFAULT 0 AFTER `address_id`;

ALTER TABLE `orders`
ADD COLUMN `billing_address` longtext DEFAULT NULL AFTER `shipping_address`,
ADD COLUMN `shipping_method` VARCHAR(255) NULL DEFAULT NULL AFTER `shipping_type`,
ADD COLUMN `shiprocket_order_id` BIGINT UNSIGNED NULL DEFAULT NULL AFTER `tracking_code`,
ADD COLUMN `shiprocket_shipment_id` BIGINT UNSIGNED NULL DEFAULT NULL AFTER `shiprocket_order_id`,
ADD COLUMN `shiprocket_status` VARCHAR(50) NULL DEFAULT NULL AFTER `shiprocket_shipment_id`,
ADD COLUMN `shiprocket_status_code` BIGINT NOT NULL DEFAULT 0 AFTER `shiprocket_status`,
ADD COLUMN `pickup_address_id` BIGINT UNSIGNED NULL DEFAULT NULL AFTER `shiprocket_status_code`;

CREATE TABLE `shipping_systems` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL, 
    `active` tinyint(1) NOT NULL DEFAULT 0,
    `addon_identifier` VARCHAR(255) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
);

CREATE TABLE `pickup_addresses` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `courier_type` VARCHAR(255) NOT NULL, 
    `address_nickname` VARCHAR(255) NOT NULL,
    `is_primary` TINYINT(1) DEFAULT 0 COMMENT '1 = primary location',
    `status` TINYINT DEFAULT 1 COMMENT '1=Active, 0=Inactive',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `shipping_box_sizes` (
  `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `courier_type` VARCHAR(255) NOT NULL,
  `user_id` INT NOT NULL,
  `length` FLOAT NOT NULL,
  `breadth` FLOAT NOT NULL,
  `height` FLOAT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

UPDATE `business_settings` SET `value` = '10.2.0' WHERE `business_settings`.`type` = 'current_version';

COMMIT;