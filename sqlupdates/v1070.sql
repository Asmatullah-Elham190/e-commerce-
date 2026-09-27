ALTER TABLE `products`
  MODIFY COLUMN `est_shipping_days` VARCHAR(20) DEFAULT NULL,
  ADD COLUMN `show_estimated_shipping_time` TINYINT(1) DEFAULT 0 AFTER `est_shipping_days`,
  ADD COLUMN `shipping_note_id` BIGINT(20) NULL AFTER `show_estimated_shipping_time`,
  ADD COLUMN `show_shipping_note` TINYINT(1) DEFAULT 0 AFTER `shipping_note_id`,
  ADD COLUMN `show_warranty_note` TINYINT(1) DEFAULT 1 AFTER `warranty_note_id`,
  ADD COLUMN `delivery_note_id` BIGINT(20) NULL AFTER `show_warranty_note`,
  ADD COLUMN `show_delivery_notes` TINYINT(1) DEFAULT 0 AFTER `delivery_note_id`;

INSERT INTO `business_settings` (`id`, `type`, `value`, `lang`, `created_at`, `updated_at`) 
VALUES 
(NULL, 'ai_activation', 0, NULL, current_timestamp(), current_timestamp()),
(NULL, 'gemini_model', NULL, NULL, current_timestamp(), current_timestamp());

CREATE TABLE `ai_prompts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `identifier` VARCHAR(255) NOT NULL,
    `prompt` TEXT NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `ai_prompts` (`identifier`, `prompt`, `created_at`, `updated_at`)
VALUES (
'product_add_edit_prompt',
'You are an expert e-commerce product assistant.
Product name: {product_name}
Target language: {language}
Generate realistic values only for:
{prompt_fields}
Return ONLY JSON object.',
NOW(),
NOW()
);

CREATE TABLE `ai_usage_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `prompt_tokens` INT DEFAULT NULL,
    `completion_tokens` INT DEFAULT NULL,
    `total_tokens` INT DEFAULT NULL,
    `model` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `ai_usage_logs_user_id_index` (`user_id`),
    KEY `ai_usage_logs_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `permissions` (`id`, `name`, `section`, `guard_name`, `created_at`, `updated_at`) VALUES
(NULL, 'manage_ai_configuration', 'ai_studio', 'web', current_timestamp(), current_timestamp());

UPDATE `business_settings` SET `value` = '10.7.0' WHERE `business_settings`.`type` = 'current_version';

COMMIT;