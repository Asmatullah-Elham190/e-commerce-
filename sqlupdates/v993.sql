INSERT INTO `permissions` (`id`, `name`, `section`, `guard_name`, `created_at`, `updated_at`) VALUES
(NULL, 'cybersource_pg_configuration', 'cybersource_pg', 'web', '2022-06-22 00:13:41', '2022-06-12 15:31:31');

ALTER TABLE `categories`
ADD COLUMN `discount` DOUBLE(20,2) NOT NULL DEFAULT 0.00 AFTER `commision_rate`,
ADD COLUMN `discount_start_date` INT(11) DEFAULT NULL AFTER `discount`,
ADD COLUMN `discount_end_date` INT(11) DEFAULT NULL AFTER `discount_start_date`;

CREATE TABLE seller_categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  seller_id INT NOT NULL,
  category_id INT NOT NULL,
  discount DOUBLE(20,2) NOT NULL DEFAULT 0.00,
  discount_start_date INT(11) DEFAULT NULL,
  discount_end_date INT(11) DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `email_templates` (
    `id`, `receiver`, `identifier`, `email_type`, `subject`, `default_text`, 
    `status`, `is_status_changeable`, `is_dafault_text_editable`, `addon`, 
    `created_at`, `updated_at`
) 
VALUES (
    NULL,
    'customer',
    'wallet_recharge_email_to_customer',
    'Wallet Recharge Confirmation',
    'Your Wallet Has Been Recharged on [[store_name]]',
    '<span id="docs-internal-guid-b30785bd-7fff-1e0b-e705-8fd54008f465">
  <p dir="ltr" style="line-height:1.38;margin-top:12pt;margin-bottom:12pt;">
    <span style="font-size: 11pt; font-family: Roboto, sans-serif; font-weight: 700; background-color: transparent; vertical-align: baseline;">Dear [[customer_name]],</span>
  </p>
  
  <p dir="ltr" style="line-height:1.38;margin-top:12pt;margin-bottom:12pt;">
    <span style="font-size: 11pt; font-family: Roboto, sans-serif; background-color: transparent; vertical-align: baseline;">
      This is to confirm that your wallet has been successfully recharged.
    </span>
  </p>

  <p dir="ltr" style="line-height:1.38;margin-top:12pt;margin-bottom:12pt;">
    <span style="font-size: 11pt; font-family: Roboto, sans-serif; font-weight: 700; background-color: transparent; vertical-align: baseline;">Payment Details:</span>
  </p>

  <ul style="margin-bottom: 0px; padding-inline-start: 48px;">
    <li dir="ltr" style="list-style-type: disc; font-size: 11pt; font-family: Roboto, sans-serif; background-color: transparent;" aria-level="1">
      <p dir="ltr" style="line-height:1.38;margin-top:12pt;margin-bottom:0pt;" role="presentation">
        <span style="font-size: 11pt; font-weight: 700;">Payment Date:</span>
        <span> [[date]]</span>
      </p>
    </li>
    <li dir="ltr" style="list-style-type: disc; font-size: 11pt; font-family: Roboto, sans-serif; background-color: transparent;" aria-level="1">
      <p dir="ltr" style="line-height:1.38;margin-top:0pt;margin-bottom:0pt;" role="presentation">
        <span style="font-size: 11pt; font-weight: 700;">Amount Rechaged:</span>
        <span> [[amount]]</span>
      </p>
    </li>
    <li dir="ltr" style="list-style-type: disc; font-size: 11pt; font-family: Roboto, sans-serif; background-color: transparent;" aria-level="1">
      <p dir="ltr" style="line-height:1.38;margin-top:0pt;margin-bottom:12pt;" role="presentation">
        <span style="font-size: 11pt; font-weight: 700;">Payment Method:</span>
        <span> [[payment_method]]</span>
      </p>
    </li>
  </ul>

  <p dir="ltr" style="line-height:1.38;margin-top:12pt;margin-bottom:12pt;">
    <span style="font-size: 11pt; font-family: Roboto, sans-serif; background-color: transparent; vertical-align: baseline;">
      If you have any questions or need assistance, feel free to contact us at [[admin_email]].
    </span>
  </p>

  <p dir="ltr" style="line-height:1.38;margin-top:12pt;margin-bottom:12pt;">
    <span style="font-size: 11pt; font-family: Roboto, sans-serif; background-color: transparent; vertical-align: baseline;">
      Best regards,<br>
      The [[store_name]] Team
    </span>
  </p>
</span>',

    1, 0, 1, NULL,
    NOW(), NOW()
);
UPDATE `business_settings` SET `value` = '9.9.3' WHERE `business_settings`.`type` = 'current_version';

COMMIT;