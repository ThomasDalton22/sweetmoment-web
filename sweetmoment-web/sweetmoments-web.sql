-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2025 at 12:16 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sweetmoments-web`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` enum('hero','middle','bottom') COLLATE utf8mb4_unicode_ci DEFAULT 'hero',
  `is_active` tinyint(1) DEFAULT 1,
  `order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `subtitle`, `image`, `link`, `position`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Special Wedding Package', 'Up to 30% discount for complete wedding services', 'banners/wedding-package-banner.jpg', NULL, 'hero', 1, 1, '2025-07-28 02:14:54', '2025-07-28 19:34:31'),
(2, 'Premium Photography', 'Capture your precious moments with top photographers', 'banners/photography-banner.jpg', NULL, 'hero', 1, 2, '2025-07-28 02:14:54', '2025-07-28 19:34:37'),
(3, 'Luxury Venues Available', 'Book your dream venue for the perfect wedding', 'banners/venue-banner.jpg', NULL, 'middle', 1, 3, '2025-07-28 02:14:54', '2025-07-28 19:34:41');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_package_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `event_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `vendor_package_id`, `quantity`, `event_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 11, 1, 1, '2025-12-15', 'Looking forward to working with you!', '2025-07-28 02:14:54', '2025-07-28 02:14:54'),
(2, 11, 4, 1, '2025-12-15', 'Need for outdoor ceremony', '2025-07-28 02:14:54', '2025-07-28 02:14:54'),
(5, 2, 3, 1, NULL, NULL, '2025-07-28 02:13:39', '2025-07-28 02:13:39');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_one_id` bigint(20) UNSIGNED NOT NULL,
  `user_two_id` bigint(20) UNSIGNED NOT NULL,
  `last_message_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_profile_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `vendor_profile_id`, `created_at`, `updated_at`) VALUES
(4, 2, 2, '2025-07-28 00:52:22', '2025-07-28 00:52:22'),
(5, 2, 1, '2025-07-28 07:52:26', '2025-07-28 07:52:26');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `from_user_id` bigint(20) UNSIGNED NOT NULL,
  `to_user_id` bigint(20) UNSIGNED NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_12_18_162706_create_vendor_offers_table', 1),
(6, '2024_12_18_185203_create_messages_table', 1),
(7, '2024_12_19_072814_create_weddings_table', 1),
(8, '2024_12_19_094936_create_parties_table', 1),
(9, '2024_12_27_174052_create_news_table', 1),
(10, '2025_01_03_085015_create_orders_table', 1),
(11, '2025_01_03_135119_create_pembayarans_table', 1),
(12, '2025_01_21_110910_create_porto_vendors_table', 1),
(13, '2025_01_24_111456_create_portfolios_table', 1),
(14, '2025_01_26_143715_create_testimonies_table', 1),
(15, '2025_01_27_021328_add_reactions_to_news_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `likes` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `dislikes` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `reaction` enum('like','dislike') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `description`, `content`, `image`, `likes`, `dislikes`, `reaction`, `created_at`, `updated_at`) VALUES
(1, 'Top 10 Wedding Trends for 2025', 'Discover the latest wedding trends that will make your special day unforgettable', 'Wedding trends for 2025 are all about personalization and sustainability. Couples are increasingly choosing eco-friendly options and unique personalized elements that reflect their personality...', 'news/wedding-trends-2025.jpg', 46, 3, NULL, '2025-07-28 02:14:54', '2025-07-28 15:30:52'),
(2, 'How to Choose the Perfect Wedding Venue', 'Essential tips for selecting the ideal venue for your dream wedding', 'Choosing the perfect wedding venue is one of the most important decisions you will make. Consider factors like guest capacity, location, budget, and the overall atmosphere you want to create...', 'news/wedding-venue-tips.jpg', 33, 1, NULL, '2025-07-28 02:14:54', '2025-07-28 14:52:31'),
(3, 'Wedding Photography: Candid vs Posed Shots', 'Understanding the difference and importance of both styles in wedding photography', 'Modern wedding photography combines both candid and posed shots to tell the complete story of your special day. Candid shots capture genuine emotions while posed shots ensure everyone looks their best...', 'news/wedding-photography-styles.jpg', 28, 2, NULL, '2025-07-28 02:14:54', '2025-07-28 02:14:54');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `data`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 11, 'order_confirmed', 'Order Confirmed', 'Your wedding package order has been confirmed', '{\"order_id\": 3, \"vendor\": \"Beauty by Sarah\"}', 0, '2025-07-28 02:14:54', '2025-07-28 02:14:54'),
(2, 11, 'new_message', 'New Message', 'You have a new message from Golden Moments Photography', '{\"from\": \"Golden Moments Photography\", \"message\": \"Thank you for choosing us!\"}', 0, '2025-07-28 02:14:54', '2025-07-28 02:14:54'),
(3, 12, 'payment_received', 'Payment Received', 'Your payment has been received and confirmed', '{\"order_id\": 1, \"amount\": 12000000}', 1, '2025-07-28 02:14:54', '2025-07-28 02:14:54'),
(4, 13, 'welcome', 'Welcome to Sweet Moments!', 'Thank you for joining our platform. Start exploring amazing wedding vendors.', '\"{\\\"action\\\":\\\"explore_vendors\\\"}\"', 1, '2025-07-28 12:20:43', '2025-07-28 12:25:13'),
(5, 1, 'vendor_application', 'New Vendor Application', 'New vendor application from Tess', '\"{\\\"vendor_profile_id\\\":10}\"', 1, '2025-07-28 14:08:34', '2025-07-28 14:52:55'),
(6, 1, 'vendor_application', 'New Vendor Application', 'New vendor application from Tess', '\"{\\\"vendor_profile_id\\\":11}\"', 1, '2025-07-28 14:12:03', '2025-07-28 14:52:55'),
(7, 1, 'vendor_application', 'New Vendor Application', 'New vendor application from Tess', '\"{\\\"vendor_profile_id\\\":12}\"', 1, '2025-07-28 14:14:34', '2025-07-28 14:52:55');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int(11) NOT NULL,
  `total_price` bigint(20) NOT NULL,
  `event_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Unpaid','Paid','Pending Payment') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unpaid',
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `vendor_package_id`, `name`, `address`, `phone`, `qty`, `total_price`, `event_date`, `notes`, `transaction_id`, `status`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 12, 2, 'Jane Smith', 'Jl. Sudirman No. 123, Jakarta', '+62812345689', 1, 12000000, '2025-08-20', 'Looking for romantic and elegant photos', NULL, 'Paid', NULL, '2025-07-28 02:14:54', '2025-07-28 02:14:54'),
(2, 12, 5, 'Jane Smith', 'Jl. Sudirman No. 123, Jakarta', '+62812345689', 1, 50000000, '2025-08-20', 'Need venue for 150 guests', NULL, 'Paid', NULL, '2025-07-28 02:14:54', '2025-07-28 02:14:54'),
(3, 11, 7, 'John Doe', 'Jl. Thamrin No. 456, Jakarta', '+62812345688', 1, 2000000, '2025-09-15', 'Natural makeup look preferred', NULL, 'Unpaid', NULL, '2025-07-28 02:14:54', '2025-07-28 02:14:54'),
(4, 2, 1, 'Tess', 'Jakarta Selatan, Indonesia', '+62812345679', 1, 5000000, '2025-07-30', 'Okay', 'SM-4-1753699938', 'Unpaid', 'pending', '2025-07-27 23:52:00', '2025-07-28 03:52:18'),
(5, 2, 2, 'Tess', 'Jakarta Selatan, Indonesia', '+62812345679', 1, 12000000, '2025-07-30', 'Okay', 'SM-5-1753692557', 'Unpaid', 'pending', '2025-07-27 23:52:00', '2025-07-28 01:49:17'),
(6, 13, 2, 'tes', 'jalan mekalang', '081228306241', 1, 12000000, '2025-07-30', NULL, 'testse', 'Paid', 'done', '2025-07-28 12:43:46', '2025-07-28 12:43:46'),
(7, 13, 13, 'tes', 'jalan mekalang', '081228306241', 1, 15000000, '2025-07-31', 'ye', 'SM-7-1753804037', 'Unpaid', 'pending', '2025-07-28 18:08:40', '2025-07-29 08:47:17'),
(8, 3, 10, 'Elegant Wedding Venue', 'Bali, Indonesia', '+62812345680', 1, 8000000, '2025-12-12', NULL, NULL, 'Unpaid', NULL, '2025-12-11 14:13:22', '2025-12-11 14:13:22'),
(9, 13, 10, 'tes', 'jalan mekalang', '081228306241', 1, 8000000, '2025-12-13', NULL, 'SM-9-1765521009', 'Unpaid', 'pending', '2025-12-11 23:29:50', '2025-12-11 23:30:09');

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_profile_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `review` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `vendor_profile_id`, `order_id`, `rating`, `review`, `created_at`, `updated_at`) VALUES
(1, 11, 1, NULL, 5, 'Amazing photography service! Golden Moments captured our wedding beautifully. Every photo tells a story and the quality is outstanding.', '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(2, 12, 1, NULL, 5, 'Professional and creative team. They made us feel comfortable throughout the day and delivered beyond our expectations.', '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(3, 11, 2, NULL, 5, 'The venue is absolutely stunning! The ocean view during our ceremony was breathtaking. Staff was very helpful and accommodating.', '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(4, 12, 3, NULL, 4, 'Sarah did an amazing job on my wedding makeup. I felt beautiful and confident all day. Highly recommend her services!', '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(5, 11, 4, NULL, 5, 'Perfect Day Videography created a cinematic masterpiece of our wedding. The video quality is incredible and captures all emotions perfectly.', '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(7, 13, 1, 6, 4, 'yo', '2025-07-28 13:45:50', '2025-07-28 13:45:50');

-- --------------------------------------------------------

--
-- Table structure for table `testimonies`
--

CREATE TABLE `testimonies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `testimony` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonies`
--

INSERT INTO `testimonies` (`id`, `user`, `testimony`, `rating`, `created_at`, `updated_at`) VALUES
(1, 'Sarah & Michael', 'Sweet Moments platform helped us find the perfect vendors for our dream wedding. The whole process was smooth and all vendors were professional!', 5, '2025-07-28 02:14:54', '2025-07-28 02:14:54'),
(2, 'Jessica & David', 'Amazing platform with great variety of vendors. We found our photographer, venue, and makeup artist all in one place. Highly recommended!', 5, '2025-07-28 02:14:54', '2025-07-28 02:14:54'),
(3, 'Amanda & Ryan', 'The quality of vendors on Sweet Moments is outstanding. Our wedding was perfect thanks to the professionals we found here.', 4, '2025-07-28 02:14:54', '2025-07-28 02:14:54'),
(4, 'Lisa & John', 'User-friendly platform with verified vendors. We felt secure booking through Sweet Moments and everything exceeded our expectations.', 5, '2025-07-28 02:14:54', '2025-07-28 02:14:54'),
(5, 'Maria & Carlos', 'Sweet Moments made our wedding planning so much easier. Great customer service and amazing vendor network!', 5, '2025-07-28 02:14:54', '2025-07-28 02:14:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `google_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_refresh_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `gender` enum('Laki-Laki','Perempuan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Laki-Laki',
  `role` enum('admin','vendor','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `google_id`, `google_token`, `google_refresh_token`, `name`, `email`, `email_verified_at`, `gender`, `role`, `address`, `profile_photo`, `phone`, `username`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, '', NULL, NULL, 'Admin User', 'admin@sweetmoments.com', NULL, 'Laki-Laki', 'admin', 'Jakarta, Indonesia', NULL, '+62812345678', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(2, '', NULL, NULL, 'Golden Moments Photographys', 'golden@example.com', NULL, 'Laki-Laki', 'vendor', 'Jakarta Selatan, Indonesia', NULL, '+62812345679', 'golden_photo', '$2y$10$3IGxiYpe97iKklEA5hhm/um/Kaa8puSX5.I/UjfFf6.tvPdVoUXC6', NULL, '2025-07-28 02:13:29', '2025-07-27 23:54:21'),
(3, '', NULL, NULL, 'Elegant Wedding Venue', 'elegant@example.com', NULL, 'Perempuan', 'vendor', 'Bali, Indonesia', NULL, '+62812345680', 'elegant_venue', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(4, '', NULL, NULL, 'Beauty by Sarah', 'sarah@example.com', NULL, 'Perempuan', 'vendor', 'Surabaya, Indonesia', NULL, '+62812345681', 'beauty_sarah', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(5, '', NULL, NULL, 'Perfect Day Videography', 'perfectday@example.com', NULL, 'Laki-Laki', 'vendor', 'Bandung, Indonesia', NULL, '+62812345682', 'perfect_video', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(6, '', NULL, NULL, 'Royal Bridal House', 'royal@example.com', NULL, 'Perempuan', 'vendor', 'Jakarta Utara, Indonesia', NULL, '+62812345683', 'royal_bridal', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(7, '', NULL, NULL, 'MC Profesional Indonesia', 'mcpro@example.com', NULL, 'Laki-Laki', 'vendor', 'Yogyakarta, Indonesia', NULL, '+62812345684', 'mc_pro', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(8, '', NULL, NULL, 'Harmony Entertainment', 'harmony@example.com', NULL, 'Laki-Laki', 'vendor', 'Medan, Indonesia', NULL, '+62812345685', 'harmony_ent', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(9, '', NULL, NULL, 'Moments Capture Studio', 'moments@example.com', NULL, 'Perempuan', 'vendor', 'Semarang, Indonesia', NULL, '+62812345686', 'moments_studio', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(10, '', NULL, NULL, 'Paradise Garden Venue', 'paradise@example.com', NULL, 'Laki-Laki', 'vendor', 'Malang, Indonesia', NULL, '+62812345687', 'paradise_venue', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(11, '', NULL, NULL, 'John Doe', 'john@example.com', NULL, 'Laki-Laki', 'user', 'Jakarta, Indonesia', NULL, '+62812345688', 'johndoe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(12, '', NULL, NULL, 'Jane Smith', 'jane@example.com', NULL, 'Perempuan', 'user', 'Surabaya, Indonesia', NULL, '+62812345689', 'janesmith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(13, '', NULL, NULL, 'tes', 'tes@gmail.com', NULL, 'Laki-Laki', 'vendor', 'jalan mekalang', NULL, '081228306241', 'tes', '$2y$12$SmhW6QWh22tu2ZUarAWbAOQkgnA5O/Q3Yz3nUOba1MEEK8XrVFesm', NULL, '2025-07-28 12:20:43', '2025-07-28 14:12:03');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_availability`
--

CREATE TABLE `vendor_availability` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_profile_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_availability`
--

INSERT INTO `vendor_availability` (`id`, `vendor_profile_id`, `date`, `is_available`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-08-15', 0, 'Already booked', '2025-07-28 02:14:55', '2025-07-28 02:14:55'),
(2, 1, '2025-08-16', 1, 'Available', '2025-07-28 02:14:55', '2025-07-28 02:14:55'),
(3, 1, '2025-08-17', 1, 'Available', '2025-07-28 02:14:55', '2025-07-28 02:14:55'),
(4, 2, '2025-09-20', 0, 'Private event', '2025-07-28 02:14:55', '2025-07-28 02:14:55'),
(5, 2, '2025-09-21', 1, 'Available', '2025-07-28 02:14:55', '2025-07-28 02:14:55'),
(6, 3, '2025-08-25', 1, 'Available', '2025-07-28 02:14:55', '2025-07-28 02:14:55'),
(7, 4, '2025-09-10', 0, 'Equipment maintenance', '2025-07-28 02:14:55', '2025-07-28 02:14:55'),
(8, 5, '2025-10-05', 1, 'Available', '2025-07-28 02:14:55', '2025-07-28 02:14:55');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_categories`
--

CREATE TABLE `vendor_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_categories`
--

INSERT INTO `vendor_categories` (`id`, `name`, `slug`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Venue', 'venue', 'bi-buildings', '2025-07-28 02:13:29', '2025-07-28 15:03:26'),
(2, 'Photography', 'photography', 'bi-camera', '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(3, 'Videography', 'videography', 'bi-camera-video', '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(4, 'Makeup Artist', 'mua', 'bi-brush', '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(5, 'Bridal', 'bridal', 'bi-heart', '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(6, 'MC', 'mc', 'bi-mic', '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(7, 'Entertainment', 'entertainment', 'bi-music-note', '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(8, 'Tes', 'tes', NULL, '2025-12-15 14:08:32', '2025-12-15 14:08:32'),
(9, 'tt', 'tt', 'bi-hearts', '2025-12-15 14:08:42', '2025-12-15 14:08:42');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_offers`
--

CREATE TABLE `vendor_offers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jenispenawaran` enum('weddingorganizer','partyorganizer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'weddingorganizer',
  `catatan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `budget` decimal(10,2) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_packages`
--

CREATE TABLE `vendor_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_profile_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `features` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_packages`
--

INSERT INTO `vendor_packages` (`id`, `vendor_profile_id`, `name`, `description`, `price`, `features`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Basic Package', 'Perfect for intimate weddings with essential photography coverage', '5000000.00', '4 hours coverage,100 edited photos,Online gallery,Basic retouching', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(2, 1, 'Premium Package', 'Complete wedding photography service with extended coverage', '12000000.00', '8 hours coverage,300 edited photos,Online gallery,Premium album,Engagement shoot', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(3, 1, 'Deluxe Package', 'Ultimate wedding photography experience with full day coverage', '25000000.00', 'Full day coverage,500+ edited photos,Online gallery,Premium album,Engagement shoot,Same day highlights,Professional prints', 1, '2025-07-28 02:13:29', '2025-07-28 09:00:25'),
(4, 2, 'Intimate Ceremony', 'Perfect for small weddings up to 50 guests', '20000000.00', 'Venue rental 6 hours,Basic decoration,Sound system,Bridal suite access,Coordinator service', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(5, 2, 'Grand Celebration', 'Complete venue package for up to 200 guests', '50000000.00', 'Venue rental 12 hours,Premium decoration,Sound & lighting system,Bridal suite,Catering coordination,Wedding coordinator', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(6, 2, 'Luxury Experience', 'Ultimate luxury package for exclusive celebrations', '80000000.00', 'Venue rental 2 days,Luxury decoration,Premium sound & lighting,Bridal villa,Personal coordinator,Pre-wedding venue access,Photography spots', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(7, 3, 'Bridal Basic', 'Essential bridal makeup for your wedding day', '2000000.00', 'Wedding day makeup,Basic hairstyle,Touch-up service,Trial session', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(8, 3, 'Bridal Premium', 'Complete bridal beauty package with hair styling', '5000000.00', 'Wedding day makeup,Professional hairstyle,Touch-up service,Trial session,Pre-wedding makeup,Accessories', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(9, 3, 'Bridal Deluxe', 'Ultimate bridal beauty experience', '8000000.00', 'Wedding day makeup,Professional hairstyle,Touch-up service,Multiple trials,Pre-wedding makeup,Wedding party makeup,Beauty consultation', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(10, 4, 'Highlight Reel', 'Beautiful wedding highlight video', '8000000.00', '6 hours filming,3-5 minute highlight video,Drone footage,Raw footage backup', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(11, 4, 'Documentary Style', 'Complete wedding day documentation', '18000000.00', '10 hours filming,Full ceremony video,Reception highlights,Drone footage,Same day edit,Raw footage', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(12, 4, 'Cinematic Experience', 'Premium cinematic wedding videography', '30000000.00', 'Full day filming,Cinematic highlight video,Full ceremony & reception,Drone footage,Same day edit,Multiple camera angles,Color grading', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(13, 5, 'Ready-to-Wear Collection', 'Beautiful ready-made wedding gowns', '15000000.00', 'Designer wedding gown,Basic alterations,Accessories package,Consultation service', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(14, 5, 'Custom Design', 'Personalized wedding gown creation', '30000000.00', 'Custom wedding gown,Multiple fittings,Premium fabrics,Accessories package,Design consultation', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(15, 5, 'Luxury Couture', 'Exclusive haute couture wedding experience', '50000000.00', 'Haute couture gown,Premium materials,Multiple fittings,Complete accessories,Personal stylist,Preservation service', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(16, 6, 'Ceremony Only', 'Professional MC for wedding ceremony', '3000000.00', 'Wedding ceremony hosting,Bilingual service,Script preparation,Sound check', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(17, 6, 'Full Day Service', 'Complete MC service for ceremony and reception', '8000000.00', 'Ceremony & reception hosting,Bilingual service,Custom script,Coordination with vendors,Sound check', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(18, 6, 'Premium Experience', 'Ultimate MC service with entertainment coordination', '12000000.00', 'Full day hosting,Bilingual service,Custom entertainment,Vendor coordination,Games & activities,Sound management', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_portfolio_images`
--

CREATE TABLE `vendor_portfolio_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_profile_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_portfolio_images`
--

INSERT INTO `vendor_portfolio_images` (`id`, `vendor_profile_id`, `image`, `caption`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 1, 'portfolios/golden-moments-1.jpg', 'Beautiful wedding ceremony capture', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(2, 1, 'portfolios/golden-moments-2.jpg', 'Romantic couple portrait', 0, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(3, 1, 'portfolios/golden-moments-3.jpg', 'Reception celebration moments', 0, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(4, 2, 'portfolios/elegant-venue-1.jpg', 'Stunning ocean view ceremony setup', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(5, 2, 'portfolios/elegant-venue-2.jpg', 'Beautiful garden reception area', 0, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(6, 3, 'portfolios/beauty-sarah-1.jpg', 'Glamorous bridal makeup look', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(7, 3, 'portfolios/beauty-sarah-2.jpg', 'Natural bridal beauty', 0, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(8, 4, 'portfolios/perfect-day-1.jpg', 'Cinematic wedding video still', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(9, 5, 'portfolios/royal-bridal-1.jpg', 'Elegant custom wedding gown', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(10, 6, 'portfolios/mc-pro-1.jpg', 'Professional MC in action', 1, '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(13, 2, 'portfolio/1765487574_693b33d6eb2fe.jpg', 'Tes', 1, '2025-12-11 14:12:54', '2025-12-11 14:12:54'),
(14, 1, 'portfolio/1765833112_694079989ff07.png', 'Tes', 1, '2025-12-15 14:11:52', '2025-12-15 14:11:52'),
(15, 12, 'portfolio/1765833313_69407a617f02c.jpg', 'image Caption', 1, '2025-12-15 14:15:13', '2025-12-15 14:15:13');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_profiles`
--

CREATE TABLE `vendor_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_category_id` bigint(20) UNSIGNED NOT NULL,
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_range_min` decimal(15,2) DEFAULT NULL,
  `price_range_max` decimal(15,2) DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `total_reviews` int(11) DEFAULT 0,
  `is_verified` tinyint(1) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive','pending') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `search_tags` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_profiles`
--

INSERT INTO `vendor_profiles` (`id`, `user_id`, `vendor_category_id`, `business_name`, `description`, `price_range_min`, `price_range_max`, `location`, `phone`, `whatsapp`, `instagram`, `website`, `rating`, `total_reviews`, `is_verified`, `is_featured`, `status`, `search_tags`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'Golden Moments Photography', 'Professional wedding photography service with over 10 years of experience. Specializing in candid moments and artistic compositions that tell your love story beautifully.', '5000000.00', '25000000.00', 'Jakarta Selatan', '+62812345679', '+62812345679', '@goldenmoments.photo', 'https://goldenmoments.com', '4.67', 3, 1, 1, 'active', 'wedding photography, prewedding, candid, artistic, jakarta', '2025-07-28 02:13:29', '2025-07-28 13:45:50'),
(2, 3, 1, 'Elegant Wedding Venue', 'Luxury wedding venue in the heart of Bali with stunning ocean views and traditional Balinese architecture. Perfect for intimate to grand celebrations.', '20000000.00', '80000000.00', 'Bali', '+62812345680', '+62812345680', '@elegantwedding.bali', 'https://elegantwedding.com', '5.00', 1, 1, 1, 'active', 'wedding venue, bali, luxury, ocean view, traditional', '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(3, 4, 4, 'Beauty by Sarah', 'Expert makeup artist specializing in bridal makeup. Creating stunning looks that enhance your natural beauty for your special day.', '2000000.00', '8000000.00', 'Surabaya', '+62812345681', '+62812345681', '@beautybysarah', NULL, '4.00', 1, 1, 1, 'active', 'bridal makeup, wedding makeup, beauty, surabaya', '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(4, 5, 3, 'Perfect Day Videography', 'Cinematic wedding videography that captures the emotions and beauty of your wedding day. Using latest technology for stunning 4K quality.', '8000000.00', '30000000.00', 'Bandung', '+62812345682', '+62812345682', '@perfectday.video', 'https://perfectday.video', '5.00', 1, 1, 0, 'active', 'wedding videography, cinematic, 4k, emotional, bandung', '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(5, 6, 5, 'Royal Bridal House', 'Premium bridal boutique offering custom wedding gowns and accessories. Featuring both international and local designer collections.', '15000000.00', '50000000.00', 'Jakarta Utara', '+62812345683', '+62812345683', '@royalbridal', 'https://royalbridal.co.id', '4.50', 67, 1, 1, 'active', 'wedding gown, bridal dress, custom, designer, jakarta', '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(6, 7, 6, 'MC Profesional Indonesia', 'Experienced master of ceremony with bilingual capabilities. Making your wedding ceremony and reception memorable and smooth.', '3000000.00', '12000000.00', 'Yogyakarta', '+62812345684', '+62812345684', '@mcpro.id', NULL, '4.40', 45, 1, 0, 'active', 'wedding mc, bilingual, ceremony, reception, yogyakarta', '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(7, 8, 7, 'Harmony Entertainment', 'Live band and DJ services for weddings. Providing perfect music atmosphere from ceremony to reception with various music genres.', '5000000.00', '20000000.00', 'Medan', '+62812345685', '+62812345685', '@harmony.entertainment', 'https://harmony-ent.com', '4.30', 78, 0, 0, 'active', 'wedding band, dj, live music, entertainment, medan', '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(8, 9, 2, 'Moments Capture Studio', 'Contemporary wedding photography studio focusing on modern and artistic styles. Capturing authentic emotions and beautiful moments.', '4000000.00', '18000000.00', 'Semarang', '+62812345686', '+62812345686', '@momentscapture', NULL, '4.60', 92, 1, 0, 'active', 'wedding photography, contemporary, artistic, semarang', '2025-07-28 02:13:29', '2025-07-28 02:13:29'),
(9, 10, 1, 'Paradise Garden Venue', 'Beautiful garden wedding venue with natural scenery and outdoor settings. Perfect for couples who love nature and intimate celebrations.', '12000000.00', '40000000.00', 'Malang', '+62812345687', '+62812345687', '@paradise.garden', 'https://paradise-garden.id', '4.70', 34, 0, 1, 'active', 'garden venue, outdoor wedding, nature, intimate, malang', '2025-07-28 02:13:29', '2025-07-28 12:02:08'),
(12, 13, 2, 'Tess', 'tes sekarang juga', '450000.00', '470000.00', 'Medan', '08122830624', '+62812345687', '@tes.garden', 'https://paradise-garden.id', '0.00', 0, 1, 0, 'active', NULL, '2025-07-28 14:14:34', '2025-12-15 14:14:02'),
(13, 4, 1, 'Coba', 'tes', '5000000.00', '6000000.00', 'Jakarta', '08122830624', '+62812345687', '@paradise.garden', 'https://paradise-garden.id', '0.00', 0, 1, 0, 'active', NULL, '2025-12-15 14:17:41', '2025-12-15 14:17:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_user_id_foreign` (`user_id`),
  ADD KEY `cart_items_vendor_package_id_foreign` (`vendor_package_id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversations_user_one_id_foreign` (`user_one_id`),
  ADD KEY `conversations_user_two_id_foreign` (`user_two_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `favorites_user_vendor_unique` (`user_id`,`vendor_profile_id`),
  ADD KEY `favorites_user_id_foreign` (`user_id`),
  ADD KEY `favorites_vendor_profile_id_foreign` (`vendor_profile_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_from_user_id_foreign` (`from_user_id`),
  ADD KEY `messages_to_user_id_foreign` (`to_user_id`),
  ADD KEY `messages_conversation_id_foreign` (`conversation_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_vendor_package_id_foreign` (`vendor_package_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_vendor_profile_id_foreign` (`vendor_profile_id`),
  ADD KEY `reviews_order_id_foreign` (`order_id`);

--
-- Indexes for table `testimonies`
--
ALTER TABLE `testimonies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indexes for table `vendor_availability`
--
ALTER TABLE `vendor_availability`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendor_availability_vendor_date_unique` (`vendor_profile_id`,`date`),
  ADD KEY `vendor_availability_vendor_profile_id_foreign` (`vendor_profile_id`);

--
-- Indexes for table `vendor_categories`
--
ALTER TABLE `vendor_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendor_categories_slug_unique` (`slug`);

--
-- Indexes for table `vendor_offers`
--
ALTER TABLE `vendor_offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_offers_user_id_foreign` (`user_id`);

--
-- Indexes for table `vendor_packages`
--
ALTER TABLE `vendor_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_packages_vendor_profile_id_foreign` (`vendor_profile_id`);

--
-- Indexes for table `vendor_portfolio_images`
--
ALTER TABLE `vendor_portfolio_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_portfolio_images_vendor_profile_id_foreign` (`vendor_profile_id`);

--
-- Indexes for table `vendor_profiles`
--
ALTER TABLE `vendor_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_profiles_user_id_foreign` (`user_id`),
  ADD KEY `vendor_profiles_vendor_category_id_foreign` (`vendor_category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `testimonies`
--
ALTER TABLE `testimonies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `vendor_availability`
--
ALTER TABLE `vendor_availability`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `vendor_categories`
--
ALTER TABLE `vendor_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vendor_offers`
--
ALTER TABLE `vendor_offers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_packages`
--
ALTER TABLE `vendor_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `vendor_portfolio_images`
--
ALTER TABLE `vendor_portfolio_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `vendor_profiles`
--
ALTER TABLE `vendor_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_vendor_package_id_foreign` FOREIGN KEY (`vendor_package_id`) REFERENCES `vendor_packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_user_one_id_foreign` FOREIGN KEY (`user_one_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_user_two_id_foreign` FOREIGN KEY (`user_two_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_vendor_profile_id_foreign` FOREIGN KEY (`vendor_profile_id`) REFERENCES `vendor_profiles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_from_user_id_foreign` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_to_user_id_foreign` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_vendor_package_id_foreign` FOREIGN KEY (`vendor_package_id`) REFERENCES `vendor_packages` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_vendor_profile_id_foreign` FOREIGN KEY (`vendor_profile_id`) REFERENCES `vendor_profiles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_availability`
--
ALTER TABLE `vendor_availability`
  ADD CONSTRAINT `vendor_availability_vendor_profile_id_foreign` FOREIGN KEY (`vendor_profile_id`) REFERENCES `vendor_profiles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_offers`
--
ALTER TABLE `vendor_offers`
  ADD CONSTRAINT `vendor_offers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_packages`
--
ALTER TABLE `vendor_packages`
  ADD CONSTRAINT `vendor_packages_vendor_profile_id_foreign` FOREIGN KEY (`vendor_profile_id`) REFERENCES `vendor_profiles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_portfolio_images`
--
ALTER TABLE `vendor_portfolio_images`
  ADD CONSTRAINT `vendor_portfolio_images_vendor_profile_id_foreign` FOREIGN KEY (`vendor_profile_id`) REFERENCES `vendor_profiles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_profiles`
--
ALTER TABLE `vendor_profiles`
  ADD CONSTRAINT `vendor_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vendor_profiles_vendor_category_id_foreign` FOREIGN KEY (`vendor_category_id`) REFERENCES `vendor_categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
