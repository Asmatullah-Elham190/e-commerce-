ALTER TABLE `custom_alerts` ADD COLUMN `auto_hide` INT DEFAULT 0 AFTER `background_color`;
INSERT INTO `pages`
(`id`, `type`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `keywords`, `meta_image`, `created_at`, `updated_at`)
VALUES
(NULL, 'seller_legal_notice', 'Seller Legal Notice', 'seller-legal-notice', NULL, NULL, NULL, NULL, NULL, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP()),
(NULL, 'seller_withdrawal_policy', 'Seller Right of Withdrawal', 'seller-withdrawal-policy', NULL, NULL, NULL, NULL, NULL, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP()),
(NULL, 'seller_terms_and_conditions', 'Seller Terms and Conditions', 'seller-terms-and-conditions', NULL, NULL, NULL, NULL, NULL, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP());

INSERT INTO `translations` (`id`, `lang`, `lang_key`, `lang_value`, `created_at`, `updated_at`) VALUES
(NULL, 'en', 'cod_and_wallet_payment_with_otp', 'COD And Wallet Payment With OTP', '2020-11-02 07:40:38', '2021-09-20 07:29:07');

ALTER TABLE `users`
ADD COLUMN `otp_activation_purchase_cod_wallet` INT DEFAULT 0 AFTER `phone`,
ADD COLUMN `otp_alert_seen` INT DEFAULT 0 AFTER `otp_activation_purchase_cod_wallet`;

INSERT INTO `custom_alerts` (`id`, `status`, `type`, `banner`, `link`, `description`, `text_color`, `background_color`, `created_at`, `updated_at`) VALUES
(300, 0, 'small', NULL, '#', 
'<p>You can enable OTP verification with your phone number for Wallet Payments and Cash on Delivery <a href="https://demo.activeitzone.com/profile" class="otp-alert-link">here.</a></p>', 
'dark', '#ffffff', '2024-03-26 20:02:20', '2025-06-22 08:46:59');

UPDATE `business_settings` SET `value` = '10.5.0' WHERE `business_settings`.`type` = 'current_version';

COMMIT;