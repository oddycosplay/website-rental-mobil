# Docker Setup — Siliwangi Rental

**Nama File:** `docker-setup.md`  
**Lokasi:** `documents/DEV/`  
**Tujuan:** Setup Docker untuk development environment Siliwangi Rental.

---

## docker-compose.yml

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: siliwangi_app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - .:/var/www
    networks:
      - siliwangi

  nginx:
    image: nginx:alpine
    container_name: siliwangi_nginx
    restart: unless-stopped
    ports:
      - "8080:80"
    volumes:
      - .:/var/www
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
    networks:
      - siliwangi

  mysql:
    image: mysql:8.0
    container_name: siliwangi_mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: siliwangi_rental
      MYSQL_ROOT_PASSWORD: secret
      MYSQL_USER: siliwangi
      MYSQL_PASSWORD: secret
    volumes:
      - mysql_data:/var/lib/mysql
    ports:
      - "3307:3306"
    networks:
      - siliwangi

  queue:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: siliwangi_queue
    restart: unless-stopped
    working_dir: /var/www
    command: php artisan queue:work --sleep=3 --tries=3
    volumes:
      - .:/var/www
    depends_on:
      - mysql
    networks:
      - siliwangi

networks:
  siliwangi:
    driver: bridge

volumes:
  mysql_data:
```

---

## Dockerfile

```dockerfile
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    zip unzip libzip-dev

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
```

---

## Nginx Config (docker/nginx/default.conf)

```nginx
server {
    listen 80;
    root /var/www/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

## Commands

```bash
# Build dan jalankan
docker-compose up -d --build

# Migrate & seed
docker-compose exec app php artisan migrate:fresh --seed

# Storage link
docker-compose exec app php artisan storage:link

# Access container
docker-compose exec app bash

# Stop
docker-compose down
```

---

Versi: 1.0.0 | Tanggal: 2026-05-14
