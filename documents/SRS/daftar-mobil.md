# Daftar Mobil — Struktur Data Kendaraan

**Nama File:** `daftar-mobil.md`  
**Lokasi:** `documents/SRS/`  
**Tujuan:** Mendokumentasikan struktur data kendaraan, status availability, fasilitas, dan status maintenance.

---

## Metadata Dokumen

 | Atribut | Detail |
|---|---|
 | Nama Project | Siliwangi Rental |
 | Versi | 1.0.0 |
 | Tanggal | 2026-05-14 |

---

## 1. Struktur Data Kendaraan (Model: Car)

**Tabel:** `cars`

 | Kolom | Tipe | Nullable | Keterangan |
|---|---|---|---|
 | `id` | bigint PK | No | Primary key |
 | `car_brand_id` | bigint FK | No | Relasi ke `car_brands` |
 | `car_type_id` | bigint FK | No | Relasi ke `car_types` |
 | `branch_id` | bigint FK | No | Cabang pemilik kendaraan |
 | `name` | varchar(255) | No | Nama kendaraan (ex: "Toyota Avanza 2023") |
 | `slug` | varchar(255) | No | URL-friendly identifier |
 | `plate_number` | varchar(20) | No | Nomor plat kendaraan |
 | `year` | year | No | Tahun produksi |
 | `color` | varchar(50) | Yes | Warna kendaraan |
 | `transmission` | enum | No | `manual`, `automatic` |
 | `fuel` | enum | Yes | `bensin`, `solar`, `hybrid`, `listrik` |
 | `capacity` | tinyint | No | Kapasitas penumpang (2–12) |
 | `mileage` | integer | Yes | Odometer saat ini (km) |
 | `category` | enum | Yes | `Pribadi`, `Perusahaan` |
 | `price_per_day` | decimal(15,2) | No | Harga sewa harian |
 | `price_per_month` | decimal(15,2) | Yes | Harga sewa bulanan |
 | `driver_price` | decimal(15,2) | Yes | Biaya driver per hari |
 | `description` | text | Yes | Deskripsi kendaraan |
 | `image` | varchar(255) | Yes | Path foto utama |
 | `features` | json | Yes | Fasilitas (AC, GPS, dll) |
 | `status` | enum | No | `available`, `booked`, `on_rent`, `maintenance`, `in_transit`, `returned` |
 | `is_featured` | boolean | No | Tampil di homepage |
 | `is_active` | boolean | No | Aktif di katalog |
 | `stock` | tinyint | No | Jumlah unit |
 | `call_status` | boolean | Yes | Flag "hubungi untuk harga" |
 | `created_at` | timestamp | — | — |
 | `updated_at` | timestamp | — | — |
 | `deleted_at` | timestamp | Yes | Soft delete |

---

## 2. Relasi Model Car

```php
// app/Models/Car.php

public function carBrand(): BelongsTo      // → CarBrand
public function carType(): BelongsTo       // → CarType
public function branch(): BelongsTo        // → Branch
public function bookings(): HasMany        // → Booking
public function maintenances(): HasMany    // → CarMaintenance
public function inspections(): HasMany     // → CarInspection
public function locations(): HasMany       // → CarLocation

// Scope
public function scopeAvailable($query)    // status = available
public function scopeFeatured($query)     // is_featured = true
public function scopeActive($query)       // is_active = true
```

---

## 3. Status Kendaraan

 | Status | Nilai | Deskripsi | Bisa di-booking |
|---|---|---|---|
 | Available | `available` | Kendaraan siap disewa | ✅ Ya |
 | Booked | `booked` | Sudah ada booking (belum ambil) | ❌ Tidak |
 | On Rent | `on_rent` | Sedang dalam masa sewa | ❌ Tidak |
 | Maintenance | `maintenance` | Sedang diservis/diperbaiki | ❌ Tidak |
 | In Transit | `in_transit` | Sedang dalam perjalanan antar cabang | ❌ Tidak |
 | Returned | `returned` | Baru dikembalikan (menunggu cek) | ✅ Ya (setelah inspeksi) |

### 3.1 Alur Perubahan Status Otomatis

 | Event | Status Sebelum | Status Setelah |
|---|---|---|
 | Admin approve booking | `available` | `booked` |
 | Customer mulai sewa | `booked` | `on_rent` |
 | Customer kembalikan | `on_rent` | `returned` |
 | Inspeksi selesai OK | `returned` | `available` |
 | Admin jadwal maintenance | `available` | `maintenance` |
 | Maintenance selesai | `maintenance` | `available` |

---

## 4. Fasilitas Kendaraan (features JSON)

Field `features` disimpan sebagai JSON array:

```json
[
  "AC",
  "Musik",
  "GPS",
  "Kamera Mundur",
  "Kursi Bayi",
  "Wifi",
  "USB Charger",
  "Sunroof",
  "Airbag",
  "ABS"
]
```

Ditampilkan di halaman detail kendaraan sebagai icon list.

---

## 5. Tipe Kendaraan (car_types)

**Tabel:** `car_types`

 | Kolom | Tipe | Keterangan |
|---|---|---|
 | `id` | bigint PK | — |
 | `name` | varchar | Nama tipe (SUV, MPV, Sedan, dst) |
 | `slug` | varchar | URL slug |
 | `icon` | varchar | Nama icon (opsional) |
 | `description` | text | Deskripsi tipe |

Contoh data:

 | name | slug |
|---|---|
 | SUV | suv |
 | MPV | mpv |
 | Sedan | sedan |
 | Hatchback | hatchback |
 | Pick Up | pick-up |
 | Minibus | minibus |

---

## 6. Merek Kendaraan (car_brands)

**Tabel:** `car_brands`

 | Kolom | Tipe | Keterangan |
|---|---|---|
 | `id` | bigint PK | — |
 | `name` | varchar | Nama merek (Toyota, Honda, dst) |
 | `logo` | varchar | Path logo merek |

---

## 7. Maintenance Status

**Tabel:** `car_maintenances`

 | Kolom | Tipe | Keterangan |
|---|---|---|
 | `id` | bigint PK | — |
 | `car_id` | bigint FK | Kendaraan yang di-maintenance |
 | `type` | varchar | Jenis maintenance (Servis Berkala, Ganti Ban, dst) |
 | `description` | text | Keterangan pekerjaan |
 | `start_date` | date | Tanggal mulai |
 | `end_date` | date | Tanggal selesai |
 | `cost` | decimal | Biaya maintenance |
 | `status` | enum | `scheduled`, `in_progress`, `completed` |
 | `notes` | text | Catatan tambahan |

---

## 8. Inspeksi Kendaraan (car_inspections)

**Tabel:** `car_inspections`

 | Kolom | Tipe | Keterangan |
|---|---|---|
 | `id` | bigint PK | — |
 | `booking_id` | bigint FK | Booking terkait |
 | `car_id` | bigint FK | Kendaraan |
 | `inspector_id` | bigint FK | User yang inspeksi |
 | `type` | enum | `pre_rental`, `post_rental` |
 | `exterior_condition` | enum | `good`, `minor_damage`, `major_damage` |
 | `interior_condition` | enum | `good`, `dirty`, `damaged` |
 | `mileage_start` | integer | KM awal |
 | `mileage_end` | integer | KM akhir (post_rental) |
 | `fuel_level` | varchar | Full / 3/4 / 1/2 / 1/4 |
 | `notes` | text | Catatan temuan |
 | `photos` | json | Path foto-foto inspeksi |
 | `inspected_at` | datetime | Waktu inspeksi |

---

Versi: 1.0.0 | Tanggal: 2026-05-14
