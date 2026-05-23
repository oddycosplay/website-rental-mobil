# Package Installation — Siliwangi Rental

**Nama File:** `package-installation.md`  
**Lokasi:** `documents/DEV/`  
**Tujuan:** Dokumentasi semua package yang digunakan beserta perintah instalasi.

---

## 1. PHP Packages (Composer)

 | Package | Versi | Fungsi |
|---|---|---|
 | `laravel/framework` | ^12.0 | Core framework |
 | `filament/filament` | ^4.0 | Admin panel |
 | `spatie/laravel-permission` | ^6.0 | Role & Permission |
 | `livewire/livewire` | ^3.0 | Reactive components |
 | `barryvdh/laravel-dompdf` | ^3.0 | Generate PDF |
 | `midtrans/midtrans-php` | ^2.0 | Midtrans SDK |
 | `laravel/fortify` | ^1.0 | Auth backend |

### Install Commands

```bash
# Core sudah ada
composer require laravel/framework

# Admin panel
composer require filament/filament:"^4.0" --with-all-dependencies

# Role & Permission
composer require spatie/laravel-permission

# Livewire
composer require livewire/livewire

# PDF
composer require barryvdh/laravel-dompdf

# Midtrans
composer require midtrans/midtrans-php

# Fortify
composer require laravel/fortify
```

---

## 2. JS Packages (npm)

 | Package | Versi | Fungsi |
|---|---|---|
 | `tailwindcss` | ^3.x | CSS framework |
 | `alpinejs` | ^3.x | JS framework |
 | `@alpinejs/mask` | ^3.x | Input masking |
 | `autoprefixer` | ^10.x | CSS prefixer |
 | `postcss` | ^8.x | CSS processor |
 | `vite` | ^5.x | Asset bundler |

### Install Commands (npm)

```bash
npm install tailwindcss alpinejs @alpinejs/mask autoprefixer postcss
```

---

## 3. Publish Config

```bash
# Spatie Permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate

# Filament
php artisan filament:install --panels

# DomPDF
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

---

## 4. composer.json (Relevant Section)

```json
{
  "require": {
    "php": "^8.2",
    "barryvdh/laravel-dompdf": "^3.0",
    "filament/filament": "^4.0",
    "laravel/fortify": "^1.3",
    "laravel/framework": "^12.0",
    "laravel/tinker": "^2.10",
    "livewire/livewire": "^3.0",
    "midtrans/midtrans-php": "^2.6",
    "spatie/laravel-activitylog": "^4.0",
    "spatie/laravel-permission": "^6.0"
  },
  "require-dev": {
    "fakerphp/faker": "^1.23",
    "laravel/pail": "^1.0",
    "laravel/pint": "^1.13",
    "pestphp/pest": "^3.0",
    "pestphp/pest-plugin-laravel": "^3.0"
  }
}
```

---

Versi: 1.0.0 | Tanggal: 2026-05-14
