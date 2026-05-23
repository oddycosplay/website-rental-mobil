# Catalog Specification — Siliwangi Rental

**Nama File:** `catalog.md`  
**Lokasi:** `documents/SRS/`  
**Tujuan:** Spesifikasi teknis modul katalog kendaraan — struktur, filtering, sorting, featured car, dan pencarian.

---

## Metadata Dokumen

 | Atribut | Detail |
|---|---|
 | Nama Project | Siliwangi Rental |
 | Versi | 1.0.0 |
 | Tanggal | 2026-05-14 |

---

## 1. Catalog Structure

### 1.1 Route

```
GET /catalog               → Halaman katalog utama
GET /catalog?search=avanza → Hasil pencarian
GET /catalog?type=suv      → Filter by tipe
GET /cars/{slug}           → Halaman detail kendaraan
```

### 1.2 Livewire Component

```
app/Livewire/CarCatalog.php
  Properties:
    - $search (string)
    - $selectedType (string)
    - $selectedBrand (string)
    - $selectedTransmission (string)
    - $selectedCategory (string) — Pribadi/Perusahaan
    - $minPrice (integer)
    - $maxPrice (integer)
    - $sortBy (string)
    - $perPage (integer) — default 12

  Methods:
    - render() → query builder dengan semua filter
    - resetFilters() → reset semua properti ke default
    - updatingSearch() → reset pagination
```

---

## 2. Data yang Ditampilkan di Card Katalog

 | Field | Sumber | Keterangan |
|---|---|---|
 | Nama Kendaraan | `cars.name` | Nama lengkap kendaraan |
 | Foto Utama | `cars.image` | Gambar kendaraan |
 | Merek | `car_brands.name` | Relasi |
 | Tipe | `car_types.name` | Relasi |
 | Transmisi | `cars.transmission` | Manual / Matic |
 | Kapasitas | `cars.capacity` | Jumlah penumpang |
 | Harga/Hari | `cars.price_per_day` | Format Rupiah |
 | Status | `cars.status` | Available / Booked / Maintenance |
 | Badge Featured | `cars.is_featured` | Label "Unggulan" |
 | Kategori | `cars.category` | Pribadi / Perusahaan |
 | Tombol Detail | Link ke `/cars/{slug}` | CTA |
 | Tombol Booking | Link ke `/checkout/{slug}` | CTA (disabled jika tidak available) |

---

## 3. Filter System

### 3.1 Filter Options

 | Filter | Input Type | Source Data |
|---|---|---|
 | Keyword Search | Text input | `cars.name`, `car_brands.name` |
 | Tipe Kendaraan | Select / Badge filter | `car_types` table |
 | Merek | Select | `car_brands` table |
 | Transmisi | Select / Radio | `['manual', 'automatic']` |
 | Kategori | Select / Badge | `['Pribadi', 'Perusahaan']` |
 | Harga Min | Number input | `cars.price_per_day` |
 | Harga Max | Number input | `cars.price_per_day` |
 | Ketersediaan | Checkbox | `cars.status = 'available'` |

### 3.2 Query Builder Logic

```php
$query = Car::query()
    ->with(['carType', 'carBrand'])
    ->where('is_active', true);

if ($this->search) {
    $query->where(function ($q) {
        $q->where('name', 'like', "%{$this->search}%")
          ->orWhereHas('carBrand', fn($b) => $b->where('name', 'like', "%{$this->search}%"));
    });
}

if ($this->selectedType) {
    $query->whereHas('carType', fn($q) => $q->where('slug', $this->selectedType));
}

if ($this->selectedBrand) {
    $query->where('car_brand_id', $this->selectedBrand);
}

if ($this->selectedTransmission) {
    $query->where('transmission', $this->selectedTransmission);
}

if ($this->selectedCategory) {
    $query->where('category', $this->selectedCategory);
}

if ($this->minPrice) {
    $query->where('price_per_day', '>=', $this->minPrice);
}

if ($this->maxPrice) {
    $query->where('price_per_day', '<=', $this->maxPrice);
}
```

---

## 4. Sorting System

 | Opsi Sort | Kolom | Arah |
|---|---|---|
 | Harga Terendah | `price_per_day` | ASC |
 | Harga Tertinggi | `price_per_day` | DESC |
 | Terbaru | `created_at` | DESC |
 | Nama A-Z | `name` | ASC |
 | Nama Z-A | `name` | DESC |

```php
match ($this->sortBy) {
    'price_asc'  => $query->orderBy('price_per_day', 'asc'),
    'price_desc' => $query->orderBy('price_per_day', 'desc'),
    'newest'     => $query->orderBy('created_at', 'desc'),
    'name_asc'   => $query->orderBy('name', 'asc'),
    'name_desc'  => $query->orderBy('name', 'desc'),
    default      => $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc'),
};
```

---

## 5. Featured Car

- Kendaraan dengan `cars.is_featured = true` ditampilkan di:
  - Section "Armada Unggulan" di homepage (`welcome.blade.php`)
  - Posisi teratas di halaman katalog (default sort)
- Admin dapat toggle `is_featured` via Filament CarResource.
- Tidak ada batas jumlah featured car (fleksibel per kebutuhan bisnis).

---

## 6. Availability Checking

### 6.1 Logika Pengecekan

Kendaraan dianggap **tidak tersedia** pada periode tertentu jika:

```php
Booking::where('car_id', $carId)
    ->whereIn('status', ['pending', 'paid', 'confirmed', 'on_rent'])
    ->where(function ($q) use ($startDate, $endDate) {
        $q->whereBetween('start_date', [$startDate, $endDate])
          ->orWhereBetween('end_date', [$startDate, $endDate])
          ->orWhere(function ($q2) use ($startDate, $endDate) {
              $q2->where('start_date', '<=', $startDate)
                 ->where('end_date', '>=', $endDate);
          });
    })->exists();
```

### 6.2 Status Availability di Catalog

 | Status Kendaraan | Tampil di Catalog | Bisa di-booking |
|---|---|---|
 | `available` | ✅ Ya | ✅ Ya |
 | `booked` | ✅ Ya (badge "Sedang Dipesan") | ❌ Tidak |
 | `on_rent` | ✅ Ya (badge "Sedang Disewa") | ❌ Tidak |
 | `maintenance` | ✅ Ya (badge "Maintenance") | ❌ Tidak |
 | `in_transit` | ✅ Ya | ❌ Tidak |
 | `returned` | ✅ Ya | ✅ Ya |

---

## 7. Pagination

- Default: 12 kendaraan per halaman.
- Livewire `WithPagination` untuk navigasi halaman.
- Reset ke halaman 1 setiap kali filter/sort berubah (`updatingSearch`, `updatingSelectedType`, dst).

---

Versi: 1.0.0 | Tanggal: 2026-05-14
