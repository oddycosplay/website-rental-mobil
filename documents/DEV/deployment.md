# Deployment Guide — Siliwangi Rental

**Nama File:** `deployment.md`  
**Lokasi:** `documents/DEV/`  
**Tujuan:** Panduan deployment ke server production.

---

## 1. Server Requirements

 | Komponen | Minimum |
|---|---|
 | OS | Ubuntu 22.04 LTS |
 | PHP | 8.2 FPM |
 | MySQL | 8.0+ |
 | Nginx | 1.18+ |
 | RAM | 2 GB |
 | Storage | 20 GB SSD |
 | SSL | Let's Encrypt |

---

## 2. Initial Server Setup

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Nginx
sudo apt install nginx -y

# Install PHP 8.2
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.2-fpm php8.2-mysql php8.2-mbstring \
  php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip \
  php8.2-gd php8.2-intl -y

# Install MySQL
sudo apt install mysql-server -y
sudo mysql_secure_installation

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo bash -
sudo apt install nodejs -y
```

---

## 3. Nginx Configuration

```nginx
# /etc/nginx/sites-available/siliwangi-rental
server {
    listen 80;
    server_name siliwangirental.com www.siliwangirental.com;
    root /var/www/rental_project/public;
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

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";
}
```

```bash
sudo ln -s /etc/nginx/sites-available/siliwangi-rental /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

---

## 4. SSL dengan Let's Encrypt

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d siliwangirental.com -d www.siliwangirental.com
```

---

## 5. Deploy Aplikasi

```bash
# Clone/pull kode
cd /var/www
git clone [repo] rental_project
cd rental_project

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install && npm run build

# Config
cp .env.example .env
php artisan key:generate
# Edit .env dengan konfigurasi production

# Database
php artisan migrate --force
php artisan db:seed --force

# Optimize
php artisan optimize
php artisan storage:link

# Permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

---

## 6. Queue Worker (Supervisor)

```bash
sudo apt install supervisor -y

# /etc/supervisor/conf.d/siliwangi-worker.conf
[program:siliwangi-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/rental_project/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/siliwangi-worker.log

sudo supervisorctl reread && sudo supervisorctl update
sudo supervisorctl start siliwangi-worker:*
```

---

## 7. Cron Scheduler

```bash
# crontab -e (sebagai www-data atau root)
* * * * * www-data php /var/www/rental_project/artisan schedule:run >> /dev/null 2>&1
```

---

## 8. Zero-Downtime Deployment

```bash
# Pull kode terbaru
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader

# Maintenance mode
php artisan down

# Migration
php artisan migrate --force

# Clear & rebuild cache
php artisan optimize:clear
php artisan optimize

# Build assets
npm run build

# Restart queue
php artisan queue:restart

# Back online
php artisan up
```

---

Versi: 1.0.0 | Tanggal: 2026-05-14
