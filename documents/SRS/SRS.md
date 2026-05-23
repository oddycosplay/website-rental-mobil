# Software Requirements Specification (SRS)

**Nama File:** `SRS.md`  
**Lokasi:** `documents/SRS/`  
**Tujuan:** Spesifikasi teknis lengkap sistem Siliwangi Rental — arsitektur, modul backend & frontend, API, database overview, dan deployment.

---

## Metadata Dokumen

| Atribut | Detail |
|---|---|
| Nama Project | Siliwangi Rental |
| Versi | 2.0.0 |
| Stack Utama | Laravel 12, Blade, Tailwind CSS, Alpine.js, MySQL, Midtrans, Spatie Permission, Filament v4 |
| Tanggal | 2026-05-21 |

---

## 1. Software Architecture

### 1.1 Pattern Arsitektur

Sistem menggunakan pola **MVC (Model-View-Controller)** berbasis Laravel, diperkuat dengan:

- **Service Layer** — Logika bisnis kompleks (seperti integrasi Midtrans) dipisahkan ke `app/Services/`.
- **Policy Pattern** — Otorisasi per-entitas menggunakan Laravel Policy.
- **Job Queue** — Proses asinkron via `app/Jobs/` (opsional).

### 1.2 Layer Arsitektur

```
┌─────────────────────────────────────────────────────┐
│                  PRESENTATION LAYER                 │
│  Blade Templates  │  Livewire Components  │  API    │
├─────────────────────────────────────────────────────┤
│                  APPLICATION LAYER                  │
│  Controllers  │  Livewire  │  Filament Resources    │
├─────────────────────────────────────────────────────┤
│                   SERVICE LAYER                     │
│            MidtransService │ WhatsAppService        │
├─────────────────────────────────────────────────────┤
│                    DATA LAYER                       │
│             Eloquent Models │ DB Queries            │
├─────────────────────────────────────────────────────┤
│                 INFRASTRUCTURE LAYER                │
│         MySQL/SQLite │ Storage │ Cache (File)       │
└─────────────────────────────────────────────────────┘
```

### 1.3 Stack Teknologi

| Komponen | Teknologi | Versi |
|---|---|---|
| Framework Backend | Laravel | 12.x |
| Templating | Blade | Laravel Built-in |
| CSS Framework | Tailwind CSS | 3.x / 4.x |
| JS Framework | Alpine.js | 3.x |
| Reactive Components | Livewire | 3.x |
| Admin Panel | Filament | v4 |
| Database | MySQL / SQLite | 8.x |
| Payment Gateway | Midtrans | Snap API |
| Role & Permission | Spatie Permission | 6.x |

---

## 2. Backend Modules

### 2.1 Auth Module

| Komponen | File |
|---|---|
| LoginController | `app/Http/Controllers/Auth/AuthenticatedSessionController.php` |
| RegisterController | `app/Http/Controllers/Auth/RegisteredUserController.php` |
| PasswordResetController | `app/Http/Controllers/Auth/PasswordResetLinkController.php` |
| Middleware Auth | `app/Http/Middleware/Authenticate.php` |

### 2.2 Booking Module

| Komponen | File |
|---|---|
| Livewire Checkout | `app/Livewire/Checkout.php` |
| Booking Model | `app/Models/Booking.php` |
| Booking Policy | `app/Policies/BookingPolicy.php` |

### 2.3 Payment Module

| Komponen | File |
|---|---|
| MidtransController | `app/Http/Controllers/MidtransController.php` |
| MidtransService | `app/Services/MidtransService.php` |
| Payment Model | `app/Models/Payment.php` |

### 2.4 Vehicle Module

| Komponen | File |
|---|---|
| Car Model | `app/Models/Car.php` |
| CarBrand Model | `app/Models/CarBrand.php` |
| CarType Model | `app/Models/CarType.php` |
| CarMaintenance Model | `app/Models/CarMaintenance.php` |
| CarInspection Model | `app/Models/CarInspection.php` |

### 2.5 Driver Module

| Komponen | File |
|---|---|
| Driver Model | `app/Models/Driver.php` |

### 2.6 Admin Panel Module (Filament v4)

| Resource | File |
|---|---|
| BookingResource | `app/Filament/Resources/Bookings/BookingResource.php` |
| CarResource | `app/Filament/Resources/Cars/CarResource.php` |
| DriverResource | `app/Filament/Resources/Drivers/DriverResource.php` |
| PaymentResource | `app/Filament/Resources/Payments/PaymentResource.php` |
| BranchResource | `app/Filament/Resources/Branches/BranchResource.php` |
| UserResource | `app/Filament/Resources/Users/UserResource.php` |
| CarMaintenanceResource | `app/Filament/Resources/CarMaintenances/CarMaintenanceResource.php` |
| CarInspectionResource | `app/Filament/Resources/CarInspections/CarInspectionResource.php` |
| ExpenseResource | `app/Filament/Resources/Expenses/ExpenseResource.php` |

---

## 3. Frontend Modules

### 3.1 Halaman Publik

| Halaman | Route | View |
|---|---|---|
| Homepage | `/` | `welcome.blade.php` |
| Catalog Mobil | `/catalog` | `livewire/car-catalog.blade.php` |
| Detail Mobil | `/cars/{slug}` | `livewire/car-detail.blade.php` |
| Tentang | `/tentang` | `about.blade.php` |
| Kontak | `/contact` | `contact.blade.php` |

### 3.2 Halaman Customer (Auth Required)

| Halaman | Route | View |
|---|---|---|
| Dashboard Customer | `/dashboard` | `dashboard.blade.php` |
| Form Checkout | `/checkout/{car}` | `checkout/index.blade.php` |
| Riwayat Booking | `/customer/bookings` | `customer/bookings.blade.php` |
| Profil | `/profile` | `profile/edit.blade.php` |

---

## 4. Database Overview

### 4.1 Tabel Utama (Simplified Schema)

| Tabel | Fungsi |
|---|---|
| `users` | Akun pengguna (Admin, Staf, Customer, Driver) dan profil lengkap |
| `branches` | Kantor cabang operasional rental |
| `cars` | Data armada kendaraan |
| `car_types` | Klasifikasi tipe kendaraan |
| `car_brands` | Merek pabrikan kendaraan |
| `drivers` | Data pengemudi |
| `bookings` | Transaksi pemesanan sewa mobil |
| `payments` | Rekaman pembayaran Midtrans / manual |
| `promos` | Kupon potongan harga |
| `car_maintenances` | Log riwayat servis kendaraan |
| `car_inspections` | Log riwayat inspeksi kendaraan |
| `expenses` | Catatan pengeluaran operasional cabang |
| `reviews` | Ulasan bintang dan kepuasan pelanggan |

---

## 5. Deployment Architecture

```
Internet
    │
    ▼
[Nginx / Apache] — Port 80/443 — SSL/TLS
    │
    ▼
[Laravel Application] — PHP 8.2+
    │
    ├─ [MySQL 8.x / SQLite Database]
    ├─ [Storage — Local]
    └─ [Cache — File Driver]
```

### 5.1 Server Requirements

| Komponen | Minimum |
|---|---|
| PHP | 8.2+ |
| RDBMS | MySQL 8.0+ / SQLite |
| RAM | 2 GB |
| Storage | 20 GB |
| OS | Windows (Laragon) / Ubuntu 22.04 LTS |

---

Versi: 2.0.0 | Tanggal: 21 Mei 2026 | Penyelarasan Dokumen SRS dengan Skema Akademik Kerja Praktik
