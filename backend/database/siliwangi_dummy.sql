SET FOREIGN_KEY_CHECKS=0;

-- 1. MASTER USER & AUTH
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
    `last_login` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`),
    UNIQUE KEY `users_phone_unique` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles` (
    `role_id` bigint(20) unsigned NOT NULL,
    `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `model_id` bigint(20) unsigned NOT NULL,
    PRIMARY KEY (`role_id`,`model_id`,`model_type`),
    KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
    CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. MASTER CABANG
DROP TABLE IF EXISTS `branches`;
CREATE TABLE `branches` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `province` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `google_maps` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `status` boolean NOT NULL DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `branches_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. MASTER MOBIL
-- 3. MASTER MOBIL (Merged brand, type, images, locations, maintenances, inspections)
DROP TABLE IF EXISTS `cars`;
CREATE TABLE `cars` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `branch_id` bigint(20) unsigned NOT NULL,
    `car_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `plate_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `year` year(4) NOT NULL,
    `color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `seat` int(11) NOT NULL DEFAULT 4,
    `transmission` enum('Manual','Automatic') COLLATE utf8mb4_unicode_ci NOT NULL,
    `fuel_type` enum('Bensin','Diesel','Listrik') COLLATE utf8mb4_unicode_ci NOT NULL,
    `daily_price` decimal(15,2) NOT NULL,
    `monthly_price` decimal(15,2) NOT NULL,
    `late_fee` decimal(15,2) NOT NULL,
    `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `status` enum('available','rented','maintenance') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
    `is_available` boolean NOT NULL DEFAULT 1,
    `featured` boolean NOT NULL DEFAULT 0,
    
    -- Additional fields from newer migrations:
    `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Pribadi',
    `mileage` int(11) NOT NULL DEFAULT 0,
    `stnk_expiry` date DEFAULT NULL,
    `tax_expiry` date DEFAULT NULL,
    `stock` int(11) NOT NULL DEFAULT 1,
    `driver_daily_price` decimal(15,2) DEFAULT 0,
    `is_call_for_price` boolean NOT NULL DEFAULT 0,

    -- Merged car_brands columns:
    `brand_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `brand_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `brand_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,

    -- Merged car_types columns:
    `type_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `type_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,

    -- Merged car_images (JSON array of gallery images):
    `images` json DEFAULT NULL,

    -- Merged car_locations (Current/latest coordinates & log):
    `latitude` decimal(10,8) DEFAULT NULL,
    `longitude` decimal(11,8) DEFAULT NULL,
    `speed` decimal(5,2) DEFAULT NULL,
    `location_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `location_raw_data` json DEFAULT NULL,

    -- Merged car_maintenances (JSON array of maintenance logs):
    `maintenances` json DEFAULT NULL,

    -- Merged car_inspections (JSON array of inspections):
    `inspections` json DEFAULT NULL,

    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `cars_slug_unique` (`slug`),
    UNIQUE KEY `cars_plate_number_unique` (`plate_number`),
    KEY `cars_branch_id_foreign` (`branch_id`),
    CONSTRAINT `cars_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 4. CUSTOMER
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint(20) unsigned DEFAULT NULL,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `identity_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `identity_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `customers_user_id_foreign` (`user_id`),
    CONSTRAINT `customers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. DRIVER
DROP TABLE IF EXISTS `drivers`;
CREATE TABLE `drivers` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint(20) unsigned DEFAULT NULL,
    `branch_id` bigint(20) unsigned NOT NULL,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `license_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `daily_fee` decimal(15,2) NOT NULL,
    `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
    `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
    `is_available` boolean NOT NULL DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `drivers_user_id_foreign` (`user_id`),
    KEY `drivers_branch_id_foreign` (`branch_id`),
    CONSTRAINT `drivers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `drivers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. PROMO
DROP TABLE IF EXISTS `promos`;
CREATE TABLE `promos` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `discount_type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci NOT NULL,
    `discount_value` decimal(15,2) NOT NULL,
    `minimum_transaction` decimal(15,2) DEFAULT 0,
    `start_date` date NOT NULL,
    `end_date` date NOT NULL,
    `quota` int(11) NOT NULL DEFAULT 0,
    `used` int(11) NOT NULL DEFAULT 0,
    `status` boolean NOT NULL DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `promos_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. BOOKING
DROP TABLE IF EXISTS `bookings`;
CREATE TABLE `bookings` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `booking_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `customer_id` bigint(20) unsigned NOT NULL,
    `car_id` bigint(20) unsigned NOT NULL,
    `driver_id` bigint(20) unsigned DEFAULT NULL,
    `branch_id` bigint(20) unsigned NOT NULL,
    `promo_id` bigint(20) unsigned DEFAULT NULL,
    `booking_type` enum('daily','monthly') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'daily',
    `pickup_date` datetime NOT NULL,
    `return_date` datetime NOT NULL,
    `pickup_location` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `return_location` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `total_day` int(11) NOT NULL,
    `price` decimal(15,2) NOT NULL,
    `driver_price` decimal(15,2) DEFAULT 0,
    `extra_price` decimal(15,2) DEFAULT 0,
    `late_fee` decimal(15,2) DEFAULT 0,
    `discount` decimal(15,2) DEFAULT 0,
    `tax` decimal(15,2) DEFAULT 0,
    `grand_total` decimal(15,2) NOT NULL,
    `dp_amount` decimal(15,2) DEFAULT 0,
    `remaining_payment` decimal(15,2) DEFAULT 0,
    `payment_status` enum('unpaid','partial','paid','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
    `booking_status` enum('pending','confirmed','ongoing','completed','cancelled','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
    `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `expired_at` datetime DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `bookings_booking_code_unique` (`booking_code`),
    KEY `bookings_customer_id_foreign` (`customer_id`),
    KEY `bookings_car_id_foreign` (`car_id`),
    KEY `bookings_driver_id_foreign` (`driver_id`),
    KEY `bookings_branch_id_foreign` (`branch_id`),
    KEY `bookings_promo_id_foreign` (`promo_id`),
    CONSTRAINT `bookings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
    CONSTRAINT `bookings_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE,
    CONSTRAINT `bookings_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL,
    CONSTRAINT `bookings_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
    CONSTRAINT `bookings_promo_id_foreign` FOREIGN KEY (`promo_id`) REFERENCES `promos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `driver_schedules`;
CREATE TABLE `driver_schedules` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `driver_id` bigint(20) unsigned NOT NULL,
    `booking_id` bigint(20) unsigned NOT NULL,
    `start_date` datetime NOT NULL,
    `end_date` datetime NOT NULL,
    `status` enum('scheduled','ongoing','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `driver_schedules_driver_id_foreign` (`driver_id`),
    KEY `driver_schedules_booking_id_foreign` (`booking_id`),
    CONSTRAINT `driver_schedules_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE,
    CONSTRAINT `driver_schedules_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. PAYMENT
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `booking_id` bigint(20) unsigned NOT NULL,
    `payment_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `snap_token` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `gross_amount` decimal(15,2) NOT NULL,
    `paid_amount` decimal(15,2) DEFAULT 0,
    `payment_status` enum('pending','success','failed','expired','refund') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
    `payment_date` datetime DEFAULT NULL,
    `proof_payment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `midtrans_response` json DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `payments_payment_code_unique` (`payment_code`),
    KEY `payments_booking_id_foreign` (`booking_id`),
    CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `payment_logs`;
CREATE TABLE `payment_logs` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `payment_id` bigint(20) unsigned NOT NULL,
    `response` json DEFAULT NULL,
    `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `payment_logs_payment_id_foreign` (`payment_id`),
    CONSTRAINT `payment_logs_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. REVIEW
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `booking_id` bigint(20) unsigned NOT NULL,
    `customer_id` bigint(20) unsigned NOT NULL,
    `car_id` bigint(20) unsigned NOT NULL,
    `rating` int(11) NOT NULL DEFAULT 5,
    `review` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `status` boolean NOT NULL DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `reviews_booking_id_foreign` (`booking_id`),
    KEY `reviews_customer_id_foreign` (`customer_id`),
    KEY `reviews_car_id_foreign` (`car_id`),
    CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
    CONSTRAINT `reviews_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
    CONSTRAINT `reviews_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. WEBSITE CONTENT
DROP TABLE IF EXISTS `banners`;
CREATE TABLE `banners` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `subtitle` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `button_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `status` boolean NOT NULL DEFAULT 1,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE `testimonials` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `job` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `testimonial` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `rating` int(11) NOT NULL DEFAULT 5,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. NOTIFICATION & CHATBOT
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint(20) unsigned NOT NULL,
    `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `is_read` boolean NOT NULL DEFAULT 0,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `notifications_user_id_foreign` (`user_id`),
    CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `whatsapp_logs`;
CREATE TABLE `whatsapp_logs` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `customer_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `response` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. LOG SYSTEM
DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE `activity_log` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `subject_id` bigint(20) unsigned DEFAULT NULL,
    `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `causer_id` bigint(20) unsigned DEFAULT NULL,
    `properties` json DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `activity_log_log_name_index` (`log_name`),
    KEY `subject` (`subject_type`,`subject_id`),
    KEY `causer` (`causer_type`,`causer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- DUMMY DATA INSERTS
-- --------------------------------------------------------

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super-admin', 'web', NOW(), NOW()),
(2, 'owner', 'web', NOW(), NOW()),
(3, 'customer', 'web', NOW(), NOW()),
(4, 'finance', 'web', NOW(), NOW()),
(5, 'driver', 'web', NOW(), NOW()),
(6, 'admin', 'web', NOW(), NOW()),
(7, 'staff-operasional', 'web', NOW(), NOW()),
(8, 'it-support', 'web', NOW(), NOW());

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@siliwangi.com', '081200000001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', NOW(), NOW()),
(2, 'Owner', 'owner@siliwangi.com', '081200000002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', NOW(), NOW()),
(3, 'Budi Customer', 'budi@gmail.com', '081200000003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', NOW(), NOW()),
(4, 'Siti Finance', 'finance@siliwangi.com', '081200000004', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', NOW(), NOW()),
(5, 'Asep Supir', 'asep@siliwangi.com', '081200000005', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', NOW(), NOW());

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 4),
(5, 'App\\Models\\User', 5);

INSERT INTO `branches` (`id`, `name`, `slug`, `phone`, `email`, `address`, `city`, `province`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Pusat Jakarta', 'pusat-jakarta', '021-1234567', 'jkt@siliwangi.com', 'Jl. Jend. Sudirman No. 1', 'Jakarta Selatan', 'DKI Jakarta', 1, NOW(), NOW()),
(2, 'Cabang Bandung', 'cabang-bandung', '022-7654321', 'bdg@siliwangi.com', 'Jl. Asia Afrika No. 100', 'Bandung', 'Jawa Barat', 1, NOW(), NOW());

INSERT INTO `cars` (
    `id`, `branch_id`, `car_name`, `slug`, `plate_number`, `year`, `color`, `seat`, 
    `transmission`, `fuel_type`, `daily_price`, `monthly_price`, `late_fee`, `status`, `is_available`, `featured`,
    `category`, `mileage`, `stnk_expiry`, `tax_expiry`, `stock`, `driver_daily_price`, `is_call_for_price`,
    `brand_name`, `brand_slug`, `brand_logo`,
    `type_name`, `type_description`,
    `images`, `latitude`, `longitude`, `speed`, `location_address`, `location_raw_data`,
    `maintenances`, `inspections`,
    `created_at`, `updated_at`
) VALUES
(1, 1, 'Toyota Avanza Veloz', 'toyota-avanza-veloz', 'B 1234 ABC', 2023, 'Putih', 7, 'Automatic', 'Bensin', 350000.00, 7000000.00, 35000.00, 'available', 1, 1, 'Pribadi', 15000, '2028-05-15', '2027-05-15', 1, 150000.00, 0, 'Toyota', 'toyota', 'images/brands/toyota.png', 'MPV', 'Multi Purpose Vehicle', '["images/cars/avanza_1.jpg", "images/cars/avanza_2.jpg"]', -6.20876340, 106.84559900, 0.00, 'Jl. Jend. Sudirman, Jakarta Central', NULL, '[]', '[]', NOW(), NOW()),
(2, 1, 'Honda CR-V', 'honda-cr-v', 'B 5678 DEF', 2022, 'Hitam', 5, 'Automatic', 'Bensin', 750000.00, 15000000.00, 75000.00, 'rented', 1, 1, 'Pribadi', 25000, '2027-06-20', '2026-06-20', 1, 200000.00, 0, 'Honda', 'honda', 'images/brands/honda.png', 'SUV', 'Sport Utility Vehicle', '["images/cars/crv_1.jpg"]', -6.21462000, 106.84021000, 60.50, 'Tol Dalam Kota Jakarta', NULL, '[]', '[]', NOW(), NOW()),
(3, 2, 'Mitsubishi Xpander', 'mitsubishi-xpander', 'D 9012 GHI', 2023, 'Silver', 7, 'Automatic', 'Bensin', 400000.00, 8000000.00, 40000.00, 'available', 1, 0, 'Pribadi', 18000, '2028-01-10', '2027-01-10', 1, 150000.00, 0, 'Mitsubishi', 'mitsubishi', 'images/brands/mitsubishi.png', 'MPV', 'Multi Purpose Vehicle', '["images/cars/xpander_1.jpg"]', -6.91746400, 107.61912200, 0.00, 'Jl. Asia Afrika, Bandung', NULL, '[]', '[]', NOW(), NOW()),
(4, 2, 'Toyota Fortuner GR', 'toyota-fortuner-gr', 'D 3456 JKL', 2024, 'Hitam', 7, 'Automatic', 'Diesel', 1200000.00, 24000000.00, 120000.00, 'maintenance', 1, 1, 'Pribadi', 8000, '2029-03-01', '2028-03-01', 1, 250000.00, 0, 'Toyota', 'toyota', 'images/brands/toyota.png', 'SUV', 'Sport Utility Vehicle', '["images/cars/fortuner_1.jpg"]', -6.92012000, 107.60453000, 0.00, 'Cabang Bandung Servis Area', NULL, '[{"type": "Periodic Service", "cost": 500000, "date": "2026-05-01", "status": "completed", "notes": "Ganti oli dan filter"}]', '[]', NOW(), NOW()),
(5, 1, 'Daihatsu Ayla', 'daihatsu-ayla', 'B 1111 AAA', 2021, 'Merah', 5, 'Manual', 'Bensin', 250000.00, 5000000.00, 25000.00, 'available', 1, 0, 'Pribadi', 45000, '2026-11-12', '2026-11-12', 1, 120000.00, 0, 'Daihatsu', 'daihatsu', 'images/brands/daihatsu.png', 'City Car', 'Compact City Car', '[]', -6.17539240, 106.82715280, 0.00, 'Jl. Merdeka Barat, Jakarta', NULL, '[]', '[]', NOW(), NOW()),
(6, 1, 'Honda Brio RS', 'honda-brio-rs', 'B 2222 BBB', 2023, 'Kuning', 5, 'Automatic', 'Bensin', 300000.00, 6000000.00, 30000.00, 'available', 1, 1, 'Pribadi', 12000, '2028-09-15', '2027-09-15', 1, 130000.00, 0, 'Honda', 'honda', 'images/brands/honda.png', 'City Car', 'Compact City Car', '[]', -6.19312000, 106.82190000, 0.00, 'Thamrin, Jakarta', NULL, '[]', '[]', NOW(), NOW()),
(7, 2, 'Toyota Alphard', 'toyota-alphard', 'D 3333 CCC', 2024, 'Hitam', 7, 'Automatic', 'Bensin', 2000000.00, 40000000.00, 200000.00, 'available', 1, 1, 'Perusahaan', 5000, '2029-02-28', '2028-02-28', 1, 300000.00, 0, 'Toyota', 'toyota', 'images/brands/toyota.png', 'MPV', 'Multi Purpose Vehicle', '[]', -6.91474400, 107.60981000, 40.00, 'Jl. Dago, Bandung', NULL, '[]', '[]', NOW(), NOW()),
(8, 2, 'Suzuki Ertiga Hybrid', 'suzuki-ertiga-hybrid', 'D 4444 DDD', 2023, 'Abu-abu', 7, 'Automatic', 'Bensin', 350000.00, 7000000.00, 35000.00, 'available', 1, 0, 'Pribadi', 16000, '2028-07-20', '2027-07-20', 1, 150000.00, 0, 'Suzuki', 'suzuki', 'images/brands/suzuki.png', 'MPV', 'Multi Purpose Vehicle', '[]', -6.90389000, 107.61861000, 0.00, 'Gedung Sate, Bandung', NULL, '[]', '[]', NOW(), NOW()),
(9, 1, 'Toyota Hiace Commuter', 'toyota-hiace', 'B 5555 EEE', 2022, 'Putih', 15, 'Manual', 'Diesel', 1500000.00, 30000000.00, 150000.00, 'available', 1, 0, 'Perusahaan', 30000, '2027-10-10', '2026-10-10', 1, 200000.00, 0, 'Toyota', 'toyota', 'images/brands/toyota.png', 'Minibus', 'Large Capacity Minibus', '[]', -6.20000000, 106.80000000, 0.00, 'Kuningan, Jakarta', NULL, '[]', '[]', NOW(), NOW()),
(10, 1, 'Honda Civic Turbo', 'honda-civic-turbo', 'B 6666 FFF', 2023, 'Putih', 5, 'Automatic', 'Bensin', 1000000.00, 20000000.00, 100000.00, 'rented', 1, 1, 'Pribadi', 10000, '2028-12-25', '2027-12-25', 1, 250000.00, 0, 'Honda', 'honda', 'images/brands/honda.png', 'Sedan', 'Sedan Car', '[]', -6.18000000, 106.83000000, 75.00, 'Harmoni, Jakarta', NULL, '[]', '[]', NOW(), NOW());

INSERT INTO `customers` (`id`, `user_id`, `name`, `email`, `phone`, `identity_number`, `address`, `created_at`, `updated_at`) VALUES
(1, 3, 'Budi Customer', 'budi@gmail.com', '081200000003', '3171234567890001', 'Jl. Merdeka No 45, Jakarta', NOW(), NOW());

INSERT INTO `drivers` (`id`, `user_id`, `branch_id`, `name`, `phone`, `license_number`, `daily_fee`, `rating`, `status`, `is_available`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 'Asep Supir', '081200000005', '123456789012', 150000, 4.8, 'active', 1, NOW(), NOW()),
(2, NULL, 2, 'Ujang Santoso', '081299998888', '987654321098', 150000, 4.5, 'active', 1, NOW(), NOW());

INSERT INTO `promos` (`id`, `code`, `title`, `discount_type`, `discount_value`, `minimum_transaction`, `start_date`, `end_date`, `quota`, `used`, `status`, `created_at`, `updated_at`) VALUES
(1, 'WELCOME20', 'Diskon Pengguna Baru', 'percentage', 20.00, 500000, '2026-01-01', '2026-12-31', 100, 10, 1, NOW(), NOW()),
(2, 'MUDIK50K', 'Diskon Lebaran', 'fixed', 50000.00, 300000, '2026-03-01', '2026-05-31', 50, 5, 1, NOW(), NOW());

INSERT INTO `bookings` (`id`, `booking_code`, `customer_id`, `car_id`, `driver_id`, `branch_id`, `promo_id`, `booking_type`, `pickup_date`, `return_date`, `pickup_location`, `return_location`, `total_day`, `price`, `driver_price`, `extra_price`, `late_fee`, `discount`, `tax`, `grand_total`, `dp_amount`, `remaining_payment`, `payment_status`, `booking_status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'TRX-202605-0001', 1, 1, NULL, 1, NULL, 'daily', DATE_ADD(NOW(), INTERVAL -2 DAY), DATE_ADD(NOW(), INTERVAL 1 DAY), 'Kantor Pusat', 'Kantor Pusat', 3, 1050000, 0, 0, 0, 0, 105000, 1155000, 1155000, 0, 'paid', 'ongoing', 'Tolong cuci bersih sebelum pick up', NOW(), NOW()),
(2, 'TRX-202605-0002', 1, 2, 1, 1, 1, 'daily', DATE_ADD(NOW(), INTERVAL 5 DAY), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Bandara Soekarno Hatta', 'Kantor Pusat', 2, 1500000, 300000, 0, 0, 300000, 150000, 1650000, 500000, 1150000, 'partial', 'confirmed', 'Jemput di Terminal 3', NOW(), NOW());

INSERT INTO `payments` (`id`, `booking_id`, `payment_code`, `payment_method`, `transaction_id`, `gross_amount`, `paid_amount`, `payment_status`, `payment_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'PAY-202605-0001', 'bank_transfer', 'MID-123456789', 1155000, 1155000, 'success', NOW(), NOW(), NOW()),
(2, 2, 'PAY-202605-0002', 'qris', 'MID-987654321', 1650000, 500000, 'success', NOW(), NOW(), NOW());

INSERT INTO `reviews` (`id`, `booking_id`, `customer_id`, `car_id`, `rating`, `review`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 5, 'Mobil sangat bersih dan wangi, mesin juga prima!', 1, NOW(), NOW());

SET FOREIGN_KEY_CHECKS=1;
