# Deployment Guide — Siliwangi Rental

**Nama File:** `deployment.md`  
**Lokasi:** `documents/DEV/`  
**Tujuan:** Panduan deployment ke server production (Classic & Docker-Based).

---

## 1. Spesifikasi Server Produksi

| Komponen | Minimum | Rekomendasi |
|---|---|---|
| **OS** | Ubuntu 22.04 LTS | Ubuntu 24.04 LTS |
| **RAM** | 2 GB | 4 GB (jika build Docker di server) |
| **CPU** | 1 Core | 2 Cores |
| **Storage** | 20 GB SSD | 40 GB NVMe SSD |
| **Docker** | - | Versi 24.0+ & Docker Compose v2 |
| **SSL** | Let's Encrypt | Let's Encrypt |

---

## 2. METODE 1: Deployment Berbasis Docker (Direkomendasikan)

Dengan struktur baru di mana backend Laravel berada di `/backend` dan frontend Vue 3 berada di `/frontend`, Docker Compose akan mengorkestrasi 6 container utama melalui network internal `siliwangi`.

### 2.1 Persiapan Server Awal

Jalankan perintah berikut pada server Ubuntu Anda untuk memasang Docker dan Docker Compose:

```bash
# Update package list & install prasyarat
sudo apt update && sudo apt install -y curl git apt-transport-https ca-certificates gnupg lsb-release

# Tambah GPG key Docker resmi
sudo mkdir -p /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg

# Setup repositori Docker
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Install Docker Engine & Docker Compose
sudo apt update && sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin

# Pastikan Docker berjalan otomatis
sudo systemctl enable docker --now
```

### 2.2 Langkah Deployment Aplikasi

1. **Clone Repositori**:
   ```bash
   cd /var/www
   git clone <repo-url> rental_project
   cd rental_project
   ```

2. **Setup Environment (.env)**:
   Buat file `.env` di dalam folder `/backend`:
   ```bash
   cp backend/.env.example backend/.env
   # Edit backend/.env dan sesuaikan variabel koneksi database:
   # DB_HOST=mysql (nama container database di compose)
   # DB_DATABASE=siliwangi_rental
   # DB_USERNAME=siliwangi
   # DB_PASSWORD=secret
   ```

3. **Build dan Jalankan Container**:
   Jalankan Docker Compose untuk mengompilasi frontend Vue 3 dan membangun image PHP-FPM backend:
   ```bash
   docker compose up -d --build
   ```

4. **Inisialisasi Database & Storage**:
   Jalankan migrasi database, database seeder, dan tautkan folder storage di dalam container `backend_app`:
   ```bash
   # Generate key aplikasi Laravel
   docker compose exec backend_app php artisan key:generate

   # Jalankan migrasi dan seeder
   docker compose exec backend_app php artisan migrate:fresh --seed --force

   # Buat symbolic link storage
   docker compose exec backend_app php artisan storage:link
   ```

5. **Optimasi Cache Laravel (Produksi)**:
   ```bash
   docker compose exec backend_app php artisan config:cache
   docker compose exec backend_app php artisan route:cache
   docker compose exec backend_app php artisan view:cache
   ```

---

## 3. Konfigurasi SSL Let's Encrypt (Host OS)

Untuk performa dan skalabilitas terbaik, SSL Termination disarankan dikelola di level Host OS (luar Docker) menggunakan Certbot, yang kemudian meneruskan traffic terenkripsi ke container `gateway` di port lokal `8080`.

### 3.1 Install Certbot di Host OS

```bash
sudo apt install -y nginx certbot python3-certbot-nginx
```

### 3.2 Konfigurasi Nginx di Host OS (`/etc/nginx/sites-available/siliwangi-rental`)

Buat konfigurasi reverse proxy berikut untuk mengarahkan port HTTP/HTTPS ke Docker Gateway:

```nginx
server {
    listen 80;
    server_name siliwangirental.com www.siliwangirental.com;

    location / {
        proxy_pass http://127.0.0.1:8080; # Mengarah ke container gateway Nginx Docker
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

Aktifkan konfigurasi dan reload Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/siliwangi-rental /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

### 3.3 Dapatkan Sertifikat SSL SSL Let's Encrypt

Jalankan Certbot untuk mengamankan domain dengan HTTPS secara otomatis:
```bash
sudo certbot --nginx -d siliwangirental.com -d www.siliwangirental.com
```
Certbot secara otomatis akan memodifikasi konfigurasi Nginx host untuk memaksa pengalihan HTTP ke HTTPS dan mengelola pembaruan sertifikat secara otomatis via cron job.

---

## 4. Alur Zero-Downtime Deployment (Docker-Based)

Untuk melakukan update aplikasi di server produksi tanpa downtime bagi pengguna:

```bash
#!/bin/bash
# Simpan sebagai deploy.sh di server produksi

echo "Menarik kode terbaru dari branch main..."
git pull origin main

echo "Membangun ulang image dan memperbarui container..."
# --build akan mendeteksi jika ada perubahan kode di backend/frontend
docker compose up -d --build --remove-orphans

echo "Menjalankan migrasi database..."
docker compose exec backend_app php artisan migrate --force

echo "Membersihkan dan membangun ulang cache aplikasi..."
docker compose exec backend_app php artisan config:cache
docker compose exec backend_app php artisan route:cache
docker compose exec backend_app php artisan view:cache

echo "Merestart Queue Worker..."
docker compose exec backend_app php artisan queue:restart

echo "Deployment Selesai!"
```

---

## 5. METODE 2: Classic Deployment (Tanpa Docker)

Jika Anda mendeploy langsung ke VPS tanpa containerization, ikuti langkah-langkah di bawah ini.

### 5.1 Nginx Server Block Setup (`/etc/nginx/sites-available/siliwangi-classic`)
```nginx
server {
    listen 80;
    server_name siliwangirental.com www.siliwangirental.com;
    root /var/www/rental_project/backend/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### 5.2 Build Manual
```bash
# Build Frontend Vue 3 secara lokal atau di server
cd /var/www/rental_project/frontend
npm install && npm run build
# Salin berkas dist ke folder public backend
cp -r dist/* ../backend/public/

# Setup Backend Laravel
cd ../backend
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

---

Versi: 2.0.0 | Tanggal: 2026-06-15
