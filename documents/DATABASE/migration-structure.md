# Migration Structure — Siliwangi Rental

**Nama File:** `migration-structure.md`  
**Lokasi:** `documents/DATABASE/`  
**Tujuan:** Mendokumentasikan struktur file migration Laravel beserta urutan eksekusi dan keterangan setiap migration.

---

## Metadata Dokumen

| Atribut | Detail |
|---|---|
| Nama Project | Siliwangi Rental |
| Versi | 3.0.0 |
| Tanggal | 2026-05-21 |

---

## 1. Urutan Migration Utama

Berikut adalah daftar migration database yang terdaftar dalam sistem:

| No | Nama File Migration | Tabel / Kolom Utama yang Terpengaruh | Keterangan |
|---|---|---|---|
| 1 | `0001_01_01_000000_create_users_table.php` | `users`, `password_reset_tokens`, `sessions` | Skema dasar autentikasi bawaan Laravel. |
| 2 | `0001_01_01_000001_create_cache_table.php` | `cache`, `cache_locks` | Driver caching framework. |
| 3 | `0001_01_01_000002_create_jobs_table.php` | `jobs`, `job_batches`, `failed_jobs` | Pengelolaan antrean (queue) Laravel. |
| 4 | `2026_05_03_205749_add_two_factor_columns...php` | `users` (2FA columns) | Dukungan autentikasi dua faktor. |
| 5 | `2026_05_03_205750_create_passkeys_table.php` | `passkeys` | Dukungan masuk tanpa sandi (WebAuthn). |
| 6 | `2026_05_03_210259_create_personal_access_tokens_table.php` | `personal_access_tokens` | Token otentikasi API Sanctum. |
| 7 | `2026_05_05_000000_create_siliwangi_tables.php` | `branches`, `cars`, `customers`, `drivers`, `driver_schedules`, `promos`, `bookings`, `payments`, `reviews` | Inisialisasi awal skema rental mobil. |
| 8 | `2026_05_05_154328_create_permission_tables.php` | `permissions`, `roles`, `model_has_roles`, dll. | Integrasi Spatie Permission. |
| 9 | `2026_05_06_000001_add_nik_ktp_sim_to_customers...php` | `customers` & `bookings` (identity paths) | Menambahkan kolom identitas penyewa. |
| 10 | `2026_05_07_000001_create_finance_tables.php` | `expense_categories`, `expenses` | Pembukuan biaya operasional. |
| 11 | `2026_05_19_052121_consolidate_customers_and_cleanup_tables.php` | `users` (profil customer), `bookings` / `reviews` | **Konsolidasi Utama:** Menghapus tabel `customers` dan `booking_items`, menggabungkan seluruh data identitas customer langsung ke tabel `users`. Menambahkan penanganan checkout tamu (guest checkout). |
| 12 | `2026_05_19_052122_recreate_missing_operational_tables.php` | `car_brands`, `car_types`, `car_maintenances`, `car_inspections` | Menyediakan tabel master & pencatatan log operasional mobil secara terpisah dan terstruktur. |

---

## 2. Cara Menjalankan Migration

```bash
# Jalankan semua migration (fresh install)
php artisan migrate

# Rollback semua dan migrate ulang (HATI-HATI: data hilang)
php artisan migrate:fresh

# Migrate + seed data awal
php artisan migrate:fresh --seed

# Rollback migration terakhir
php artisan migrate:rollback

# Status migration
php artisan migrate:status
```

---

## 3. Seeder Files

| File | Fungsi |
|---|---|
| `DatabaseSeeder.php` | Root seeder — memanggil seeder utama sistem. |
| `UserSeeder.php` | Mengisi data pengguna contoh (Admin, Customer, Driver). |
| `CarSeeder.php` | Mengisi data armada mobil contoh. |
| `BranchSeeder.php` | Mengisi data cabang operasional contoh. |

---

Versi: 3.0.0 | Tanggal: 21 Mei 2026 | Penyelarasan Dokumen dengan Migrasi Konsolidasi & Skema KP
