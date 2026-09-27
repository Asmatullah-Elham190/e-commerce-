INSERT INTO `permissions` (`id`, `name`, `section`, `guard_name`, `created_at`, `updated_at`) VALUES
(NULL, 'manage_order', 'sale', 'web', current_timestamp(), current_timestamp()),
(NULL, 'can_download_and_print_shipping_label', 'sale', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_promotion_and_offers_dashboard', 'promotion_and_offers', 'web', current_timestamp(), current_timestamp()),
(NULL, 'view_promotional_product', 'promotion_and_offers', 'web', current_timestamp(), current_timestamp()),
(NULL, 'add_promotional_products', 'promotion_and_offers', 'web', current_timestamp(), current_timestamp()),
(NULL, 'remove_from_promotional', 'promotion_and_offers', 'web', current_timestamp(), current_timestamp()),
(NULL, 'remove_from_todays_deal', 'promotion_and_offers', 'web', current_timestamp(), current_timestamp()),
(NULL, 'add_todays_deal_products', 'promotion_and_offers', 'web', current_timestamp(), current_timestamp()),
(NULL, 'can_set_category_wise_discount', 'promotion_and_offers', 'web', current_timestamp(), current_timestamp());

UPDATE `permissions`
SET `section` = 'promotion_and_offers',
    `updated_at` = current_timestamp()
WHERE `name` = 'set_category_wise_discount';

ALTER TABLE `products`
ADD COLUMN `promotional` TINYINT(1) NOT NULL DEFAULT 0 AFTER `draft`;

UPDATE products 
SET promotional = 1
WHERE id IN (
    SELECT product_id 
    FROM flash_deal_products
);

UPDATE products p
JOIN coupons c 
    ON c.type = 'product_base'
SET p.promotional = 1
WHERE JSON_CONTAINS(
    c.details,
    JSON_OBJECT('product_id', CAST(p.id AS CHAR))
);

UPDATE products
SET promotional = 1
WHERE todays_deal = 1;

ALTER TABLE `orders`
ADD COLUMN `invoice_number` VARCHAR(255) NULL AFTER `code`;

ALTER TABLE `orders`
ADD COLUMN `order_note` VARCHAR(255) NULL AFTER `invoice_number`;

INSERT INTO `business_settings` (`type`, `value`)
VALUES (
    'invoice_config',
    '{"invoice_logo":null,"invoice_title":"invoice","custom_invoice_title":null,"footer_text":"Powered by Active IT Zone Limited.","generate_invoice_number":1,"barcode_type":"qrcode","barcode_encode":"order_number","custom_barcode_value":null,"show_human_readable_text_below_barcode":1,"show_qr_code_alongside_barcode":0,"show_platform_contact":0,"fields":{"show_platform_contact":"1","show_seller_contact":"1","show_customer_name":"1","show_billing_address":"1","show_product_image":"1","show_sku":"1","show_product_variation":"1"},"company_name":"Active IT Zone Limited","address":"125, 3rd Floor, DEMO Block, Demo Park,","company_name_and_address":"get_from_general_settings","phone_email":"get_from_general_settings","phone":"113452335","email":"admin@example.com","custom_company_name":null,"custom_address":null,"custom_phone":null,"custom_email":null}'
);

INSERT INTO `business_settings` (`type`, `value`)
VALUES (
    'shipping_label',
    '{"label_size_preset":"4x6","barcode_type":"qrcode","barcode_encode":"order_number","custom_barcode_value":null,"show_human_readable_text_below_barcode":1,"show_qr_code_alongside_barcode":0,"label_logo":null,"fields":{"sender_name_and_address":"1","receiver_name_and_address":"1","order_number":"1","cod_amount":"1","custom_footer_text":"1"},"custom_footer_text":"Powered by Active IT Zone Limited"}'
);

INSERT INTO `business_settings` (`type`, `value`)
VALUES (
    'thermal_printer',
    '{"generate_invoice_for_thermal_printer":"1","fields":{"show_logo":"1","show_platform_contact":"1","show_seller_contact":"1","show_sku":"1","show_product_variation":"1","show_barcode":"1","show_qr_code":"1"}}'
);

UPDATE `business_settings` SET `value` = '10.9.0' WHERE `business_settings`.`type` = 'current_version';

COMMIT;