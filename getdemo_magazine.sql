-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 09, 2026 at 03:56 PM
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
-- Table structure for table `article_master`
--

CREATE TABLE `article_master` (
  `article_id` int(11) NOT NULL,
  `strGuid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `magazine_id` int(11) NOT NULL,
  `article_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `article_image` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `article_pdf` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `isPaid` int(11) NOT NULL COMMENT '0=free,1=paid',
  `view_count` int(11) DEFAULT '0',
  `iStatus` tinyint(4) NOT NULL DEFAULT '1',
  `isDelete` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `article_master`
--

INSERT INTO `article_master` (`article_id`, `strGuid`, `magazine_id`, `article_title`, `article_image`, `article_pdf`, `isPaid`, `view_count`, `iStatus`, `isDelete`, `created_at`, `updated_at`) VALUES
(1, 'b8132c07-3f2c-4d61-aa02-6c64cb2b64d5', 3, 'test article 1', 'uploads/articles/images/1769493768_697855083d8f9.png', 'uploads/articles/pdfs/1769493768_697855083da54.pdf', 0, 1, 1, 0, '2026-01-24 16:10:49', '2026-02-06 17:45:26'),
(2, '969a5134-b751-4e1b-a939-e08ded321b54', 5, 'test article 2', 'uploads/articles/images/1769510404_697896043f551.jpg', 'uploads/articles/pdfs/1769493999_697855ef98dc2.pdf', 1, 1, 1, 0, '2026-01-27 11:36:39', '2026-02-06 17:42:26'),
(3, 'b3e31d8a-da3a-4533-bfef-e15f9c58e340', 5, 'test article 3', 'uploads/articles/images/1769510517_697896756e2ed.png', 'uploads/articles/pdfs/1769510517_697896756e423.pdf', 0, 0, 1, 0, '2026-01-27 16:11:57', '2026-02-06 12:29:32'),
(4, '9a05fde3-ef04-4c7a-b1e6-9dac49122b5e', 5, 'jhjakfjhkfhf', NULL, 'uploads/articles/pdfs/1769858710_697de696e9e63.pdf', 0, 1, 1, 0, '2026-01-31 16:50:21', '2026-02-06 12:29:59'),
(5, NULL, 7, 'High Quality Products', NULL, 'uploads/articles/pdfs/1769858565_697de605a17c4.pdf', 0, 0, 1, 1, '2026-01-31 16:52:45', '2026-01-31 16:55:46'),
(6, '8abe0467-21eb-4a3f-a7df-c7189f2b6147', 7, 'High Quality Products', NULL, 'uploads/articles/pdfs/1769858770_697de6d27640d.pdf', 0, 1, 1, 0, '2026-01-31 16:56:10', '2026-02-06 15:12:04'),
(7, 'b4adddc1-a960-4fee-990a-a882e3703a4f', 7, 'Pathey', NULL, 'uploads/articles/pdfs/1769858861_697de72dd89f7.pdf', 0, 0, 1, 0, '2026-01-31 16:57:41', '2026-02-06 12:27:33'),
(8, '7a3aaeea-653c-4958-8236-caf28bb69519', 7, 'Editorial', NULL, 'uploads/articles/pdfs/1769858915_697de763c8ae0.pdf', 0, 0, 1, 0, '2026-01-31 16:58:35', '2026-02-06 12:27:27'),
(9, '31b76a4b-e3e6-4da0-8a3a-ec763af11a91', 7, 'Anukramanika', NULL, 'uploads/articles/pdfs/1769858977_697de7a185155.pdf', 0, 0, 1, 0, '2026-01-31 16:59:37', '2026-02-06 12:27:21'),
(10, '6eebd08e-68e5-4877-afad-a5cc490d024e', 7, 'Yuva Samvad', NULL, 'uploads/articles/pdfs/1769859015_697de7c74adb4.pdf', 0, 1, 1, 0, '2026-01-31 17:00:15', '2026-02-06 17:57:23'),
(11, 'fa7355b8-680d-4a65-a46d-53bbeb89220a', 7, 'Sanskruti Sudha', NULL, 'uploads/articles/pdfs/1769859046_697de7e629448.pdf', 0, 0, 1, 0, '2026-01-31 17:00:46', '2026-02-06 12:26:58'),
(12, '2fe65376-bdba-4bc3-ad47-dfe89568dc33', 7, 'Cover Story', NULL, 'uploads/articles/pdfs/1769859148_697de84c6658c.pdf', 0, 0, 1, 0, '2026-01-31 17:02:28', '2026-02-06 12:26:53'),
(13, 'f360877f-5e61-47d8-bfd8-454c67f20ba6', 7, 'Granth Vimochan', NULL, 'uploads/articles/pdfs/1769859232_697de8a013e64.pdf', 0, 0, 1, 0, '2026-01-31 17:03:52', '2026-02-06 12:26:49'),
(14, '6c81ac4d-de36-4ac3-90a1-cee213bb7a8f', 7, 'Sangh', NULL, 'uploads/articles/pdfs/1769859297_697de8e17f521.pdf', 0, 0, 1, 0, '2026-01-31 17:04:57', '2026-02-06 12:26:44'),
(15, 'f944667b-b34b-46cb-bbf4-81e7ea785b79', 7, 'Samprat', NULL, 'uploads/articles/pdfs/1769859360_697de920a47b5.pdf', 0, 0, 1, 0, '2026-01-31 17:06:00', '2026-02-06 12:26:39'),
(16, '14fd21c1-9442-424e-b969-7bbb2b3195ea', 7, 'Bharat Gatha', NULL, 'uploads/articles/pdfs/1769859846_697deb067234b.pdf', 0, 0, 1, 0, '2026-01-31 17:14:06', '2026-02-06 12:26:35'),
(17, 'abf10e60-a413-47f1-8f83-4f3d96a0fadd', 7, 'Earth Taran', NULL, 'uploads/articles/pdfs/1769859900_697deb3cb2394.pdf', 0, 0, 1, 0, '2026-01-31 17:15:00', '2026-02-06 12:26:31'),
(18, '2b794ef5-f264-43ea-89f9-c65aa106d93c', 7, 'Samajoday', NULL, 'uploads/articles/pdfs/1769859949_697deb6db3ac2.pdf', 0, 0, 1, 0, '2026-01-31 17:15:49', '2026-02-06 12:26:27'),
(19, 'aa0bf9ae-5f66-4a21-923f-f6dba2143ba8', 7, 'Samrastano saint setu - Ravidas', NULL, 'uploads/articles/pdfs/1769860027_697debbb37ae2.pdf', 0, 0, 1, 0, '2026-01-31 17:17:07', '2026-02-06 12:26:24'),
(20, '2b0d3a59-5331-4eca-b1aa-5a04e9fbba86', 7, 'Sirumal Vadhvani', NULL, 'uploads/articles/pdfs/1769860096_697dec0061851.pdf', 0, 0, 1, 0, '2026-01-31 17:18:16', '2026-02-06 12:26:19'),
(21, 'db43e73b-15b1-4485-aa72-02b0edb19c72', 7, 'Book Review-Tatvamasi', NULL, 'uploads/articles/pdfs/1769860136_697dec28d6e3b.pdf', 0, 2, 1, 0, '2026-01-31 17:18:56', '2026-02-06 18:50:26'),
(22, 'ff617e72-538a-4223-8256-64cf328c25bb', 7, 'Novel 11', NULL, 'uploads/articles/pdfs/1769860215_697dec77857a1.pdf', 0, 1, 1, 0, '2026-01-31 17:20:15', '2026-02-06 18:45:58'),
(23, '9ef0b5a7-d9ee-4286-8d57-8474a8713ae1', 7, 'News', NULL, 'uploads/articles/pdfs/1769860278_697decb697cb0.pdf', 0, 2, 1, 0, '2026-01-31 17:21:18', '2026-02-06 15:18:49'),
(24, '10992ef8-50ce-4499-9fa0-639faaf6ae51', 7, 'Kalarav', NULL, 'uploads/articles/pdfs/1769860346_697decfac4cba.pdf', 0, 22, 1, 0, '2026-01-31 17:22:26', '2026-02-06 18:26:15'),
(25, NULL, 7, 'test', 'uploads/articles/images/1770199676_69831a7c1797b.jpg', 'uploads/articles/pdfs/1770199676_69831a7c17ab1.pdf', 0, 0, 1, 1, '2026-02-04 15:37:56', '2026-02-04 15:38:12'),
(26, NULL, 7, 'kllskglkg', NULL, 'uploads/articles/pdfs/1770199780_69831ae446a95.pdf', 1, 0, 1, 1, '2026-02-04 15:39:40', '2026-02-04 15:39:47');

-- --------------------------------------------------------

--
-- Table structure for table `customer_article_log`
--

CREATE TABLE `customer_article_log` (
  `logid` int(11) NOT NULL,
  `magazine_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `date_time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_article_log`
--

INSERT INTO `customer_article_log` (`logid`, `magazine_id`, `article_id`, `customer_id`, `date_time`) VALUES
(1, 5, 2, 12, '2026-01-27 15:31:34'),
(2, 3, 1, 12, '2026-01-27 15:35:07'),
(3, 3, 1, 12, '2026-01-27 15:38:24'),
(25, 7, 24, 12, '2026-01-31 17:53:33'),
(24, 7, 6, 12, '2026-01-31 17:06:02'),
(23, 5, 3, 12, '2026-01-31 17:02:18'),
(22, 5, 3, 9, '2026-01-27 16:19:09'),
(21, 5, 3, 12, '2026-01-27 16:18:39'),
(20, 5, 2, 12, '2026-01-27 16:08:58'),
(19, 5, 2, 12, '2026-01-27 16:08:37'),
(18, 3, 1, 12, '2026-01-27 16:06:56'),
(17, 3, 1, 12, '2026-01-27 16:06:36'),
(16, 3, 1, 12, '2026-01-27 16:06:29'),
(15, 3, 1, 9, '2026-01-27 16:04:34'),
(26, 7, 23, 12, '2026-01-31 17:53:39'),
(27, 7, 22, 12, '2026-01-31 17:53:45'),
(28, 7, 21, 12, '2026-01-31 17:53:52'),
(29, 7, 20, 12, '2026-01-31 17:54:06'),
(30, 7, 19, 12, '2026-01-31 17:54:15'),
(31, 7, 18, 12, '2026-01-31 17:54:31'),
(32, 7, 17, 12, '2026-01-31 17:54:40'),
(33, 7, 16, 12, '2026-01-31 17:54:51'),
(34, 7, 15, 12, '2026-01-31 17:54:59'),
(35, 7, 14, 12, '2026-01-31 17:55:09'),
(36, 7, 6, 12, '2026-01-31 17:55:21'),
(37, 7, 7, 12, '2026-01-31 17:55:30'),
(38, 7, 24, 12, '2026-02-03 15:16:29'),
(39, 7, 24, 12, '2026-02-03 15:25:48'),
(40, 7, 24, 12, '2026-02-03 15:26:35'),
(41, 7, 23, 12, '2026-02-03 15:26:46'),
(42, 7, 24, 13, '2026-02-06 12:29:13'),
(43, 5, 4, 13, '2026-02-06 12:29:59'),
(44, 7, 21, 13, '2026-02-06 12:40:34'),
(45, 7, 6, 13, '2026-02-06 15:12:04'),
(46, 7, 23, 13, '2026-02-06 15:18:49'),
(47, 7, 24, 14, '2026-02-06 15:36:50'),
(48, 5, 2, 1, '2026-02-06 17:42:26'),
(49, 7, 24, 1, '2026-02-06 17:42:55'),
(50, 3, 1, 1, '2026-02-06 17:45:26'),
(51, 7, 24, 14, '2026-02-06 17:55:32'),
(52, 7, 10, 14, '2026-02-06 17:57:23'),
(53, 7, 24, 14, '2026-02-06 18:06:41'),
(54, 7, 24, 14, '2026-02-06 18:17:21'),
(55, 7, 24, 14, '2026-02-06 18:26:15'),
(56, 7, 22, 14, '2026-02-06 18:45:58'),
(57, 7, 21, 14, '2026-02-06 18:50:26');

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
(5, 9, '2026-01-02 11:42:47'),
(6, 11, '2026-01-02 17:43:59'),
(7, 1, '2026-01-06 14:17:24'),
(8, 1, '2026-01-06 14:18:50'),
(9, 1, '2026-01-06 14:19:11'),
(10, 1, '2026-01-06 14:26:02'),
(11, 1, '2026-01-06 17:22:40'),
(12, 1, '2026-01-06 18:35:04'),
(13, 1, '2026-01-06 18:35:51'),
(14, 12, '2026-01-06 18:48:11'),
(15, 1, '2026-01-07 12:28:51'),
(16, 1, '2026-01-07 12:40:14'),
(17, 1, '2026-01-07 15:55:18'),
(18, 1, '2026-01-07 17:09:07'),
(19, 1, '2026-01-08 13:31:42'),
(20, 1, '2026-01-10 15:32:07'),
(21, 1, '2026-01-10 15:54:27'),
(22, 1, '2026-01-12 15:45:25'),
(23, 1, '2026-01-12 16:37:46'),
(24, 1, '2026-01-19 16:17:30'),
(25, 1, '2026-01-19 16:22:07'),
(26, 1, '2026-01-19 16:23:07'),
(27, 1, '2026-01-19 16:54:42'),
(28, 13, '2026-01-19 18:44:19'),
(29, 13, '2026-01-19 18:46:50'),
(30, 12, '2026-01-19 18:58:43'),
(31, 12, '2026-01-19 18:59:41'),
(32, 12, '2026-01-19 19:05:46'),
(33, 12, '2026-01-20 10:41:50'),
(34, 12, '2026-01-20 11:16:11'),
(35, 12, '2026-01-20 11:16:14'),
(36, 12, '2026-01-20 11:32:19'),
(37, 12, '2026-01-20 11:32:41'),
(38, 12, '2026-01-20 11:38:09'),
(39, 12, '2026-01-20 11:39:28'),
(40, 12, '2026-01-20 12:11:41'),
(41, 14, '2026-01-20 12:29:35'),
(42, 14, '2026-01-20 12:30:28'),
(43, 14, '2026-01-20 12:31:18'),
(44, 12, '2026-01-20 12:42:07'),
(45, 14, '2026-01-20 13:04:01'),
(46, 14, '2026-01-20 14:06:50'),
(47, 12, '2026-01-20 14:23:52'),
(48, 14, '2026-01-20 14:34:53'),
(49, 15, '2026-01-20 16:33:39'),
(50, 15, '2026-01-20 16:33:44'),
(51, 12, '2026-01-21 10:12:29'),
(52, 14, '2026-01-21 10:14:21'),
(53, 12, '2026-01-21 10:14:51'),
(54, 14, '2026-01-21 17:26:05'),
(55, 12, '2026-01-21 17:27:03'),
(56, 14, '2026-01-21 18:09:10'),
(57, 1, '2026-01-21 18:54:44'),
(58, 1, '2026-01-22 16:28:33'),
(59, 1, '2026-01-23 09:33:50'),
(60, 1, '2026-01-24 09:41:10'),
(61, 1, '2026-01-26 11:29:13'),
(62, 1, '2026-01-26 11:35:31'),
(63, 17, '2026-01-27 12:16:20'),
(64, 17, '2026-01-27 12:34:16'),
(65, 12, '2026-01-27 12:39:13'),
(66, 12, '2026-01-27 12:45:12'),
(67, 1, '2026-01-27 13:08:57'),
(68, 1, '2026-01-27 13:09:08'),
(69, 9, '2026-01-27 13:41:56'),
(70, 9, '2026-01-27 15:05:32'),
(71, 12, '2026-01-27 15:30:39'),
(72, 12, '2026-01-27 15:34:47'),
(73, 9, '2026-01-27 15:39:08'),
(74, 9, '2026-01-27 15:44:23'),
(75, 9, '2026-01-27 15:57:31'),
(76, 9, '2026-01-27 16:04:25'),
(77, 12, '2026-01-27 16:06:18'),
(78, 9, '2026-01-27 16:18:57'),
(79, 12, '2026-01-31 17:01:59'),
(80, 1, '2026-01-31 17:32:56'),
(81, 13, '2026-01-31 17:33:15'),
(82, 12, '2026-01-31 17:34:27'),
(83, 12, '2026-01-31 17:44:02'),
(84, 13, '2026-01-31 17:45:13'),
(85, 13, '2026-01-31 17:56:37'),
(86, 15, '2026-01-31 22:48:58'),
(87, 12, '2026-02-02 19:05:47'),
(88, 13, '2026-02-02 19:11:18'),
(89, 12, '2026-02-03 15:16:12'),
(90, 13, '2026-02-06 12:27:00'),
(91, 13, '2026-02-06 12:39:47'),
(92, 13, '2026-02-06 13:03:57'),
(93, 13, '2026-02-06 13:04:11'),
(94, 13, '2026-02-06 14:45:27'),
(95, 14, '2026-02-06 15:25:17'),
(96, 13, '2026-02-06 15:37:07'),
(97, 13, '2026-02-06 16:08:13'),
(98, 1, '2026-02-06 17:42:03'),
(99, 1, '2026-02-06 17:46:09'),
(100, 14, '2026-02-06 17:55:22'),
(101, 14, '2026-02-06 18:17:16'),
(102, 14, '2026-02-06 18:26:10'),
(103, 14, '2026-02-06 18:45:50'),
(104, 1, '2026-02-09 11:01:47');

-- --------------------------------------------------------

--
-- Table structure for table `customer_magazine_log`
--

CREATE TABLE `customer_magazine_log` (
  `logid` int(11) NOT NULL,
  `magazine_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `date_time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_magazine_log`
--

INSERT INTO `customer_magazine_log` (`logid`, `magazine_id`, `customer_id`, `date_time`) VALUES
(1, 1, 1, '2026-01-06 12:22:28'),
(2, 3, 14, '2026-01-20 14:47:43'),
(3, 1, 14, '2026-01-20 14:51:45'),
(4, 3, 15, '2026-01-20 16:35:27'),
(5, 3, 14, '2026-01-20 17:05:44'),
(6, 5, 14, '2026-01-20 17:18:01'),
(7, 2, 14, '2026-01-20 18:17:02'),
(8, 5, 14, '2026-01-20 19:01:17'),
(9, 5, 14, '2026-01-20 19:02:08'),
(10, 5, 14, '2026-01-20 19:14:56'),
(11, 4, 12, '2026-01-21 11:09:31'),
(12, 3, 12, '2026-01-21 11:10:18'),
(13, 5, 14, '2026-01-21 18:11:56'),
(14, 5, 1, '2026-01-26 11:36:42'),
(15, 5, 1, '2026-01-26 11:37:40'),
(16, 4, 1, '2026-01-26 11:38:05'),
(17, 5, 12, '2026-01-27 14:10:10');

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
  `profile_image` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_count` int(11) DEFAULT '0',
  `magazine_count` int(11) DEFAULT '0',
  `free_article` int(11) NOT NULL,
  `article_count` int(11) NOT NULL DEFAULT '0',
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

INSERT INTO `customer_master` (`customer_id`, `customer_name`, `customer_mobile`, `customer_email`, `password`, `profile_image`, `login_count`, `magazine_count`, `free_article`, `article_count`, `iStatus`, `isDelete`, `created_at`, `updated_at`, `must_reset_password`, `temp_password_set_at`) VALUES
(1, 'prerna parekh', '9987654321', 'dev5.apolloinfotech@gmail.com', '$2y$10$9LZdwJiZg1btoD9ZdMqMh.6txjJ9oLYgbAGXwSifZiqkmDjVYfkqm', 'cust_1_1768041388.png', 36, 4, 0, 0, 1, 0, '2025-12-27 16:58:23', '2026-02-09 11:01:47', 1, '2026-01-06 14:18:54'),
(15, 'Krunal Shah', '9824773136', 'shahkrunal83@gmail.com', '$2y$10$RqF7.ONNZOkrqnPTCpG4VOLpXzmuXmnkoQuIK/KHCsLe81TRXD/WW', NULL, 3, 1, 0, 0, 1, 0, '2026-01-20 16:32:05', '2026-01-31 22:48:58', 0, NULL),
(3, 'Aloha to Omega Learning Center', '9987654322', 'dev6.apolloinfotech@gmail.com', '$2y$10$sXv8vxMllSH4XMoE5Qah9et7zfmpaHH/0Y22.ldDmnmsqCgCWmH9m', NULL, 0, 0, 0, 0, 1, 0, '2025-12-27 17:25:05', '2026-01-06 14:10:10', 1, '2026-01-06 14:10:10'),
(9, 'John Doe', '9876543210', 'john@example.com', '$2y$10$9LZdwJiZg1btoD9ZdMqMh.6txjJ9oLYgbAGXwSifZiqkmDjVYfkqm', NULL, 8, 0, 0, 0, 1, 0, '2025-12-29 13:03:06', '2026-01-27 16:19:09', 0, NULL),
(11, 'Tarang', '9876543212', 'dev1.apolloinfotech@gmail.com', '$2y$10$VMxZ35t0V91N4SF5FRJcou0AOIgmdGqQaV4x1Uscd6xSQjXYZOpy2', NULL, 1, 0, 0, 0, 1, 0, '2026-01-02 11:44:20', '2026-01-02 16:57:53', 0, NULL),
(12, 'Mignesh', '9904500629', 'dev7.apolloinfotech@gmail.com', '$2y$10$9LZdwJiZg1btoD9ZdMqMh.6txjJ9oLYgbAGXwSifZiqkmDjVYfkqm', NULL, 27, 3, 10, 0, 1, 0, '2026-01-06 18:47:53', '2026-02-03 15:16:12', 1, '2026-01-07 12:27:49'),
(13, 'Apollo', '1234567890', 'dev8.apolloinfotech@gmail.com', '$2y$10$.8TASs7CuGii0F9vjMoKqu5x4XSUJY08kX77afWZcVJuDmK03e8Ce', NULL, 13, 0, 20, 0, 1, 0, '2026-01-19 18:44:07', '2026-02-06 16:08:13', 0, NULL),
(14, 'Tarang', '1111111111', 'devqq8.apolloinfotech@gmail.com', '$2y$10$ppfat5HhTzdr9wGtQ9pWMel6ULTKpSAlYuPMcINct5Zuco7.NnetW', NULL, 14, 9, 0, 0, 1, 0, '2026-01-20 12:29:24', '2026-02-06 18:45:50', 0, NULL),
(16, 'utkanth Bhandari', '9825060836', 'utkanth@streamlinecontrols.com', '$2y$10$4WsVxLXH7C7w7u7yi2esjuLEv2XaE7JsPV8lWyRmt7RfwJV0wNICe', NULL, 0, 0, 0, 0, 1, 0, '2026-01-24 09:40:28', '2026-01-24 09:40:28', 0, NULL),
(17, 'prerna', '9723391747', 'dev4.apolloinfotech@gmail.com', '$2y$10$ECY6QsnnidVHJaw0Nn..0eC9df1K6elMMpNGm0tsK2VMoCuTC1uFi', NULL, 2, 0, 9, 0, 1, 0, '2026-01-27 11:56:37', '2026-01-27 12:40:34', 0, NULL),
(18, 'test user', '8542157899', 'dev.apolloinfotech@gmail.com', '$2y$10$ITcpRga2/9Ic60dK3r118.vLZh.TbeyCVIMO5KpC73sUlQNoeqF1.', NULL, 0, 0, 25, 0, 1, 0, '2026-02-03 15:09:24', '2026-02-03 15:09:24', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `free_article`
--

CREATE TABLE `free_article` (
  `id` int(11) NOT NULL,
  `free_article` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `free_article`
--

INSERT INTO `free_article` (`id`, `free_article`) VALUES
(2, 25);

-- --------------------------------------------------------

--
-- Table structure for table `magazine_master`
--

CREATE TABLE `magazine_master` (
  `id` int(11) NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pdf` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int(11) NOT NULL,
  `publish_date` date NOT NULL,
  `magazine_count` int(11) NOT NULL DEFAULT '0',
  `iStatus` int(11) NOT NULL DEFAULT '1',
  `isDelete` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `magazine_master`
--

INSERT INTO `magazine_master` (`id`, `title`, `image`, `pdf`, `month`, `year`, `publish_date`, `magazine_count`, `iStatus`, `isDelete`, `created_at`, `updated_at`) VALUES
(1, 'વિતેલા વર્ષ ના વળામણાં', 'uploads/images/1768972833_oIXxRQaI_untitled-1-01-21-2026-10-50-am.png', 'uploads/pdfs/07-02-12-2023.pdf', '12', 2025, '2025-12-28', 1, 1, 0, '2025-12-27 16:02:41', '2026-01-21 11:00:08'),
(2, 'શ્રદ્ધેય અટલજીના જન્મદિને', 'uploads/images/1768972301_zzcPC89i_11-30-12-2023.png', 'uploads/pdfs/11-30-12-2023.pdf', '12', 2025, '2025-12-29', 1, 1, 0, '2025-12-29 15:54:34', '2026-01-21 10:58:10'),
(3, 'જળવાયું પરિવર્તનનું સંકટ હવે તો માત્ર એક જ ઉપાય', 'uploads/images/1768972616_VKFayhJF_untitled-1-01-21-2026-10-46-am.png', 'uploads/pdfs/09-16-12-2023.pdf', '1', 2026, '2026-01-18', 4, 1, 0, '2026-01-19 10:45:44', '2026-01-21 11:10:18'),
(4, 'હમ લાયે હૈ ચટ્ટાન સે જિંદગી નિકાલ કે', 'uploads/images/1768972680_HDEt0QHs_untitled-1-01-21-2026-10-47-am.png', 'uploads/pdfs/08-09-12-2023.pdf', '1', 2026, '2026-01-21', 2, 1, 0, '2026-01-21 10:56:59', '2026-01-26 11:38:05'),
(5, 'અખંડ ભારતને સુપ્રીમની મહોર', 'uploads/images/1768972751_WFJfDJ8C_untitled-1-01-21-2026-10-48-am.png', 'uploads/pdfs/10-23-12-2023.pdf', '2', 2026, '2026-02-16', 8, 1, 0, '2026-01-20 12:53:54', '2026-01-27 14:10:10'),
(6, 'test magazine add', 'uploads/images/1769494667_Uv4q8Wxt_1600w-kgrclsa6l6m.webp', NULL, '1', 2026, '2026-01-27', 0, 1, 1, '2026-01-27 11:47:33', '2026-01-27 11:48:04'),
(7, 'ભારત ના અમૃત કાલે ઉભર્તા યુવા', 'uploads/images/1769857923_VcvyB2Xd_1-title-1jpg.jpeg', NULL, '1', 2026, '2026-01-31', 0, 1, 0, '2026-01-31 16:42:03', '2026-01-31 16:42:03');

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
(5, 'Regular Plan', '49', '365', 1, 0, '2026-01-20 16:43:43', '2026-01-20 16:43:43');

-- --------------------------------------------------------

--
-- Table structure for table `razorpay_orders`
--

CREATE TABLE `razorpay_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `plan_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INR',
  `payment_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('created','paid','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'created',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `razorpay_orders`
--

INSERT INTO `razorpay_orders` (`id`, `customer_id`, `plan_id`, `order_id`, `receipt`, `amount`, `currency`, `payment_id`, `signature`, `status`, `created_at`, `updated_at`) VALUES
(30, 12, 5, 'order_S6WJAxQ3XF7f4t', 'rcpt_12_1768996642', 49.00, 'INR', 'pay_S6WJUU2uvYsqk2', '28ecb0253b22aa3e842932495bd948154ddff602e0e068b684f976c0159ea0d1', 'paid', '2026-01-21 11:57:25', '2026-01-21 11:58:06'),
(31, 12, 5, 'order_S6WeQxvT26A4Jx', 'rcpt_12_1768997850', 49.00, 'INR', 'pay_S6Wee8xBkoVR05', '21d4746e863e200bd939348fea4a83481465dd2685b6b1f93d251c7dd2c3d12b', 'paid', '2026-01-21 12:17:32', '2026-01-21 12:18:03'),
(32, 12, 5, 'order_S6Wqne89UJ6Eka', 'rcpt_12_1768998552', 49.00, 'INR', 'pay_S6Wr2YzFJNB6sS', '8d1e1b330bfec063d171b02c1a22196c8b585b7f00b2a5710e7b3c4cc001d070', 'paid', '2026-01-21 12:29:14', '2026-01-21 12:32:05'),
(33, 14, 5, 'order_S6X1xsmzIzedy7', 'rcpt_14_1768999187', 49.00, 'INR', 'pay_S6X27KOrukt4ah', '3f19b112470aec9d7711415d50f135d8cfff60cfb2c5796a678438e6cd634318', 'paid', '2026-01-21 12:39:49', '2026-01-21 12:40:15'),
(34, 14, 5, 'order_S6X2suFp6ILs2O', 'rcpt_14_1768999239', 49.00, 'INR', 'pay_S6X31PSTUH0wjS', 'ab9d8f85c5536d2f9929c79ecf04d5644a7b331d630d9c50a485856988948309', 'paid', '2026-01-21 12:40:41', '2026-01-21 12:41:06'),
(35, 14, 5, 'order_S6XK6GgUXmiiSh', 'rcpt_14_1769000213', 49.00, 'INR', 'pay_S6XKSmKfgVxfo6', '575be11b14842c1bf413d3da962b42e36c0d957a0cb2ae7bc0f5b6146c12f4da', 'paid', '2026-01-21 12:56:59', '2026-01-21 12:57:41'),
(36, 14, 5, 'order_S6XT1hCxCR3M2J', 'rcpt_14_1769000725', 49.00, 'INR', 'pay_S6XTKuTBb40FNq', '3d457de3f41269e46b8b3318013768eb73408c52f6359ff0596fb0ca58261ed7', 'paid', '2026-01-21 13:05:26', '2026-01-21 13:06:02'),
(37, 1, 5, 'order_S7BKUMsv2UF9Uk', 'rcpt_1_1769141103', 49.00, 'INR', NULL, NULL, 'created', '2026-01-23 04:05:05', '2026-01-23 04:05:05'),
(38, 1, 5, 'order_S8OzWxXcMCUaga', 'rcpt_1_1769407556', 49.00, 'INR', 'pay_S8OzmOyZFjlnRx', 'f85044089d11539680bb87801423982e4804545845c1857e3cfb0d6f068e85c1', 'paid', '2026-01-26 06:05:58', '2026-01-26 06:06:33'),
(39, 15, 5, 'order_SAZ8kljNUgEaJ4', 'rcpt_15_1769879976', 49.00, 'INR', NULL, NULL, 'failed', '2026-01-31 17:19:38', '2026-01-31 17:19:47');

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
  `isActive` int(11) NOT NULL DEFAULT '1',
  `iStatus` int(11) NOT NULL DEFAULT '1',
  `isDelete` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_master`
--

INSERT INTO `subscription_master` (`subscription_id`, `customer_id`, `plan_id`, `start_date`, `end_date`, `days`, `amount`, `isActive`, `iStatus`, `isDelete`, `created_at`, `updated_at`) VALUES
(1, 12, 5, '2026-01-21', '2027-01-20', 365, 49, 1, 1, 0, '2026-01-21 17:48:03', '2026-01-21 17:48:03'),
(2, 12, 5, '2027-01-21', '2028-01-20', 365, 49, 0, 1, 0, '2026-01-21 18:02:05', '2026-01-21 18:02:05'),
(3, 14, 5, '2025-01-21', '2026-01-20', 365, 49, 0, 1, 0, '2026-01-21 18:10:15', '2026-01-21 18:10:15'),
(5, 14, 5, '2026-01-20', '2026-01-21', 365, 49, 0, 1, 0, '2026-01-21 18:27:41', '2026-01-21 18:27:41'),
(6, 14, 5, '2026-01-22', '2027-01-21', 365, 49, 1, 1, 0, '2026-01-21 18:36:02', '2026-01-21 18:36:02'),
(7, 16, 0, '2026-01-23', '2026-01-23', NULL, 0, 1, 1, 0, '2026-01-24 09:40:28', '2026-01-24 09:40:28'),
(8, 1, 5, '2026-01-26', '2027-01-25', 365, 49, 1, 1, 0, '2026-01-26 11:36:33', '2026-01-26 11:36:33');

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
(1, 'Super Admin', 'admin', 'admin@admin.com', '9824143973', NULL, '$2y$10$bpucN8DM6s02tG/jronIJOI7.bMTvOoQrNJA8coczGS2v/GXBbofS', 1, 1, 'MqEx4GlLXkChakhZjYwf4DOb62qFn62h9lz5xodGFOU9bNrNJVLJVnvyPtvg', '2022-09-12 04:33:06', '2023-06-16 07:20:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `article_master`
--
ALTER TABLE `article_master`
  ADD PRIMARY KEY (`article_id`);

--
-- Indexes for table `customer_article_log`
--
ALTER TABLE `customer_article_log`
  ADD PRIMARY KEY (`logid`),
  ADD KEY `idx_customer_article` (`customer_id`,`article_id`);

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
-- Indexes for table `free_article`
--
ALTER TABLE `free_article`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `razorpay_orders`
--
ALTER TABLE `razorpay_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_order_id` (`order_id`),
  ADD KEY `idx_customer_id` (`customer_id`),
  ADD KEY `idx_plan_id` (`plan_id`),
  ADD KEY `idx_status` (`status`);

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
-- AUTO_INCREMENT for table `article_master`
--
ALTER TABLE `article_master`
  MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `customer_article_log`
--
ALTER TABLE `customer_article_log`
  MODIFY `logid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `customer_login_log`
--
ALTER TABLE `customer_login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `customer_magazine_log`
--
ALTER TABLE `customer_magazine_log`
  MODIFY `logid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `customer_master`
--
ALTER TABLE `customer_master`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `free_article`
--
ALTER TABLE `free_article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `magazine_master`
--
ALTER TABLE `magazine_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `plan_master`
--
ALTER TABLE `plan_master`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `razorpay_orders`
--
ALTER TABLE `razorpay_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subscription_master`
--
ALTER TABLE `subscription_master`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
