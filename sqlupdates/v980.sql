
INSERT INTO `business_settings` (`id`, `type`, `value`, `lang`, `created_at`, `updated_at`) 
VALUES 
(NULL, 'whatsapp_chat', 0, NULL, current_timestamp(), current_timestamp());

DELETE FROM `permissions` WHERE `name`='facebook_chat';

INSERT INTO `permissions` (`id`, `name`, `section`, `guard_name`, `created_at`, `updated_at`) 
VALUES
(NULL, 'whatsapp_chat', 'setup_configurations', 'web', current_timestamp(), current_timestamp());

CREATE TABLE `registration_verification_codes` (
  `id` int(11) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `code` text NOT NULL,
  `is_verified` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `registration_verification_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

ALTER TABLE `registration_verification_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `email_templates` (`id`, `receiver`, `identifier`, `email_type`, `subject`, `default_text`, `status`, `is_status_changeable`, `is_dafault_text_editable`, `addon`, `created_at`, `updated_at`) 
VALUES
(null, 'seller', 'email_verification_for_registration_seller', 'Email Verification for Registration', 'Email Verification on [[store_name]]', '<span id=\"docs-internal-guid-b30785bd-7fff-1e0b-e705-8fd54008f465\"><p dir=\"ltr\" style=\"margin-top: 12pt; margin-bottom: 12pt; line-height: 1.38;\"><span style=\"background-color: transparent; font-family: Roboto, sans-serif; font-size: 11pt; white-space-collapse: preserve;\">Thank you for choosing [[store_name]] to grow your business! To register as a seller, we need you to confirm your email address.</span></p><p dir=\"ltr\" style=\"margin-top: 12pt; margin-bottom: 12pt; line-height: 1.38;\"><font face=\"Roboto, sans-serif\"><span style=\"font-size: 14.6667px; white-space-collapse: preserve;\">Your email verification code is [[code]]</span></font></p><p dir=\"ltr\" style=\"margin-top: 12pt; margin-bottom: 12pt; line-height: 1.38;\"><span style=\"font-size: 11pt; font-family: Roboto, sans-serif; background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-variant-position: normal; vertical-align: baseline; white-space-collapse: preserve;\">For any questions or concerns, feel free to contact us at </span><span style=\"font-size: 11pt; font-family: Roboto, sans-serif; background-color: transparent; font-weight: 700; font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-variant-position: normal; vertical-align: baseline; white-space-collapse: preserve;\">[[admin_email]]</span><span style=\"font-size: 11pt; font-family: Roboto, sans-serif; background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-variant-position: normal; vertical-align: baseline; white-space-collapse: preserve;\">.</span></p><p dir=\"ltr\" style=\"margin-top: 12pt; margin-bottom: 12pt; line-height: 1.38;\"><span style=\"font-size: 11pt; font-family: Roboto, sans-serif; background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-variant-position: normal; vertical-align: baseline; white-space-collapse: preserve;\">Thank you,</span><span style=\"font-size: 11pt; font-family: Roboto, sans-serif; background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-variant-position: normal; vertical-align: baseline; white-space-collapse: preserve;\"><br></span><span style=\"font-size: 11pt; font-family: Roboto, sans-serif; background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-variant-position: normal; vertical-align: baseline; white-space-collapse: preserve;\">The [[store_name]] Team</span></p><div><span style=\"font-size: 11pt; font-family: Roboto, sans-serif; background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-variant-position: normal; vertical-align: baseline; white-space-collapse: preserve;\"><br></span></div></span>', 1, 0, 1, NULL, '2024-09-04 00:31:38', '2025-01-15 10:37:36');


INSERT INTO `business_settings` (`type`, `value`)
SELECT 'authentication_layout_select', 'boxed'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM `business_settings` 
    WHERE `type` = 'authentication_layout_select'
);

UPDATE `business_settings` 
SET `value` = 'boxed' 
WHERE `type` = 'authentication_layout_select';


UPDATE `business_settings` SET `value` = '9.8' WHERE `business_settings`.`type` = 'current_version';

COMMIT;