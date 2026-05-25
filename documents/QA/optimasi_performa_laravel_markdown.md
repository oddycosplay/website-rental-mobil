# Optimasi Performa Website Laravel

## Tujuan

Dokumen ini bertujuan untuk menjelaskan langkah-langkah optimasi performa pada website Laravel agar aplikasi lebih cepat, ringan, stabil, dan mampu menangani banyak pengguna.

---

## 1. Optimasi Konfigurasi Laravel

### 1.1 Cache Konfigurasi Laravel

Jalankan perintah berikut pada server production:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

#### Fungsi

| Command        | Fungsi                            |
| -------------- | --------------------------------- |
| `config:cache` | Mempercepat pembacaan konfigurasi |
| `route:cache`  | Mempercepat proses routing        |
| `view:cache`   | Mempercepat rendering Blade       |
| `event:cache`  | Mengoptimalkan event listener     |

#### Membersihkan Cache

```bash
php artisan optimize:clear
```

---

### 1.2 Mode Production

Atur file `.env`:

```env
APP_ENV=production
APP_DEBUG=false
```

#### Penjelasan

- `APP_ENV=production` mengaktifkan mode production
- `APP_DEBUG=false` menonaktifkan debug yang dapat memperlambat aplikasi

---

## 2. Optimasi Database

### 2.1 Hindari N+1 Query

#### Contoh Salah

```php
$bookings = Booking::all();

foreach ($bookings as $booking) {
    echo $booking->customer->name;
}
```

#### Dampak

Laravel akan menjalankan query berulang kali sehingga memperlambat aplikasi.

---

#### Contoh Benar

Gunakan eager loading:

```php
$bookings = Booking::with('customer')->get();
```

Jika memiliki banyak relasi:

```php
$bookings = Booking::with([
    'customer',
    'car',
    'payment'
])->get();
```

---

### 2.2 Gunakan Pagination

#### Contoh Salah

```php
$cars = Car::all();
```

#### Contoh Benar

```php
$cars = Car::paginate(10);
```

Or:

```php
$cars = Car::simplePaginate(10);
```

#### Manfaat

- Mengurangi penggunaan memory
- Mempercepat loading halaman
- Mengurangi query berat

---

### 2.3 Ambil Kolom yang Dibutuhkan

#### Contoh Salah

```php
User::all();
```

#### Contoh Benar

```php
User::select('id', 'name', 'email')->get();
```

#### Manfaat

Mengurangi data yang diambil dari database.

---

### 2.4 Tambahkan Index Database

#### Contoh Migration

```php
$table->foreignId('user_id')->index();
$table->string('email')->unique();
```

#### Kolom yang Disarankan Menggunakan Index

- `user_id`
- `booking_id`
- `car_id`
- `email`
- `status`
- `created_at`

---

## 3. Optimasi Blade dan View

### 3.1 Hindari Query di Blade

#### Contoh Salah

```blade
@foreach ($bookings as $booking)
    {{ App\Models\User::find($booking->user_id)->name }}
@endforeach
```

---

#### Contoh Benar

Controller:

```php
$bookings = Booking::with('user')->get();
```

Blade:

```blade
{{ $booking->user->name }}
```

---

### 3.2 Gunakan Component Secukupnya

#### Penjelasan

Terlalu banyak nested component dapat memperlambat rendering halaman.

Gunakan component hanya untuk:

- Reusable UI
- Struktur yang memang diperlukan

---

## 4. Optimasi Frontend

### 4.1 Build Asset Production

Gunakan:

```bash
npm run build
```

Jangan gunakan:

```bash
npm run dev
```

pada server production.

---

### 4.2 Minify CSS dan JavaScript

Vite otomatis melakukan:

- Minify CSS
- Minify JavaScript
- Tree shaking

---

### 4.3 Kompres Gambar

#### Format Gambar yang Disarankan

- WebP
- AVIF

#### Ukuran Ideal

| Jenis Gambar | Ukuran  |
| ------------ | ------- |
| Thumbnail    | < 200KB |
| Banner       | < 500KB |

---

## 5. Gunakan Queue

### Penjelasan

Jangan jalankan proses berat secara langsung saat user melakukan request.

#### Contoh Proses Berat

- Kirim email
- Upload gambar
- Generate PDF
- Notifikasi WhatsApp

---

### Menjalankan Queue

```bash
php artisan queue:work
```

#### Contoh Penggunaan

```php
SendInvoiceJob::dispatch($booking);
```

---

## 6. Gunakan Cache Data

### Contoh

```php
$cars = Cache::remember('cars', 3600, function () {
    return Car::all();
});
```

#### Penjelasan

Data akan disimpan selama 1 jam sehingga mengurangi query database.

---

### Driver Cache yang Direkomendasikan

- Redis
- Memcached

---

## 7. Optimasi Server

### 7.1 Gunakan PHP Versi Terbaru

#### Disarankan

- PHP 8.2
- PHP 8.3
- PHP 8.4

#### Manfaat

Versi PHP terbaru memiliki performa lebih cepat dan efisien.

---

### 7.2 Aktifkan OPcache

Tambahkan pada `php.ini`:

```ini
opcache.enable=1
opcache.enable_cli=1
```

#### Fungsi

Menyimpan hasil compile PHP agar eksekusi lebih cepat.

---

### 7.3 Gunakan Nginx

#### Keunggulan Nginx

- Lebih ringan
- Lebih cepat
- Lebih stabil untuk traffic tinggi

---

## 8. Optimasi Storage dan Log

### 8.1 Bersihkan File Log

Lokasi log Laravel:

```bash
storage/logs/
```

#### Penjelasan

File log yang terlalu besar dapat memperlambat aplikasi.

---

### 8.2 Gunakan Storage Link

```bash
php artisan storage:link
```

---

## 9. Monitoring dan Debugging

### 9.1 Laravel Debugbar

#### Install

```bash
composer require barryvdh/laravel-debugbar --dev
```

#### Fungsi

- Melihat query lambat
- Monitoring memory
- Melihat duplicate query

#### Catatan

Gunakan hanya pada development.

---

### 9.2 Laravel Telescope

Digunakan untuk:

- Monitoring aplikasi
- Melihat request
- Queue monitoring
- Exception monitoring

---

## 10. Optimasi Deployment

### Install Composer Production

```bash
composer install --optimize-autoloader --no-dev
```

---

### Optimasi Laravel

```bash
php artisan optimize
```

---

## 11. Optimasi Query Besar

### Contoh Salah

```php
foreach ($users as $user) {
    Booking::where('user_id', $user->id)->count();
}
```

---

### Contoh Benar

```php
User::withCount('bookings')->get();
```

---

## 12. Gunakan Chunk dan Cursor

### Chunk

```php
User::chunk(100, function ($users) {
    //
});
```

---

### Cursor

```php
User::cursor();
```

#### Cocok Untuk

- Export data
- Import data
- Laporan besar

---

## 13. Gunakan CDN

### Fungsi CDN

Mempercepat pengiriman:

- Gambar
- CSS
- JavaScript

---

### Contoh CDN

- Cloudflare
- BunnyCDN

---

## 14. Checklist Optimasi Laravel

### Checklist Production

| Optimasi         | Status |
| ---------------- | ------ |
| APP_DEBUG=false  | ✅     |
| Route Cache      | ✅     |
| Config Cache     | ✅     |
| View Cache       | ✅     |
| Eager Loading    | ✅     |
| Pagination       | ✅     |
| Database Index   | ✅     |
| Queue            | ✅     |
| OPcache          | ✅     |
| Build Production | ✅     |
| Kompres Gambar   | ✅     |

---

## 15. Prioritas Optimasi Paling Penting

Urutan optimasi yang paling berpengaruh:

1. Optimasi query database
2. Gunakan eager loading
3. Gunakan pagination
4. Aktifkan cache Laravel
5. Kompres gambar
6. Gunakan queue
7. Aktifkan OPcache
8. Upgrade PHP

---

## Contoh Setup Optimasi Production

```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

npm run build

composer install --optimize-autoloader --no-dev
```

---

## Optimasi untuk Website Rental Mobil Laravel

### Bagian yang Biasanya Lambat

- Dashboard admin
- Data booking
- Laporan transaksi
- Upload gambar mobil
- Statistik chart
- Pencarian mobil

---

### Solusi yang Direkomendasikan

#### Gunakan Eager Loading

```php
Booking::with([
    'customer',
    'car',
    'payment'
])->paginate(10);
```

---

### Gunakan Cache Dashboard

```php
$statistics = Cache::remember('dashboard-statistics', 3600, function () {
    return [
        'totalCars' => Car::count(),
        'totalBookings' => Booking::count(),
        'totalCustomers' => Customer::count(),
    ];
});
```

---

## Kesimpulan

Optimasi Laravel harus dilakukan dari berbagai sisi seperti:

- Konfigurasi framework
- Query database
- Frontend asset
- Server
- Cache
- Queue
- Deployment

Dengan optimasi yang tepat, website Laravel dapat menjadi:

- Lebih cepat
- Lebih ringan
- Lebih stabil
- Mampu menangani banyak pengguna
- Lebih efisien dalam penggunaan resource server
