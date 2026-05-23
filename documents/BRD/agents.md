# Agents & Role Matrix — Siliwangi Rental

**Nama File:** `agents.md`  
**Lokasi:** `documents/BRD/`  
**Tujuan:** Mendokumentasikan role matrix, permission matrix, dan kapabilitas setiap aktor/agent dalam sistem Siliwangi Rental.

---

## Metadata Dokumen

 | Atribut | Detail |
|---|---|
 | Nama Project | Siliwangi Rental |
 | Versi | 1.0.0 |
 | Tanggal | 2026-05-14 |

---

## 1. Daftar Role

 | Role | Kode | Deskripsi |
|---|---|---|
 | Owner | `owner` | Pemilik usaha, akses laporan dan analytics tertinggi |
 | Admin | `admin` | Pengelola sistem harian — booking, armada, driver |
 | Finance | `finance` | Pengelola keuangan — pembayaran, refund, laporan keuangan |
 | Driver | `driver` | Driver kendaraan — lihat jadwal dan booking yang ditugaskan |
 | Customer | `customer` | Pelanggan — booking, payment, invoice |

---

## 2. Permission Matrix

### 2.1 Modul Booking

 | Permission | Owner | Admin | Finance | Driver | Customer |
|---|---|---|---|---|---|
 | view any booking | ✅ | ✅ | ✅ | ❌ | ❌ |
 | view own booking | ✅ | ✅ | ✅ | ✅ | ✅ |
 | create booking | ❌ | ✅ | ❌ | ❌ | ✅ |
 | approve booking | ❌ | ✅ | ❌ | ❌ | ❌ |
 | cancel booking (admin) | ❌ | ✅ | ❌ | ❌ | ❌ |
 | cancel booking (own) | ❌ | ❌ | ❌ | ❌ | ✅ |
 | return processing | ❌ | ✅ | ❌ | ✅ | ❌ |
 | assign driver | ❌ | ✅ | ❌ | ❌ | ❌ |

### 2.2 Modul Payment

 | Permission | Owner | Admin | Finance | Driver | Customer |
|---|---|---|---|---|---|
 | view all payments | ✅ | ✅ | ✅ | ❌ | ❌ |
 | view own payment | ✅ | ✅ | ✅ | ❌ | ✅ |
 | create payment | ❌ | ❌ | ❌ | ❌ | ✅ |
 | confirm payment manual | ❌ | ✅ | ✅ | ❌ | ❌ |
 | process refund | ❌ | ❌ | ✅ | ❌ | ❌ |
 | view revenue report | ✅ | ❌ | ✅ | ❌ | ❌ |
 | export financial report | ✅ | ❌ | ✅ | ❌ | ❌ |

### 2.3 Modul Kendaraan

 | Permission | Owner | Admin | Finance | Driver | Customer |
|---|---|---|---|---|---|
 | view vehicle list | ✅ | ✅ | ✅ | ✅ | ✅ |
 | create vehicle | ❌ | ✅ | ❌ | ❌ | ❌ |
 | edit vehicle | ❌ | ✅ | ❌ | ❌ | ❌ |
 | delete vehicle | ❌ | ✅ | ❌ | ❌ | ❌ |
 | update vehicle status | ❌ | ✅ | ❌ | ❌ | ❌ |
 | schedule maintenance | ❌ | ✅ | ❌ | ❌ | ❌ |
 | create inspection | ❌ | ✅ | ❌ | ✅ | ❌ |

### 2.4 Modul Driver

 | Permission | Owner | Admin | Finance | Driver | Customer |
|---|---|---|---|---|---|
 | view all drivers | ✅ | ✅ | ❌ | ❌ | ❌ |
 | create driver | ❌ | ✅ | ❌ | ❌ | ❌ |
 | edit driver | ❌ | ✅ | ❌ | ❌ | ❌ |
 | view own profile | ❌ | ❌ | ❌ | ✅ | ❌ |
 | view own schedule | ❌ | ❌ | ❌ | ✅ | ❌ |
 | update availability | ❌ | ✅ | ❌ | ✅ | ❌ |

### 2.5 Modul Laporan

 | Permission | Owner | Admin | Finance | Driver | Customer |
|---|---|---|---|---|---|
 | view revenue report | ✅ | ❌ | ✅ | ❌ | ❌ |
 | view booking report | ✅ | ✅ | ✅ | ❌ | ❌ |
 | view vehicle report | ✅ | ✅ | ❌ | ❌ | ❌ |
 | view driver report | ✅ | ✅ | ❌ | ❌ | ❌ |
 | view customer report | ✅ | ✅ | ✅ | ❌ | ❌ |
 | view expense report | ✅ | ❌ | ✅ | ❌ | ❌ |
 | view KPI dashboard | ✅ | ✅ | ✅ | ❌ | ❌ |
 | export PDF | ✅ | ✅ | ✅ | ❌ | ❌ |
 | export Excel | ✅ | ✅ | ✅ | ❌ | ❌ |

### 2.6 Modul Manajemen Sistem

 | Permission | Owner | Admin | Finance | Driver | Customer |
|---|---|---|---|---|---|
 | manage users | ✅ | ✅ | ❌ | ❌ | ❌ |
 | manage roles | ✅ | ❌ | ❌ | ❌ | ❌ |
 | manage branches | ✅ | ✅ | ❌ | ❌ | ❌ |
 | manage promos | ✅ | ✅ | ❌ | ❌ | ❌ |
 | view activity log | ✅ | ✅ | ❌ | ❌ | ❌ |

---

## 3. Kapabilitas Owner

- Akses penuh ke seluruh dashboard analytics dan laporan keuangan.
- Melihat performa seluruh cabang (booking, revenue, occupancy).
- Manage roles dan permissions sistem.
- Melihat laporan KPI, laba kotor, laba bersih, dan pengeluaran.
- Tidak mengelola operasional harian secara langsung.

---

## 4. Kapabilitas Admin

- Kelola seluruh booking: approve, reject, assign driver, return processing.
- Kelola armada kendaraan: CRUD, status, maintenance, inspeksi.
- Kelola driver: CRUD, jadwal, availability.
- Kelola cabang, promo, dan user.
- Lihat activity log sistem.
- Export laporan booking dan kendaraan.

---

## 5. Kapabilitas Finance

- Melihat seluruh transaksi pembayaran.
- Konfirmasi pembayaran manual (jika diperlukan).
- Memproses refund setelah booking dibatalkan.
- Membuat dan melihat laporan keuangan (revenue, expense, profit).
- Export laporan keuangan PDF dan Excel.
- Tidak dapat mengubah data booking atau kendaraan.

---

## 6. Kapabilitas Driver

- Melihat jadwal booking yang ditugaskan.
- Update status ketersediaan diri sendiri.
- Melihat profil diri sendiri.
- Mencatat inspeksi kendaraan (pre/post rental).
- Tidak dapat mengakses data pembayaran atau customer.

---

## 7. Kapabilitas Customer

- Registrasi dan login akun.
- Browse katalog kendaraan (tanpa login).
- Membuat booking (harian/bulanan, dengan/tanpa driver).
- Melakukan pembayaran DP dan pelunasan via Midtrans.
- Membatalkan booking sebelum status Confirmed.
- Melihat riwayat booking dan status real-time.
- Download invoice PDF.
- Mengelola profil akun.
- Memberikan rating ke driver.

---

## 8. Implementasi Role di Spatie Permission

```php
// Roles yang di-seed
Role::create(['name' => 'owner']);
Role::create(['name' => 'admin']);
Role::create(['name' => 'finance']);
Role::create(['name' => 'driver']);
Role::create(['name' => 'customer']);

// Middleware di routes
Route::middleware(['auth', 'role:admin|owner'])->group(function () {
    // Admin routes
});

Route::middleware(['auth', 'role:finance|owner'])->group(function () {
    // Finance routes
});
```

---

Versi: 1.0.0 | Tanggal: 2026-05-14
