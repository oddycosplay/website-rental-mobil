# Indexing Strategy & Database Optimization

**Nama File:** `indexing-strategy.md`  
**Lokasi:** `documents/DATABASE/`  
**Tujuan:** Mendokumentasikan strategi indexing MySQL dan optimasi database untuk performa optimal.

---

## Metadata Dokumen

 | Atribut | Detail |
|---|---|
 | Nama Project | Siliwangi Rental |
 | Versi | 1.0.0 |
 | Tanggal | 2026-05-14 |

---

## 1. Index per Tabel

### users

 | Kolom | Tipe Index | Alasan |
|---|---|---|
 | `email` | UNIQUE | Login lookup, cepat dan unique |
 | `is_active` | INDEX | Filter user aktif |

### cars

 | Kolom | Tipe Index | Alasan |
|---|---|---|
 | `slug` | UNIQUE | URL lookup |
 | `plate_number` | UNIQUE | Identifikasi unik kendaraan |
 | `status` | INDEX | Filter availability frequent query |
 | `is_featured` | INDEX | Homepage featured car query |
 | `is_active` | INDEX | Catalog listing filter |
 | `branch_id` | INDEX | Filter per cabang |
 | `price_per_day` | INDEX | Filter/sort harga |
 | `(car_type_id, status)` | COMPOSITE | Filter tipe + status katalog |

### bookings

 | Kolom | Tipe Index | Alasan |
|---|---|---|
 | `booking_code` | UNIQUE | Lookup booking |
 | `guest_token` | UNIQUE | Guest tracking lookup |
 | `status` | INDEX | Filter per status — query sering |
 | `customer_id` | INDEX | Riwayat booking customer |
 | `car_id` | INDEX | Cek availability kendaraan |
 | `(car_id, start_date, end_date)` | COMPOSITE | Availability date range check |
 | `payment_due_at` | INDEX | Auto-expire scheduler query |
 | `created_at` | INDEX | Laporan per periode |
 | `(branch_id, status)` | COMPOSITE | Laporan per cabang + status |

### payments

 | Kolom | Tipe Index | Alasan |
|---|---|---|
 | `order_id` | UNIQUE | Midtrans webhook lookup |
 | `booking_id` | INDEX | Join ke bookings |
 | `status` | INDEX | Filter pembayaran |

### drivers

 | Kolom | Tipe Index | Alasan |
|---|---|---|
 | `status` | INDEX | Availability driver check |
 | `branch_id` | INDEX | Filter per cabang |

### expenses

 | Kolom | Tipe Index | Alasan |
|---|---|---|
 | `expense_date` | INDEX | Laporan per periode |
 | `branch_id` | INDEX | Laporan per cabang |

---

## 2. Query Optimization Patterns

### 2.1 Eager Loading (Hindari N+1)

```php
// BAIK — eager loading
$bookings = Booking::with([
    'car.carBrand',
    'car.carType',
    'customer.user',
    'driver',
    'payments',
])->where('status', 'confirmed')->paginate(15);

// BURUK — N+1 problem
$bookings = Booking::all();
foreach ($bookings as $b) {
    echo $b->car->name; // query baru per iterasi
}
```

### 2.2 Select Specific Columns

```php
// BAIK
Car::select('id', 'name', 'slug', 'price_per_day', 'status', 'image')
    ->where('is_active', true)
    ->get();

// BURUK
Car::where('is_active', true)->get(); // SELECT * — boros
```

### 2.3 Availability Check Optimized

```php
// Cek apakah kendaraan available di periode tertentu
$isBooked = Booking::where('car_id', $carId)
    ->whereIn('status', ['pending','paid','confirmed','on_rent'])
    ->where(function ($q) use ($start, $end) {
        $q->whereBetween('start_date', [$start, $end])
          ->orWhereBetween('end_date', [$start, $end])
          ->orWhere(fn($q2) =>
              $q2->where('start_date', '<=', $start)
                 ->where('end_date', '>=', $end)
          );
    })->exists(); // pakai exists() bukan count() > 0
```

### 2.4 Pagination

```php
// Selalu pakai pagination — jangan get() pada data besar
Car::active()->paginate(12);

// Untuk report besar gunakan cursor pagination
Booking::latest()->cursorPaginate(50);
```

---

## 3. Database Configuration (Laravel)

```php
// config/database.php — MySQL settings
'mysql' => [
    'driver' => 'mysql',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'strict' => true,
    'engine' => 'InnoDB',
    'options' => [
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ],
],
```

---

## 4. Cache Strategy

 | Data | Cache Driver | TTL | Alasan |
|---|---|---|---|
 | Daftar car types | File/Redis | 24 jam | Jarang berubah |
 | Daftar car brands | File/Redis | 24 jam | Jarang berubah |
 | Featured cars | File/Redis | 1 jam | Update saat admin ubah |
 | Dashboard stats | File/Redis | 5 menit | Real-time cukup 5 menit |
 | Promo codes | File/Redis | 15 menit | Perlu fresh saat digunakan |

```php
// Contoh caching
$carTypes = Cache::remember('car_types', 86400, function () {
    return CarType::select('id', 'name', 'slug')->orderBy('name')->get();
});
```

---

Versi: 1.0.0 | Tanggal: 2026-05-14
