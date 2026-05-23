# Booking System Specification — Siliwangi Rental

**Nama File:** `booking-system.md`  
**Lokasi:** `documents/SRS/`  
**Tujuan:** Spesifikasi teknis lengkap modul booking — form, validasi, status, auto-expire, denda, dan pengembalian.

---

## Metadata Dokumen

 | Atribut | Detail |
|---|---|
 | Nama Project | Siliwangi Rental |
 | Versi | 1.0.0 |
 | Tanggal | 2026-05-14 |

---

## 1. Model Booking

**Tabel:** `bookings`

 | Kolom | Tipe | Keterangan |
|---|---|---|
 | `id` | bigint PK | — |
 | `booking_code` | varchar(20) | Kode unik: SR-YYYYMMDD-XXXX |
 | `user_id` | bigint FK | NULL jika guest |
 | `customer_id` | bigint FK | Data profil customer |
 | `car_id` | bigint FK | Kendaraan yang disewa |
 | `driver_id` | bigint FK | NULL jika tanpa driver |
 | `branch_id` | bigint FK | Cabang asal kendaraan |
 | `booking_category` | enum | `harian`, `bulanan` |
 | `start_date` | date | Tanggal mulai sewa |
 | `end_date` | date | Tanggal berakhir sewa |
 | `duration` | integer | Jumlah hari/bulan |
 | `with_driver` | boolean | Pakai driver / tidak |
 | `subtotal` | decimal(15,2) | Biaya sewa sebelum diskon |
 | `driver_fee` | decimal(15,2) | Biaya driver total |
 | `discount_amount` | decimal(15,2) | Nilai diskon dari promo |
 | `promo_id` | bigint FK | NULL jika tanpa promo |
 | `total_amount` | decimal(15,2) | Total yang harus dibayar |
 | `dp_amount` | decimal(15,2) | Jumlah DP yang dibayar |
 | `remaining_amount` | decimal(15,2) | Sisa yang harus dibayar |
 | `fine_amount` | decimal(15,2) | Denda keterlambatan |
 | `status` | enum | pending/paid/confirmed/on_rent/returned/completed/cancelled/expired |
 | `guest_token` | varchar(100) | UUID untuk guest tracking |
 | `guest_name` | varchar(255) | Nama guest (jika guest) |
 | `guest_email` | varchar(255) | Email guest |
 | `guest_phone` | varchar(20) | Telepon guest |
 | `ktp_path` | varchar(255) | Path file KTP |
 | `sim_path` | varchar(255) | Path file SIM |
 | `notes` | text | Catatan booking |
 | `admin_notes` | text | Catatan admin |
 | `approved_by` | bigint FK | User admin yang approve |
 | `approved_at` | datetime | Waktu approval |
 | `cancelled_by` | bigint FK | User yang batalkan |
 | `cancelled_at` | datetime | Waktu pembatalan |
 | `cancellation_reason` | text | Alasan pembatalan |
 | `payment_due_at` | datetime | Batas waktu bayar DP |
 | `actual_return_date` | date | Tanggal kembali aktual |
 | `created_at` | timestamp | — |
 | `updated_at` | timestamp | — |

---

## 2. Checkout Form (Livewire — 5 Step)

**File:** `app/Livewire/Checkout.php`

### Step 1: Konfigurasi Sewa & Rincian

- **Input:**
  - `start_date`, `end_date`, `booking_category` (harian/bulanan).
  - `need_type` (Jemput/Diantar), `pickup_location` (text).
  - `trip_category` (Wisata, Bisnis, Personal).
- **Logic:**
  - Menampilkan catatan: "1 Hari sewa = 24 Jam".
  - Durasi dihitung berdasarkan selisih jam (pembulatan ke atas per 24 jam).
  - Tampilkan rincian: Sewa Dasar, Biaya Layanan, Promo, Total (Inc Tax).

### Step 2: Data Pemesan

- **Input:** `name`, `address`, `phone` (WhatsApp format), `email`.
- **Validation:** Phone must be in International Format (62xxx) for WhatsApp API.

### Step 3: Upload Dokumen Legal

- **Input (Multiple Files):**
  - `ktp_file`, `sim_file` (Required).
  - `kartu_pelajar`, `kartu_mahasiswa`, `kk_file`, `npwp_file` (Optional/Contextual).
- **Validation:** Image/PDF, max 2MB per file.
- **Storage:** `storage/app/private/documents/{booking_code}/`.

### Step 4: Opsi Driver & Catatan Tambahan

- **Input:**
  - `with_driver` (boolean toggle: Lepas Kunci vs Pake Driver).
  - `additional_notes` (text, optional).
- **Logic:** Update `driver_fee` dan `total_amount` secara real-time saat toggle berubah.

### Step 5: Ringkasan & Konfirmasi

- **Display:** Ringkasan semua input dari Step 1-4.
- **Action:**
  - Save to DB (status: `pending`).
  - Generate `guest_token` (jika guest).
  - **Redirect 1:** Ke Midtrans Snap UI untuk bayar DP.
  - **Job:** Dispatch Notifikasi WhatsApp ke Customer (Info Booking) & Admin (Info Pesanan Baru).
  - **Redirect 2 (After Payment):** Ke WhatsApp Admin untuk koordinasi serah terima.

---

## 3. Status Booking & Transisi

 | Status | Kode | Trigger |
|---|---|---|
 | Pending | `pending` | Booking baru dibuat |
 | Paid | `paid` | DP berhasil dibayar (webhook Midtrans) |
 | Confirmed | `confirmed` | Admin approve booking |
 | On Rent | `on_rent` | Admin tandai mulai sewa |
 | Returned | `returned` | Admin proses pengembalian |
 | Completed | `completed` | Semua pembayaran lunas setelah return |
 | Cancelled | `cancelled` | Admin/Customer batalkan booking |
 | Expired | `expired` | Batas waktu DP habis (auto-scheduler) |

---

## 4. Auto-Expired Booking

**File:** `app/Console/Commands/ExpireBookings.php`

```php
// Jalankan setiap 15 menit via Scheduler
$schedule->command('bookings:expire')->everyFifteenMinutes();

// Logic:
Booking::where('status', 'pending')
    ->where('payment_due_at', '<', now())
    ->each(function ($booking) {
        $booking->update(['status' => 'expired']);
        // Update payment status ke expired
        // Bebaskan slot kendaraan
        // Kirim notifikasi email ke customer
    });
```

---

## 5. Booking Cancellation

### Oleh Customer

```
Kondisi yang diizinkan:
  - Status: pending (sebelum bayar)
  - Status: paid (sebelum admin confirm)

Proses:
  - Update status → cancelled
  - Simpan cancellation_reason
  - Jika sudah bayar DP: Finance proses refund manual
  - Bebaskan slot kendaraan
  - Notifikasi customer (booking dibatalkan)
```

### Oleh Admin

```
Kondisi: Status apapun kecuali completed
Proses:
  - Update status → cancelled
  - Isi admin_notes
  - Proses refund DP (jika ada)
  - Bebaskan slot kendaraan dan driver
  - Notifikasi customer
  - Catat di activity log
```

---

## 6. Pengembalian & Denda

```
Admin → Return Processing → Input tanggal kembali aktual

if (actual_return_date > end_date):
    days_late = diff(actual_return_date, end_date)
    fine_amount = days_late × denda_per_hari
    Buat Payment baru: type='fine', amount=fine_amount, status='pending'
    Notifikasi customer: ada tagihan denda

Update:
  - bookings.actual_return_date = input
  - bookings.status → returned
  - cars.status → returned (menunggu inspeksi)

Setelah denda dibayar (atau jika tidak ada denda):
  - bookings.status → completed
  - Generate final invoice PDF
  - Notifikasi customer: booking selesai
```

---

## 7. Booking Reminder (Scheduler)

```
Harian (jam 08:00 WIB):
  1. Query booking confirmed, start_date = tomorrow
     → Kirim reminder WhatsApp + Email ke customer (H-1)

  2. Query booking on_rent, end_date = today
     → Kirim reminder pengembalian hari ini

  3. Query booking on_rent, end_date < today
     → Kirim notifikasi keterlambatan ke customer + admin
```

---

## 8. Generate Booking Code

```php
// Format: SR-YYYYMMDD-XXXX (4 digit angka unik per hari)

$date = now()->format('Ymd');
$lastToday = Booking::whereDate('created_at', today())->count() + 1;
$booking_code = 'SR-' . $date . '-' . str_pad($lastToday, 4, '0', STR_PAD_LEFT);

// Contoh: SR-20260514-0001
```

---

Versi: 1.0.0 | Tanggal: 2026-05-14
