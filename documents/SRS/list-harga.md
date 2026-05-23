# List Harga — Pricing Specification

**Nama File:** `list-harga.md`  
**Lokasi:** `documents/SRS/`  
**Tujuan:** Mendokumentasikan struktur harga rental, denda, promo, DP, dan driver fee.

---

## Metadata Dokumen

 | Atribut | Detail |
|---|---|
 | Nama Project | Siliwangi Rental |
 | Versi | 1.0.0 |
 | Tanggal | 2026-05-14 |

---

## 1. Struktur Harga Rental

### 1.1 Harga Harian

 | Field di Database | Kolom | Tipe |
|---|---|---|
 | Harga per hari | `cars.price_per_day` | decimal(15,2) |

Kalkulasi total biaya harian:

```
total_rental = price_per_day × jumlah_hari
```

### 1.2 Harga Bulanan

 | Field di Database | Kolom | Tipe |
|---|---|---|
 | Harga per bulan | `cars.price_per_month` | decimal(15,2) |

Kalkulasi total biaya bulanan:

```
total_rental = price_per_month × jumlah_bulan
```

Jika kendaraan tidak memiliki `price_per_month`, maka:

```
total_rental = price_per_day × 30 × jumlah_bulan
```

### 1.3 Kalkulasi Total Booking

```
subtotal_rental = harga × durasi

driver_fee = (cars.driver_price × durasi_hari)   [jika memilih driver]
           = 0                                     [jika tanpa driver]

diskon      = subtotal_rental × (promo.discount_percent / 100)
            = promo.discount_amount                [jika nominal tetap]

total_biaya = subtotal_rental + driver_fee - diskon

dp_amount   = total_biaya × (dp_percentage / 100)   [default: 30%]
sisa        = total_biaya - dp_amount
```

---

## 2. Driver Fee

 | Field | Sumber | Keterangan |
|---|---|---|
 | Harga driver per hari | `cars.driver_price` | Per unit kendaraan berbeda |
 | Default driver price | Belum ditentukan pada requirement | Bisa di-set per kendaraan |

Catatan:

- Driver fee dikenakan per hari sewa.
- Jika customer memilih driver saat booking, driver fee otomatis ditambahkan ke total.
- Driver fee tidak berlaku jika customer memilih "Tanpa Driver".

---

## 3. DP (Down Payment) System

 | Parameter | Default | Keterangan |
|---|---|---|
 | Persentase DP | 30% | Configurable di config/rental.php |
 | Minimum DP | Belum ditentukan pada requirement | — |
 | Batas Waktu Bayar DP | 24 jam setelah booking dibuat | Setelah itu → EXPIRED |

### 3.1 Flow DP

```
1. Booking dibuat → Status: PENDING
2. Customer bayar DP via Midtrans
3. DP diterima → Status Payment: PARTIAL PAYMENT
4. Booking Status → PAID
5. Admin approve → Status: CONFIRMED
6. Saat pengembalian → Customer bayar sisa
7. Semua lunas → Status: COMPLETED
```

---

## 4. Denda Keterlambatan

 | Parameter | Nilai | Keterangan |
|---|---|---|
 | Tarif denda per hari | Belum ditentukan pada requirement | Di-set per kendaraan atau global |
 | Perhitungan | Hari terlambat × tarif denda/hari | Dibulatkan ke atas (ceil) |
 | Tolerance | Belum ditentukan pada requirement | Misal: grace period 2 jam |

### 4.1 Kalkulasi Denda

```
hari_terlambat = (tanggal_kembali_aktual - tanggal_kembali_rencana) hari

total_denda = hari_terlambat × denda_per_hari

Contoh:
  Rencana kembali: 10 Mei 2026
  Aktual kembali : 12 Mei 2026
  Terlambat      : 2 hari
  Denda/hari     : Rp 150.000
  Total Denda    : Rp 300.000
```

### 4.2 Penyimpanan Denda

Denda disimpan sebagai payment terpisah dengan `type = 'fine'` di tabel `payments`.

---

## 5. Promo & Diskon

**Tabel:** `promos`

 | Kolom | Tipe | Keterangan |
|---|---|---|
 | `id` | bigint PK | — |
 | `code` | varchar(50) | Kode promo unik (ex: SILIWANGI20) |
 | `name` | varchar(255) | Nama promo |
 | `description` | text | Deskripsi promo |
 | `type` | enum | `percentage`, `fixed_amount` |
 | `value` | decimal(15,2) | Nilai diskon (% atau nominal) |
 | `min_booking_amount` | decimal(15,2) | Minimum total booking |
 | `max_discount_amount` | decimal(15,2) | Maksimum potongan (untuk tipe percentage) |
 | `start_date` | date | Tanggal mulai berlaku |
 | `end_date` | date | Tanggal berakhir |
 | `usage_limit` | integer | Maksimum total penggunaan |
 | `used_count` | integer | Sudah digunakan berapa kali |
 | `per_user_limit` | integer | Maksimum penggunaan per customer |
 | `is_active` | boolean | Status aktif promo |
 | `applicable_car_ids` | json | Kendaraan yang berlaku (null = semua) |

### 5.1 Validasi Promo saat Checkout

```
1. Cek kode promo ada di database
2. Cek is_active = true
3. Cek tanggal: now() antara start_date dan end_date
4. Cek used_count < usage_limit
5. Cek per_user_limit: customer belum melebihi batas
6. Cek min_booking_amount: subtotal_rental >= min_booking_amount
7. Cek applicable_car_ids: kendaraan yang dipilih berlaku
8. Hitung diskon:
   - type percentage: diskon = subtotal × (value / 100), max = max_discount_amount
   - type fixed_amount: diskon = value (jika <= subtotal)
```

---

## 6. Tipe Pembayaran

 | Tipe | Nilai Enum | Keterangan |
|---|---|---|
 | Down Payment | `dp` | Pembayaran awal (DP) |
 | Pelunasan | `settlement` | Sisa pembayaran |
 | Denda | `fine` | Denda keterlambatan |
 | Pembayaran Tambahan | `additional` | Biaya tambahan lainnya |

---

## 7. Metode Pembayaran (via Midtrans)

 | Metode | Tersedia | Keterangan |
|---|---|---|
 | Virtual Account BCA | ✅ | BCA Virtual Account |
 | Virtual Account Mandiri | ✅ | Mandiri Bill Payment |
 | Virtual Account BRI | ✅ | BRI Virtual Account |
 | Virtual Account BNI | ✅ | BNI Virtual Account |
 | QRIS | ✅ | QR Code universal |
 | Kartu Kredit | ✅ | Visa / Mastercard |
 | Alfamart / Indomaret | ✅ | Bayar di minimarket |
 | GoPay | ✅ | E-Wallet GoPay |
 | OVO | ✅ | E-Wallet OVO |
 | ShopeePay | ✅ | E-Wallet ShopeePay |

---

Versi: 1.0.0 | Tanggal: 2026-05-14
