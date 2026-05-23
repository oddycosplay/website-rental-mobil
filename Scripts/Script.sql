
-- 1. users
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `last_login` TIMESTAMP NULL DEFAULT NULL,
  `remember_token` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  INDEX `users_phone_index` (`phone`),
  INDEX `users_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. roles
CREATE TABLE `roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `guard_name` VARCHAR(50) NOT NULL DEFAULT 'web',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. model_has_roles
CREATE TABLE `model_has_roles` (
  `role_id` BIGINT UNSIGNED NOT NULL,
  `model_type` VARCHAR(255) NOT NULL,
  `model_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`),
  INDEX `model_has_roles_model_id_model_type_index` (`model_id`, `model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. branches
CREATE TABLE `branches` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `province` VARCHAR(100) DEFAULT NULL,
  `google_maps` TEXT DEFAULT NULL,
  `status` BOOLEAN NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branches_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. car_brands
CREATE TABLE `car_brands` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(150) NOT NULL,
  `logo` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `car_brands_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. car_types
CREATE TABLE `car_types` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. cars
CREATE TABLE `cars` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` BIGINT UNSIGNED NOT NULL,
  `brand_id` BIGINT UNSIGNED NOT NULL,
  `type_id` BIGINT UNSIGNED NOT NULL,
  `car_name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(200) NOT NULL,
  `plate_number` VARCHAR(20) NOT NULL,
  `year` YEAR NOT NULL,
  `color` VARCHAR(50) DEFAULT NULL,
  `seat` INT NOT NULL,
  `transmission` ENUM('manual','automatic') NOT NULL,
  `fuel_type` ENUM('bensin','solar','electric') NOT NULL,
  `daily_price` DECIMAL(15,2) NOT NULL,
  `monthly_price` DECIMAL(15,2) DEFAULT NULL,
  `late_fee` DECIMAL(15,2) DEFAULT NULL,
  `thumbnail` VARCHAR(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `status` ENUM('available','booked','maintenance') NOT NULL DEFAULT 'available',
  `is_available` BOOLEAN NOT NULL DEFAULT 1,
  `featured` BOOLEAN NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cars_slug_unique` (`slug`),
  UNIQUE KEY `cars_plate_number_unique` (`plate_number`),
  CONSTRAINT `cars_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `cars_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `car_brands` (`id`),
  CONSTRAINT `cars_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `car_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. car_images
CREATE TABLE `car_images` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `car_id` BIGINT UNSIGNED NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `is_primary` BOOLEAN NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `car_images_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. customers
CREATE TABLE `customers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `identity_number` VARCHAR(50) DEFAULT NULL,
  `identity_photo` VARCHAR(255) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `customers_email_index` (`email`),
  INDEX `customers_phone_index` (`phone`),
  CONSTRAINT `customers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. customer_documents
CREATE TABLE `customer_documents` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` BIGINT UNSIGNED NOT NULL,
  `document_type` ENUM('ktp','sim','passport','kk','selfie','other') NOT NULL,
  `document_number` VARCHAR(100) DEFAULT NULL,
  `document_file` VARCHAR(255) NOT NULL,
  `verification_status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `verified_by` BIGINT UNSIGNED DEFAULT NULL,
  `verified_at` TIMESTAMP NULL DEFAULT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `customer_documents_document_type_index` (`document_type`),
  INDEX `customer_documents_verification_status_index` (`verification_status`),
  CONSTRAINT `customer_documents_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `customer_documents_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. drivers
CREATE TABLE `drivers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `branch_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `address` TEXT DEFAULT NULL,
  `license_number` VARCHAR(100) NOT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `daily_fee` DECIMAL(15,2) NOT NULL,
  `rating` DECIMAL(3,2) DEFAULT '5.00',
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `is_available` BOOLEAN NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `drivers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `drivers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. promos
CREATE TABLE `promos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `discount_type` ENUM('percent','fixed') NOT NULL,
  `discount_value` DECIMAL(15,2) NOT NULL,
  `minimum_transaction` DECIMAL(15,2) DEFAULT '0.00',
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `quota` INT DEFAULT NULL,
  `used` INT NOT NULL DEFAULT 0,
  `status` BOOLEAN NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `promos_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. bookings
CREATE TABLE `bookings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_code` VARCHAR(50) NOT NULL,
  `customer_id` BIGINT UNSIGNED NOT NULL,
  `car_id` BIGINT UNSIGNED NOT NULL,
  `driver_id` BIGINT UNSIGNED DEFAULT NULL,
  `branch_id` BIGINT UNSIGNED NOT NULL,
  `promo_id` BIGINT UNSIGNED DEFAULT NULL,
  `booking_type` ENUM('daily','monthly') NOT NULL DEFAULT 'daily',
  `pickup_date` DATETIME NOT NULL,
  `return_date` DATETIME NOT NULL,
  `pickup_location` TEXT NOT NULL,
  `return_location` TEXT NOT NULL,
  `total_day` INT NOT NULL,
  `price` DECIMAL(15,2) NOT NULL,
  `driver_price` DECIMAL(15,2) NOT NULL DEFAULT '0.00',
  `extra_price` DECIMAL(15,2) NOT NULL DEFAULT '0.00',
  `late_fee` DECIMAL(15,2) NOT NULL DEFAULT '0.00',
  `discount` DECIMAL(15,2) NOT NULL DEFAULT '0.00',
  `tax` DECIMAL(15,2) NOT NULL DEFAULT '0.00',
  `grand_total` DECIMAL(15,2) NOT NULL,
  `dp_amount` DECIMAL(15,2) NOT NULL DEFAULT '0.00',
  `remaining_payment` DECIMAL(15,2) NOT NULL DEFAULT '0.00',
  `payment_status` ENUM('unpaid','partial','paid') NOT NULL DEFAULT 'unpaid',
  `booking_status` ENUM('pending','paid','confirmed','on_going','completed','cancelled','expired') NOT NULL DEFAULT 'pending',
  `notes` TEXT DEFAULT NULL,
  `expired_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_booking_code_unique` (`booking_code`),
  INDEX `bookings_pickup_date_index` (`pickup_date`),
  INDEX `bookings_return_date_index` (`return_date`),
  INDEX `bookings_customer_id_index` (`customer_id`),
  CONSTRAINT `bookings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `bookings_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`),
  CONSTRAINT `bookings_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`),
  CONSTRAINT `bookings_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `bookings_promo_id_foreign` FOREIGN KEY (`promo_id`) REFERENCES `promos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. driver_schedules
CREATE TABLE `driver_schedules` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `driver_id` BIGINT UNSIGNED NOT NULL,
  `booking_id` BIGINT UNSIGNED NOT NULL,
  `start_date` DATETIME NOT NULL,
  `end_date` DATETIME NOT NULL,
  `status` ENUM('scheduled','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `driver_schedules_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`),
  CONSTRAINT `driver_schedules_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. payments
CREATE TABLE `payments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` BIGINT UNSIGNED NOT NULL,
  `payment_code` VARCHAR(100) NOT NULL,
  `payment_method` VARCHAR(50) DEFAULT NULL,
  `transaction_id` VARCHAR(100) DEFAULT NULL,
  `snap_token` TEXT DEFAULT NULL,
  `gross_amount` DECIMAL(15,2) NOT NULL,
  `paid_amount` DECIMAL(15,2) DEFAULT '0.00',
  `payment_status` ENUM('pending','paid','failed','expired') NOT NULL DEFAULT 'pending',
  `payment_date` DATETIME DEFAULT NULL,
  `proof_payment` VARCHAR(255) DEFAULT NULL,
  `midtrans_response` JSON DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_payment_code_unique` (`payment_code`),
  CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. payment_logs
CREATE TABLE `payment_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_id` BIGINT UNSIGNED NOT NULL,
  `response` JSON DEFAULT NULL,
  `status` VARCHAR(50) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `payment_logs_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. reviews
CREATE TABLE `reviews` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` BIGINT UNSIGNED NOT NULL,
  `customer_id` BIGINT UNSIGNED NOT NULL,
  `car_id` BIGINT UNSIGNED NOT NULL,
  `rating` INT NOT NULL,
  `review` TEXT DEFAULT NULL,
  `status` BOOLEAN NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `reviews_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. notifications
CREATE TABLE `notifications` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `message` TEXT NOT NULL,
  `is_read` BOOLEAN NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. whatsapp_logs
CREATE TABLE `whatsapp_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_phone` VARCHAR(20) NOT NULL,
  `message` TEXT NOT NULL,
  `response` TEXT DEFAULT NULL,
  `status` VARCHAR(50) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 20. banners
CREATE TABLE `banners` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `subtitle` TEXT DEFAULT NULL,
  `image` VARCHAR(255) NOT NULL,
  `button_link` VARCHAR(255) DEFAULT NULL,
  `status` BOOLEAN NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 21. pages
CREATE TABLE `pages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(200) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_description` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 22. testimonials
CREATE TABLE `testimonials` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `job` VARCHAR(100) DEFAULT NULL,
  `photo` VARCHAR(255) DEFAULT NULL,
  `testimonial` TEXT NOT NULL,
  `rating` INT NOT NULL DEFAULT 5,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 23. activity_log
CREATE TABLE `activity_log` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `log_name` VARCHAR(255) DEFAULT NULL,
  `description` TEXT NOT NULL,
  `subject_type` VARCHAR(255) DEFAULT NULL,
  `subject_id` BIGINT UNSIGNED DEFAULT NULL,
  `causer_type` VARCHAR(255) DEFAULT NULL,
  `causer_id` BIGINT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ==========================================================
-- INSERT DUMMY DATA FOR TESTING
-- ==========================================================

INSERT INTO `roles` (`name`, `guard_name`, `created_at`) VALUES 
('admin', 'web', NOW()),
('owner', 'web', NOW()),
('customer', 'web', NOW()),
('finance', 'web', NOW()),
('operational', 'web', NOW());

-- Passwords are set to 'password' (bcrypt hash)
INSERT INTO `users` (`name`, `email`, `phone`, `password`, `status`, `created_at`) VALUES 
('Super Admin', 'admin@siliwangi.com', '081200000001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', NOW()),
('Owner Siliwangi', 'owner@siliwangi.com', '081200000002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', NOW()),
('Budi Customer', 'budi@gmail.com', '081200000003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', NOW()),
('Siti Finance', 'finance@siliwangi.com', '081200000004', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', NOW());

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 4);

INSERT INTO `branches` (`name`, `slug`, `phone`, `email`, `address`, `city`, `province`, `status`, `created_at`) VALUES
('Siliwangi Pusat Bandung', 'siliwangi-pusat-bandung', '08111111111', 'pusat@siliwangi.com', 'Jl. Siliwangi No 1', 'Bandung', 'Jawa Barat', 1, NOW()),
('Siliwangi Cabang Jakarta', 'siliwangi-cabang-jakarta', '08222222222', 'jakarta@siliwangi.com', 'Jl. Sudirman No 10', 'Jakarta Pusat', 'DKI Jakarta', 1, NOW());

INSERT INTO `car_brands` (`name`, `slug`, `created_at`) VALUES
('Toyota', 'toyota', NOW()),
('Honda', 'honda', NOW()),
('Hyundai', 'hyundai', NOW()),
('Mitsubishi', 'mitsubishi', NOW());

INSERT INTO `car_types` (`name`, `description`, `created_at`) VALUES
('SUV', 'Sport Utility Vehicle', NOW()),
('MPV', 'Multi Purpose Vehicle', NOW()),
('Sedan', 'Mobil Sedan', NOW()),
('Minibus', 'Kapasitas besar', NOW());

INSERT INTO `cars` (`branch_id`, `brand_id`, `type_id`, `car_name`, `slug`, `plate_number`, `year`, `color`, `seat`, `transmission`, `fuel_type`, `daily_price`, `monthly_price`, `status`, `is_available`, `featured`, `created_at`) VALUES
(1, 1, 2, 'Toyota Alphard G', 'toyota-alphard-g', 'D 1234 VIP', '2023', 'Black', 7, 'automatic', 'bensin', 2500000.00, 45000000.00, 'available', 1, 1, NOW()),
(1, 1, 2, 'Toyota Innova Zenix', 'toyota-innova-zenix', 'D 5678 JKL', '2023', 'White', 7, 'automatic', 'bensin', 1200000.00, 25000000.00, 'available', 1, 1, NOW()),
(1, 3, 1, 'Hyundai Palisade', 'hyundai-palisade', 'D 9999 SBY', '2023', 'Black', 7, 'automatic', 'solar', 2200000.00, 40000000.00, 'available', 1, 1, NOW()),
(2, 2, 3, 'Honda Civic RS', 'honda-civic-rs', 'B 1111 TST', '2022', 'Red', 5, 'automatic', 'bensin', 1500000.00, 28000000.00, 'available', 1, 0, NOW());

INSERT INTO `customers` (`user_id`, `name`, `email`, `phone`, `identity_number`, `address`, `created_at`) VALUES
(3, 'Budi Customer', 'budi@gmail.com', '081200000003', '3273000011112222', 'Jl. Dipatiukur No. 55', NOW()),
(NULL, 'Andi Wijaya (Guest)', 'andi@gmail.com', '085678901234', '3273000055556666', 'Jl. Gatot Subroto No 1', NOW());

INSERT INTO `drivers` (`user_id`, `branch_id`, `name`, `phone`, `license_number`, `daily_fee`, `status`, `created_at`) VALUES
(NULL, 1, 'Agus Sopian', '08199999991', 'SIM-A-001', 200000.00, 'active', NOW()),
(NULL, 1, 'Dadang Subur', '08199999992', 'SIM-B1-001', 250000.00, 'active', NOW());

SET FOREIGN_KEY_CHECKS = 1;
