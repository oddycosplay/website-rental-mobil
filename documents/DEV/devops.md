# DevOps & Deployment Plan — Siliwangi Rental

**Nama File:** `devops.md`  
**Lokasi:** `documents/`  
**Tujuan:** Mendokumentasikan strategi deployment, konfigurasi Docker, alur CI/CD, dan standar keamanan server produksi.

---

## 1. Strategi Containerization (Docker)

Menggunakan Docker untuk memastikan konsistensi lingkungan antara development, staging, dan produksi.

### 1.1 Komponen Docker (docker-compose)

- **App Service:** PHP 8.3-fpm (Nginx as proxy).
- **Database Service:** MySQL 8.0.
- **Queue Worker:** Laravel Queue worker untuk memproses notifikasi WhatsApp/Email.
- **Cache Service:** Redis untuk caching dan session management.

### 1.2 Dockerfile Specification

- Menggunakan base image `php:8.3-fpm-alpine` untuk ukuran image yang kecil.
- Instalasi ekstensi wajib: `pdo_mysql`, `gd`, `zip`, `intl`, `bcmath`.
- Multi-stage build untuk mengoptimalkan aset frontend (Vite).

---

## 2. CI/CD Pipeline (GitHub Actions)

Otomatisasi pengujian dan deployment setiap kali ada perubahan pada branch `main`.

### 2.1 Workflow: Test & Lint

- Trigger: Push ke `main` atau Pull Request.
- Langkah:
  - Setup PHP & Node.js.
  - Jalankan `composer install` & `npm install`.
  - Jalankan static analysis (PHPStan/Pint).
  - Jalankan Unit & Feature Tests.

### 2.2 Workflow: Deployment (Auto-deploy)

- Trigger: Push ke branch `main` (setelah Test lulus).
- Langkah:
  - Build Docker Image.
  - Push Image ke Container Registry (GHCR/DockerHub).
  - SSH ke Production Server.
  - `docker-compose pull` & `docker-compose up -d`.
  - Jalankan `php artisan migrate --force`.

---

## 3. Production Server Hardening

Langkah-langkah keamanan untuk melindungi server dari serangan luar.

### 3.1 Keamanan Server (Linux/Ubuntu)

- **Disable Root Login:** Menggunakan user sudo khusus.
- **SSH Key Only:** Mematikan autentikasi password untuk SSH.
- **Firewall (UFW):** Hanya membuka port 80 (HTTP), 443 (HTTPS), dan port SSH kustom.
- **Fail2Ban:** Untuk mencegah serangan brute-force pada SSH.

### 3.2 Keamanan Aplikasi (Laravel)

- **SSL/TLS:** Wajib HTTPS menggunakan Let's Encrypt.
- **Secure Cookies:** Mengaktifkan flag `secure` dan `http_only`.
- **Environment Variables:** Menggunakan `.env` yang tidak masuk ke repository.
- **File Permissions:** Folder `storage` dan `bootstrap/cache` harus memiliki izin tulis yang tepat namun terbatas.

---

## 4. Monitoring & Backup

### 4.1 Log Management

- Menggunakan **Sentry** untuk error tracking secara real-time.
- Log rotasi untuk mencegah disk penuh.

### 4.2 Database Backup

- Backup otomatis setiap hari (cron job) ke cloud storage (S3/R2).
- Retention policy: Simpan backup selama 30 hari terakhir.

---

Versi: 1.0.0 | Tanggal: 2026-05-14
