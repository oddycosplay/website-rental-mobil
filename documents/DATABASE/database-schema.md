# Database Schema — Siliwangi Rental

**Nama File:** `database-schema.md`  
**Lokasi:** `documents/DATABASE/`  
**Tujuan:** Dokumentasi lengkap skema database MySQL.

---

## 10. Tabel: bookings

```sql
CREATE TABLE bookings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_code VARCHAR(20) NOT NULL UNIQUE,
    user_id BIGINT UNSIGNED NULL,
    customer_id BIGINT UNSIGNED NULL,
    car_id BIGINT UNSIGNED NOT NULL,
    driver_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NOT NULL,
    promo_id BIGINT UNSIGNED NULL,
    booking_category ENUM('harian','bulanan') NOT NULL DEFAULT 'harian',
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    duration INT UNSIGNED NOT NULL DEFAULT 1,
    with_driver BOOLEAN NOT NULL DEFAULT 0,
    subtotal DECIMAL(15,2) NOT NULL DEFAULT 0,
    driver_fee DECIMAL(15,2) NOT NULL DEFAULT 0,
    discount_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
    extra_price DECIMAL(15,2) NOT NULL DEFAULT 0, -- Storing Admin & Operational Fees
    tax_amount DECIMAL(15,2) NOT NULL DEFAULT 0,   -- PPN 12%
    total_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
    dp_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
    remaining_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
    fine_amount DECIMAL(15,2) NOT NULL DEFAULT 0,
    status ENUM('pending','paid','confirmed','on_rent','returned','completed','cancelled','expired') NOT NULL DEFAULT 'pending',
    guest_token VARCHAR(100) NULL UNIQUE,
    guest_name VARCHAR(255) NULL,
    guest_email VARCHAR(255) NULL,
    guest_phone VARCHAR(20) NULL,
    ktp_path VARCHAR(255) NULL,
    sim_path VARCHAR(255) NULL,
    notes TEXT NULL,
    admin_notes TEXT NULL,
    payment_due_at DATETIME NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    FOREIGN KEY (car_id) REFERENCES cars(id),
    FOREIGN KEY (driver_id) REFERENCES drivers(id) ON DELETE SET NULL,
    FOREIGN KEY (branch_id) REFERENCES branches(id),
    FOREIGN KEY (promo_id) REFERENCES promos(id) ON DELETE SET NULL,
    INDEX idx_status (status)
) ENGINE=InnoDB;
```

---

## 11. Tabel: location_surveys

```sql
CREATE TABLE location_surveys (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    store_id BIGINT UNSIGNED NOT NULL,
    booking_id BIGINT UNSIGNED NOT NULL,
    surveyor_name VARCHAR(255) NOT NULL,
    survey_date DATE NOT NULL,
    survey_type ENUM('delivery', 'pickup') NOT NULL DEFAULT 'delivery',
    address TEXT NOT NULL,
    residence_status JSON NOT NULL,
    job_status JSON NOT NULL,
    neighbor_interview JSON NOT NULL,
    photos JSON NOT NULL,
    recommendation ENUM('layak', 'tidak_layak') NOT NULL DEFAULT 'layak',
    notes TEXT NULL,
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    approved_by BIGINT UNSIGNED NULL,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_location_surveys_status (status)
) ENGINE=InnoDB;
```

---

## 12. Tabel: operationals

```sql
CREATE TABLE operationals (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    store_id BIGINT UNSIGNED NOT NULL,
    booking_id BIGINT UNSIGNED NOT NULL,
    car_id BIGINT UNSIGNED NOT NULL,
    inspector_name VARCHAR(255) NOT NULL,
    inspection_type ENUM('pre_rental', 'post_rental') NOT NULL DEFAULT 'pre_rental',
    inspected_at TIMESTAMP NOT NULL,
    odometer_km INT UNSIGNED NOT NULL,
    fuel_level ENUM('full', 'three_quarter', 'half', 'quarter', 'empty') NOT NULL DEFAULT 'full',
    exterior JSON NOT NULL,
    interior JSON NOT NULL,
    equipment JSON NOT NULL,
    engine JSON NOT NULL,
    photos JSON NOT NULL,
    fuel_photos JSON NOT NULL,
    damage_found BOOLEAN NOT NULL DEFAULT 0,
    damage_description TEXT NULL,
    damage_cost DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    dirty_fine DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    fuel_fine DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    damage_photos JSON NULL,
    customer_confirmed BOOLEAN NOT NULL DEFAULT 0,
    customer_note TEXT NULL,
    notes TEXT NULL,
    status ENUM('pending', 'approved') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE,
    INDEX idx_operationals_status (status)
) ENGINE=InnoDB;
```

---

Versi: 1.2.0 | Tanggal: 2026-05-31

