-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 02, 2026 at 03:09 PM
-- Server version: 5.7.23-23
-- PHP Version: 8.1.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `getdemo_magazine`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer_login_log`
--

CREATE TABLE `customer_login_log` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `login_date_time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_login_log`
--

INSERT INTO `customer_login_log` (`id`, `customer_id`, `login_date_time`) VALUES
(1, 1, '2025-12-27 17:38:38'),
(2, 1, '2025-12-27 17:46:04'),
(3, 1, '2025-12-29 13:01:31'),
(4, 1, '2026-01-02 11:36:10'),
(5, 9, '2026-01-02 11:42:47');

-- --------------------------------------------------------

--
-- Table structure for table `customer_magazine_log`
--

CREATE TABLE `customer_magazine_log` (
  `logid` int(11) NOT NULL,
  `magazine_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `clicked_count` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_master`
--

CREATE TABLE `customer_master` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_mobile` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iStatus` int(11) NOT NULL DEFAULT '1',
  `isDelete` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `must_reset_password` tinyint(1) NOT NULL DEFAULT '0',
  `temp_password_set_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_master`
--

INSERT INTO `customer_master` (`customer_id`, `customer_name`, `customer_mobile`, `customer_email`, `password`, `iStatus`, `isDelete`, `created_at`, `updated_at`, `must_reset_password`, `temp_password_set_at`) VALUES
(1, 'Bansari Patel', '9987654321', 'dev6.apolloinfotech@gmail.com', '$2y$10$z9zsMq66D/dkgu7vG930e.xEQI0yHOSp5NftxM4h025STOHN.6RO2', 1, 0, '2025-12-27 16:58:23', '2026-01-01 15:11:41', 0, NULL),
(2, 'Krunal Shah', '09824773136', 'shahkrunal83@gmail.com', '$2y$10$RMdUcWRoqjD8AS7ecPR.3OMv0KqUoRXkc6FCKKcPqhp6cNDVYuMk.', 1, 0, '2025-12-27 17:23:48', '2025-12-27 17:23:48', 0, NULL),
(3, 'Aloha to Omega Learning Center', '09987654321', 'dev5.apolloinfotech@gmail.com', '$2y$10$AFFsV0mxZkYKZh8OQi9lruGCp.C3A2olGMN/YuBP8RjPy4QEaJkK.', 1, 0, '2025-12-27 17:25:05', '2026-01-01 15:08:16', 0, NULL),
(9, 'John Doe', '9876543210', 'john@example.com', '$2y$10$uaBcFsA0Lx4e70rxYCdvpec.4rYv.9HM2cm3h.bq3gv7rX6A17L7i', 1, 0, '2025-12-29 13:03:06', '2026-01-01 15:10:08', 0, NULL),
(11, 'Tarang', '9876543212', 'dev1.apolloinfotech@gmail.com', '$2y$10$eGEaHGndJacJiVu7ZYjoJ.2IOCkATdfSptqz7i6xeSVrCW/zpmntC', 1, 0, '2026-01-02 11:44:20', '2026-01-02 11:44:20', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `magazine_master`
--

CREATE TABLE `magazine_master` (
  `id` int(11) NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pdf` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int(11) NOT NULL,
  `iStatus` int(11) NOT NULL DEFAULT '1',
  `isDelete` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `magazine_master`
--

INSERT INTO `magazine_master` (`id`, `title`, `image`, `pdf`, `month`, `year`, `iStatus`, `isDelete`, `created_at`, `updated_at`) VALUES
(1, 'test', '1766833997.png', 'uploads/magazine/pdfs/test_20251227160241_HptiC5ZSaD.pdf', '2', 2026, 1, 0, '2025-12-27 16:02:41', '2025-12-27 16:43:43'),
(2, 'test magazine', 'uploads/images/1767008547_N1ahxoCN_image1.png', 'uploads/pdfs/1767008654_08lgLb2k_form8-preview.pdf', '2', 2025, 1, 0, '2025-12-29 15:54:34', '2025-12-29 17:14:14'),
(3, 'Test', 'uploads/images/1767244544_2XOCx5h4_media1.jpeg', 'uploads/pdfs/1767244544_39NDl3Fq_rss-letterhead-clone.pdf', '1', 2026, 1, 0, '2026-01-01 10:45:44', '2026-01-01 14:11:31');

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('dev5.apolloinfotech@gmail.com', '$2y$10$Q.QZ3p7EpW0bn0BytAinSOgkJ.4Afq9wdZhGRGh.3aABVjYBMRpZW', '2025-12-29 17:08:17');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'user-list', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(2, 'user-create', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(3, 'user-edit', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(4, 'user-delete', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(5, 'role-create', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(6, 'role-edit', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(7, 'role-list', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(8, 'role-delete', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(9, 'permission-list', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(10, 'permission-create', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(11, 'permission-edit', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(12, 'permission-delete', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06');

-- --------------------------------------------------------

--
-- Table structure for table `plan_master`
--

CREATE TABLE `plan_master` (
  `plan_id` int(11) NOT NULL,
  `plan_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plan_amount` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `days` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iStatus` int(11) NOT NULL DEFAULT '1',
  `isDelete` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plan_master`
--

INSERT INTO `plan_master` (`plan_id`, `plan_name`, `plan_amount`, `days`, `iStatus`, `isDelete`, `created_at`, `updated_at`) VALUES
(1, 'test plan', '2500', '5', 1, 0, '2025-12-27 17:20:46', '2026-01-01 14:59:29'),
(3, 'Silver', '399', '28', 1, 0, '2026-01-01 15:03:00', '2026-01-01 15:03:00');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06'),
(2, 'User', 'web', '2022-09-12 04:33:06', '2022-09-12 04:33:06');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `subscription_master`
--

CREATE TABLE `subscription_master` (
  `subscription_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `plan_id` int(11) DEFAULT '0',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `iStatus` int(11) NOT NULL DEFAULT '1',
  `isDelete` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_master`
--

INSERT INTO `subscription_master` (`subscription_id`, `customer_id`, `plan_id`, `start_date`, `end_date`, `days`, `amount`, `iStatus`, `isDelete`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-12-27', '2026-02-27', 60, 3000, 1, 0, '2025-12-27 17:23:25', '2025-12-27 17:23:25'),
(2, 2, 1, '2025-10-26', '2025-12-30', 60, 3000, 1, 0, '2025-12-27 17:24:28', '2025-12-27 17:24:28'),
(3, 7, 0, '2025-12-26', '2025-12-26', NULL, 0, 1, 0, '2025-12-27 18:18:27', '2025-12-27 18:18:27'),
(4, 8, 0, '2025-12-28', '2025-12-28', NULL, 0, 1, 0, '2025-12-29 13:02:36', '2025-12-29 13:02:36'),
(5, 9, 0, '2025-12-28', '2025-12-28', NULL, 0, 1, 0, '2025-12-29 13:03:06', '2025-12-29 13:03:06'),
(6, 11, 0, '2026-01-01', '2026-01-01', NULL, 0, 1, 0, '2026-01-02 11:44:20', '2026-01-02 11:44:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '2' COMMENT '1=Admin, 2=TA/TP',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `mobile_number`, `email_verified_at`, `password`, `role_id`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin', 'admin@admin.com', '9824143973', NULL, '$2y$10$bpucN8DM6s02tG/jronIJOI7.bMTvOoQrNJA8coczGS2v/GXBbofS', 1, 1, 'Wh0YztnJ8glupTsbVqPyct7yAi1PyR1MoCKLpvPEXkeOwFQgFc1wFz8LSrgE', '2022-09-12 04:33:06', '2023-06-16 07:20:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer_login_log`
--
ALTER TABLE `customer_login_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_magazine_log`
--
ALTER TABLE `customer_magazine_log`
  ADD PRIMARY KEY (`logid`);

--
-- Indexes for table `customer_master`
--
ALTER TABLE `customer_master`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `magazine_master`
--
ALTER TABLE `magazine_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `plan_master`
--
ALTER TABLE `plan_master`
  ADD PRIMARY KEY (`plan_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `subscription_master`
--
ALTER TABLE `subscription_master`
  ADD PRIMARY KEY (`subscription_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer_login_log`
--
ALTER TABLE `customer_login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customer_magazine_log`
--
ALTER TABLE `customer_magazine_log`
  MODIFY `logid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_master`
--
ALTER TABLE `customer_master`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `magazine_master`
--
ALTER TABLE `magazine_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `plan_master`
--
ALTER TABLE `plan_master`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subscription_master`
--
ALTER TABLE `subscription_master`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
