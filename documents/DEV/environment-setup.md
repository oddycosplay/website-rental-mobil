# Environment Setup — Siliwangi Rental

**Nama File:** `environment-setup.md`  
**Lokasi:** `documents/DEV/`  
**Tujuan:** Panduan setup environment development lokal Siliwangi Rental.

---

## 1. Requirement

 | Software | Versi Minimum | Keterangan |
|---|---|---|
 | PHP | 8.2+ | FPM atau CLI |
 | Composer | 2.x | Dependency manager PHP |
 | MySQL | 8.0+ | Database |
 | Node.js | 18+ | Build assets |
 | npm | 9+ | Package manager JS |
 | Laragon | Latest (Windows) | Local dev server |
 | Git | 2.x | Version control |

---

## 2. Setup Langkah demi Langkah

### Step 1: Clone Repository

```bash
git clone <https://github.com/[repo]/rental_project.git>
cd rental_project
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Install JS Dependencies

```bash
npm install
```

### Step 4: Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

### Step 5: Edit .env

```env
APP_NAME="Siliwangi Rental"
APP_ENV=local
APP_DEBUG=true
APP_URL=<http://rental_project.test>

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siliwangi_rental
DB_USERNAME=root
DB_PASSWORD=

# Queue
QUEUE_CONNECTION=database

# Cache
CACHE_DRIVER=file

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Mail
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-user
MAIL_PASSWORD=your-mailtrap-pass
MAIL_FROM_ADDRESS=noreply@siliwangirental.com
MAIL_FROM_NAME="Siliwangi Rental"

# Midtrans
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxx
MIDTRANS_IS_PRODUCTION=false

# WhatsApp
WHATSAPP_TOKEN=your-fonnte-token
WHATSAPP_URL=<https://api.fonnte.com/send>
```

### Step 6: Buat Database

```bash
mysql -u root -e "CREATE DATABASE siliwangi_rental CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Step 7: Migrate & Seed

```bash
php artisan migrate:fresh --seed
```

### Step 8: Storage Link

```bash
php artisan storage:link
```

### Step 9: Build Assets

```bash
npm run dev
```

### Step 10: Jalankan Queue Worker

```bash
php artisan queue:work
```

### Step 11: Akses Aplikasi

- Frontend: `http://rental_project.test`
- Admin Panel: `http://rental_project.test/admin`

---

## 3. Default Credentials (Development Only)

 | Role | Email | Password |
|---|---|---|
 | Owner | <owner@siliwangi.com> | password |
 | Admin | <admin@siliwangi.com> | password |
 | Finance | <finance@siliwangi.com> | password |
 | Driver | <driver@siliwangi.com> | password |
 | Customer | <customer@siliwangi.com> | password |

---

## 4. Useful Artisan Commands

```bash
# Clear semua cache
php artisan optimize:clear

# Generate dummy data
php artisan db:seed

# Jalankan scheduler sekali
php artisan schedule:run

# Expire booking manual
php artisan bookings:expire

# Lihat semua routes
php artisan route:list

# Restart queue worker
php artisan queue:restart
```

---

Versi: 1.0.0 | Tanggal: 2026-05-14
