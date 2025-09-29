/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `manajemen_asset` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `manajemen_asset`;

CREATE TABLE IF NOT EXISTS `assets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `asset_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `vendor_id` bigint unsigned DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `location_id` bigint unsigned DEFAULT NULL,
  `employee_id` bigint unsigned DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `purchase_price` decimal(15,2) DEFAULT NULL,
  `depreciation_val` decimal(15,2) DEFAULT NULL,
  `status` enum('active','borrowed','repair','disposed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `assets_asset_code_unique` (`asset_code`),
  KEY `assets_company_id_foreign` (`company_id`),
  KEY `assets_category_id_foreign` (`category_id`),
  KEY `assets_vendor_id_foreign` (`vendor_id`),
  KEY `assets_department_id_foreign` (`department_id`),
  KEY `assets_location_id_foreign` (`location_id`),
  KEY `assets_employee_id_foreign` (`employee_id`),
  CONSTRAINT `assets_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `assets_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assets_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `assets_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  CONSTRAINT `assets_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `assets_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `assets` (`id`, `company_id`, `asset_code`, `name`, `category_id`, `vendor_id`, `department_id`, `location_id`, `employee_id`, `purchase_date`, `purchase_price`, `depreciation_val`, `status`, `serial_number`, `image`, `notes`, `created_at`, `updated_at`) VALUES
	(1, 3, 'IT-20250928-0001', 'laptop lenovo', 1, 1, 1, 2, 2, '2025-09-28', 200000.00, 20000.00, 'disposed', 'ds35f4s3d21f', '01K679BHDAYNAF8JEFDW9NM6G9.jpeg', '<p>speck core i3&nbsp;</p><p>ram 8gb</p>', '2025-09-27 21:30:04', '2025-09-27 23:58:49');

CREATE TABLE IF NOT EXISTS `asset_audits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` bigint unsigned NOT NULL,
  `audit_date` date NOT NULL,
  `auditor_id` bigint unsigned DEFAULT NULL,
  `condition` enum('good','damaged','missing') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'good',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_audits_asset_id_foreign` (`asset_id`),
  KEY `asset_audits_auditor_id_foreign` (`auditor_id`),
  CONSTRAINT `asset_audits_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `asset_audits_auditor_id_foreign` FOREIGN KEY (`auditor_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `asset_audits` (`id`, `asset_id`, `audit_date`, `auditor_id`, `condition`, `remarks`, `created_at`, `updated_at`) VALUES
	(1, 1, '2025-09-28', 2, 'missing', 'hilang tidak tau kemana', '2025-09-28 00:17:39', '2025-09-28 00:17:39');

CREATE TABLE IF NOT EXISTS `asset_disposals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` bigint unsigned NOT NULL,
  `disposal_date` date NOT NULL,
  `method` enum('sold','scrapped','donated') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(15,2) DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_disposals_asset_id_foreign` (`asset_id`),
  CONSTRAINT `asset_disposals_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `asset_disposals` (`id`, `asset_id`, `disposal_date`, `method`, `value`, `reason`, `created_at`, `updated_at`) VALUES
	(1, 1, '2025-09-28', 'donated', 0.00, 'tidak layak pakai', '2025-09-27 23:58:49', '2025-09-27 23:58:49');

CREATE TABLE IF NOT EXISTS `asset_maintenances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` bigint unsigned NOT NULL,
  `maintenance_date` date NOT NULL,
  `next_maintenance` date DEFAULT NULL,
  `cost` decimal(15,2) DEFAULT NULL,
  `vendor_id` bigint unsigned DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('scheduled','in_progress','done') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_maintenances_asset_id_foreign` (`asset_id`),
  KEY `asset_maintenances_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `asset_maintenances_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `asset_maintenances_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `asset_maintenances` (`id`, `asset_id`, `maintenance_date`, `next_maintenance`, `cost`, `vendor_id`, `description`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, '2025-09-28', '2025-09-30', 200000.00, 1, 'asdsadasdas', 'in_progress', '2025-09-27 22:44:50', '2025-09-27 22:57:43');

CREATE TABLE IF NOT EXISTS `asset_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` bigint unsigned NOT NULL,
  `transaction_type` enum('borrow','return','transfer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_company_id` bigint unsigned DEFAULT NULL,
  `to_company_id` bigint unsigned DEFAULT NULL,
  `from_employee_id` bigint unsigned DEFAULT NULL,
  `to_employee_id` bigint unsigned DEFAULT NULL,
  `from_location_id` bigint unsigned DEFAULT NULL,
  `to_location_id` bigint unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_transactions_asset_id_foreign` (`asset_id`),
  KEY `asset_transactions_from_company_id_foreign` (`from_company_id`),
  KEY `asset_transactions_to_company_id_foreign` (`to_company_id`),
  KEY `asset_transactions_from_employee_id_foreign` (`from_employee_id`),
  KEY `asset_transactions_to_employee_id_foreign` (`to_employee_id`),
  KEY `asset_transactions_from_location_id_foreign` (`from_location_id`),
  KEY `asset_transactions_to_location_id_foreign` (`to_location_id`),
  CONSTRAINT `asset_transactions_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `asset_transactions_from_company_id_foreign` FOREIGN KEY (`from_company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  CONSTRAINT `asset_transactions_from_employee_id_foreign` FOREIGN KEY (`from_employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  CONSTRAINT `asset_transactions_from_location_id_foreign` FOREIGN KEY (`from_location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `asset_transactions_to_company_id_foreign` FOREIGN KEY (`to_company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL,
  CONSTRAINT `asset_transactions_to_employee_id_foreign` FOREIGN KEY (`to_employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  CONSTRAINT `asset_transactions_to_location_id_foreign` FOREIGN KEY (`to_location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `asset_transactions` (`id`, `asset_id`, `transaction_type`, `from_company_id`, `to_company_id`, `from_employee_id`, `to_employee_id`, `from_location_id`, `to_location_id`, `date`, `note`, `created_at`, `updated_at`) VALUES
	(1, 1, 'return', 1, 3, 1, 2, 1, 2, '2025-09-28', '<p>sadsadsadsad</p>', '2025-09-27 21:57:46', '2025-09-27 22:15:14');

CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1759033860),
	('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1759033860;', 1759033860),
	('laravel-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1759024911),
	('laravel-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1759024911;', 1759024911);

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'IT', 'KATEGORI IT\n', '2025-09-27 19:31:17', '2025-09-27 19:31:17'),
	(2, 'ATK', 'KATEGORY ATK', '2025-09-27 19:31:26', '2025-09-27 19:31:26'),
	(3, 'FURNITURE', 'FURNITUR', '2025-09-27 19:31:48', '2025-09-27 19:31:48'),
	(4, 'KENDARAAN', 'KENDARAAN', '2025-09-27 19:32:01', '2025-09-27 19:32:01');

CREATE TABLE IF NOT EXISTS `companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `companies_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `companies` (`id`, `name`, `address`, `phone`, `email`, `created_at`, `updated_at`) VALUES
	(1, 'PT SATONA', 'SBY', '09', 'satona@gmail.com', '2025-09-27 19:25:55', '2025-09-27 19:25:55'),
	(2, 'PT ROTAMA', 'SBY', '09', 'rotama@gmail.com', '2025-09-27 19:26:23', '2025-09-27 19:26:23'),
	(3, 'PT ROASI SINEGI INDUSTRI', 'SBY', '2040', 'roasi@gmail.com', '2025-09-27 19:26:44', '2025-09-27 19:26:44'),
	(4, 'PT RODAME INDONESIA', 'BLITAR', '029430', 'rodame@gmail.com', '2025-09-27 19:27:04', '2025-09-27 19:27:04');

CREATE TABLE IF NOT EXISTS `consumable_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `item_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `stock_qty` int NOT NULL DEFAULT '0',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `consumable_items_item_code_unique` (`item_code`),
  KEY `consumable_items_company_id_foreign` (`company_id`),
  KEY `consumable_items_category_id_foreign` (`category_id`),
  KEY `consumable_items_location_id_foreign` (`location_id`),
  CONSTRAINT `consumable_items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `consumable_items_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `consumable_items_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `consumable_items` (`id`, `company_id`, `item_code`, `name`, `category_id`, `stock_qty`, `unit`, `location_id`, `created_at`, `updated_at`) VALUES
	(1, 2, 'CON-0001', 'pencil', 3, 1000, 'pcs', 1, '2025-09-28 00:33:16', '2025-09-28 15:58:22');

CREATE TABLE IF NOT EXISTS `consumable_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `item_id` bigint unsigned NOT NULL,
  `employee_id` bigint unsigned DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `qty` int NOT NULL,
  `type` enum('in','out','adjust') COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `consumable_transactions_item_id_foreign` (`item_id`),
  KEY `consumable_transactions_employee_id_foreign` (`employee_id`),
  KEY `consumable_transactions_department_id_foreign` (`department_id`),
  CONSTRAINT `consumable_transactions_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `consumable_transactions_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  CONSTRAINT `consumable_transactions_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `consumable_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `consumable_transactions` (`id`, `item_id`, `employee_id`, `department_id`, `date`, `qty`, `type`, `note`, `created_at`, `updated_at`) VALUES
	(2, 1, 2, 1, '2025-09-28', 1000, 'in', 'pencil ditambah', '2025-09-28 15:57:18', '2025-09-28 15:57:18'),
	(3, 1, 2, 2, '2025-09-28', 900, 'out', 'keluar', '2025-09-28 15:57:59', '2025-09-28 15:58:22');

CREATE TABLE IF NOT EXISTS `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `departments_company_id_foreign` (`company_id`),
  CONSTRAINT `departments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `departments` (`id`, `company_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 1, 'IT', 'IT\n', '2025-09-27 19:38:28', '2025-09-27 19:38:28'),
	(2, 1, 'KEUANGAN', 'KEUANGAN SATONA', '2025-09-27 19:38:40', '2025-09-27 19:38:40'),
	(3, 1, 'KOMERSIAL', 'KOMERSIAL SATONA', '2025-09-27 19:38:56', '2025-09-27 19:38:56'),
	(4, 2, 'KOMERSIAL ROTAMA', 'KOMERSIAL ROTAMA', '2025-09-27 19:39:16', '2025-09-27 19:39:16');

CREATE TABLE IF NOT EXISTS `employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employees_company_id_foreign` (`company_id`),
  KEY `employees_department_id_foreign` (`department_id`),
  CONSTRAINT `employees_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `employees` (`id`, `company_id`, `name`, `department_id`, `position`, `email`, `phone`, `created_at`, `updated_at`) VALUES
	(1, 1, 'DENY WIJAYA', 1, 'MANAJER IT', 'DENY@GMAIL.COM', '029480324', '2025-09-27 19:42:08', '2025-09-27 19:42:08'),
	(2, 1, 'HERMAN ROSADI', 2, 'ASMEN KEUANGAN', 'HERMAN@GMAIL.COM', '0394803294', '2025-09-27 19:43:18', '2025-09-27 19:43:18');

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `building` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `floor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `room` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `locations_company_id_foreign` (`company_id`),
  CONSTRAINT `locations_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `locations` (`id`, `company_id`, `name`, `building`, `floor`, `room`, `created_at`, `updated_at`) VALUES
	(1, 1, 'RUANGAN IT', 'KANTOR SATONA', 'RUANG IT', 'RUANGAN IT', '2025-09-27 19:40:27', '2025-09-27 19:40:27'),
	(2, 1, 'RUANGAN KEUANGAN', 'KANTOR SATONA', 'RUANGAN KEUANGAN', 'RUANGAN KEUANGAN', '2025-09-27 19:40:46', '2025-09-27 19:40:46');

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_09_28_020447_create_companies_table', 2),
	(5, '2025_09_28_020554_create_categories_table', 2),
	(6, '2025_09_28_020700_create_departments_table', 3),
	(7, '2025_09_28_020748_create_locations_table', 4),
	(8, '2025_09_28_020824_create_vendors_table', 5),
	(9, '2025_09_28_020859_create_employees_table', 6),
	(12, '2025_09_28_021350_create_asset_transactions_table', 8),
	(13, '2025_09_28_021504_create_asset_maintenances_table', 9),
	(14, '2025_09_28_021553_create_asset_disposals_table', 10),
	(15, '2025_09_28_021626_create_asset_audits_table', 11),
	(16, '2025_09_28_021721_create_consumable_items_table', 12),
	(17, '2025_09_28_021803_create_consumable_transactions_table', 13),
	(18, '2025_09_28_021131_create_assets_table', 14),
	(19, '2025_09_28_222021_add_department_id_to_consumable_transactions_table', 15);

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('SxNkOIrVJ5xQD6V8zv1dIMooZ5wVRkxACfGLIKlY', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiMzloenU2VWZtTWY4OHZjazFvQ1ZwclltVGIzcjZHemtybGlXc2VUdSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9jb25zdW1hYmxlLWl0ZW1zIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJDJkVVhoVldYblhURzlFendLSmYuSE9KemoyV2tseWc2Ny9OMVFFcndIdlAudXQwZHJLVThlIjtzOjg6ImZpbGFtZW50IjthOjA6e319', 1759046148),
	('WQZAjLIVC4KkiJSVils892W3hzTJJobbyfLLUvmX', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiWmx5ekdqQ2dzMlRLMEZXWUlXU3pZY3Z5M3RLNnN3VENBZlprQUVnNCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hc3NldHMvMSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiQyZFVYaFZXWG5YVEc5RXp3S0pmLkhPSnpqMldrbHlnNjcvTjFRRXJ3SHZQLnV0MGRyS1U4ZSI7czo4OiJmaWxhbWVudCI7YTowOnt9fQ==', 1759104126);

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'admin@gmail.com', NULL, '$2y$12$2dUXhVWXnXTG9EzwKJf.HOJzj2Wklyg67/N1QErwHvP.ut0drKU8e', '2io7XTGzB516HMjTLEnQaJFuE8u3BRIDakJDVXaRVF0eHpbmucIgVZdpsJ0G', '2025-09-27 19:00:02', '2025-09-27 19:00:02');

CREATE TABLE IF NOT EXISTS `vendors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `vendors` (`id`, `name`, `contact_person`, `phone`, `email`, `created_at`, `updated_at`) VALUES
	(1, 'PT AYU IT TEKNOLOGI INDO', 'MIRA', '12349129', 'MIRA@GMAIL.COM', '2025-09-27 19:30:46', '2025-09-27 19:30:46');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
