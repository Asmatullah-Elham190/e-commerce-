INSERT INTO `permissions` (`id`, `name`, `section`, `guard_name`, `created_at`, `updated_at`) VALUES
(NULL, 'select_header', 'website_setup', 'web', '2022-06-20 04:41:29', '2022-06-20 04:41:29');


CREATE TABLE `elements` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `elements` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Header', '2025-07-28 06:02:29', '2025-07-28 06:02:29');

ALTER TABLE `elements`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `elements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


CREATE TABLE `element_types` (
  `id` int(11) NOT NULL,
  `element_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `element_types` (`id`, `element_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Header 1', '2025-07-28 07:54:52', '2025-07-29 11:13:41'),
(2, 1, 'Header 2', '2025-07-28 07:56:28', '2025-07-29 11:13:41'),
(3, 1, 'Header 3', '2025-07-28 07:56:40', '2025-07-29 11:13:41'),
(4, 1, 'Header 4', '2025-07-28 07:56:52', '2025-07-29 11:13:41'),
(5, 1, 'Header 5', '2025-07-28 08:41:11', '2025-07-29 11:13:41');

ALTER TABLE `element_types`
  ADD PRIMARY KEY (`id`);
  ALTER TABLE `element_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;



CREATE TABLE `element_styles` (
  `id` int(11) NOT NULL,
  `element_type_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO element_styles (id, element_type_id, name, value, created_at, updated_at) VALUES
(1, 1, 'top_header_bg_color', '#ffffff', '2025-07-28 10:46:27', '2025-07-28 13:58:05'),
(2, 1, 'middle_header_bg_color', '#ffffff', '2025-07-28 10:46:27', '2025-07-28 10:46:27'),
(3, 1, 'bottom_header_bg_color', '#ff0000', '2025-07-28 10:46:27', '2025-07-29 07:04:22'),
(4, 1, 'top_header_text_color', '#857E7E', '2025-07-28 10:46:27', '2025-07-28 13:58:05'),
(5, 1, 'middle_header_text_color', '#857E7E', '2025-07-28 10:46:27', '2025-07-29 07:04:22'),
(6, 1, 'bottom_header_text_color', '#ffffff', '2025-07-28 10:50:54', '2025-07-28 10:50:54'),

(7, 2, 'top_header_bg_color', '#FFE200', '2025-07-28 14:12:22', '2025-07-28 14:12:22'),
(8, 2, 'middle_header_bg_color', '#FFE200', '2025-07-28 14:12:22', '2025-07-28 14:12:22'),
(9, 2, 'bottom_header_bg_color', '#262522', '2025-07-28 14:12:22', '2025-07-28 14:12:22'),
(10, 2, 'top_header_text_color', '#857E7E', '2025-07-28 14:12:22', '2025-07-28 14:12:22'),
(11, 2, 'middle_header_text_color', '#857E7E', '2025-07-28 14:12:22', '2025-07-29 07:08:36'),
(12, 2, 'bottom_header_text_color', '#ffffff', '2025-07-28 14:12:22', '2025-07-28 14:12:41'),

(13, 3, 'top_header_bg_color', '#6A0DAD', '2025-07-28 14:14:29', '2025-07-28 14:14:29'),
(14, 3, 'middle_header_bg_color', '#6A0DAD', '2025-07-28 14:14:29', '2025-07-28 14:14:29'),
(15, 3, 'bottom_header_bg_color', '#6A0DAD', '2025-07-28 14:14:29', '2025-07-28 14:14:29'),
(16, 3, 'top_header_text_color', '#BFBAC2', '2025-07-28 14:14:30', '2025-07-28 14:14:30'),
(17, 3, 'middle_header_text_color', '#BFBAC2', '2025-07-28 14:14:30', '2025-07-29 07:11:43'),
(18, 3, 'bottom_header_text_color', '#ffffff', '2025-07-28 14:14:30', '2025-07-28 14:14:30'),

(19, 4, 'top_header_bg_color', '#6A0DAD', '2025-07-28 14:15:31', '2025-07-28 14:15:31'),
(20, 4, 'middle_header_bg_color', '#ffffff', '2025-07-28 14:15:32', '2025-07-28 14:15:32'),
(21, 4, 'bottom_header_bg_color', '#ffffff', '2025-07-28 14:15:32', '2025-07-28 14:15:32'),
(22, 4, 'top_header_text_color', '#BFBAC2', '2025-07-28 14:15:32', '2025-07-28 14:15:32'),
(23, 4, 'middle_header_text_color', '#857E7E', '2025-07-28 14:15:32', '2025-07-29 07:14:32'),
(24, 4, 'bottom_header_text_color', '#6A0DAD', '2025-07-28 14:15:32', '2025-07-28 14:15:32'),

(25, 5, 'top_header_bg_color', '#4B0082', '2025-07-28 14:18:41', '2025-07-29 09:01:21'),
(26, 5, 'middle_header_bg_color', '#4B0082', '2025-07-28 14:18:41', '2025-07-28 14:18:41'),
(27, 5, 'top_header_text_color', '#ffffff', '2025-07-28 14:18:41', '2025-07-29 06:05:50'),
(28, 5, 'middle_header_text_color', '#ffffff', '2025-07-28 14:18:41', '2025-07-29 07:15:43');

ALTER TABLE `element_styles`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `element_styles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
    


CREATE TABLE `element_translations` (
  `id` bigint(20) NOT NULL,
  `element_id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `lang` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `business_settings` (`id`, `type`, `value`, `lang`, `created_at`, `updated_at`) 
VALUES
(NULL, 'header_element', 1, NULL, current_timestamp(), current_timestamp()),
(NULL, 'top_header_bg_color', '#ffffff', NULL, '2025-07-28 10:46:27', '2025-07-28 13:58:05'),
(NULL, 'middle_header_bg_color', '#ffffff', NULL, '2025-07-28 10:46:27', '2025-07-28 10:46:27'),
(NULL, 'bottom_header_bg_color', '#ff0000', NULL, '2025-07-28 10:46:27', '2025-07-29 07:04:22'),
(NULL, 'top_header_text_color', '#857E7E', NULL, '2025-07-28 10:46:27', '2025-07-28 13:58:05'),
(NULL, 'middle_header_text_color', '#857E7E', NULL, '2025-07-28 10:46:27', '2025-07-29 07:04:22'),
(NULL, 'bottom_header_text_color', '#ffffff', NULL, '2025-07-28 10:50:54', '2025-07-28 10:50:54');

ALTER TABLE `products`
ADD COLUMN `short_video` VARCHAR(255) NULL AFTER `thumbnail_img`,
ADD COLUMN `short_video_thumbnail` VARCHAR(255) NULL AFTER `short_video`,
MODIFY COLUMN `video_link` LONGTEXT NULL;


UPDATE `business_settings` SET `value` = '9.9.8' WHERE `business_settings`.`type` = 'current_version';

COMMIT;