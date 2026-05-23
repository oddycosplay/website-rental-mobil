# Stakeholder — Siliwangi Rental

**Nama File:** `stakeholder.md`  
**Lokasi:** `documents/BRD/`  
**Tujuan:** Mendokumentasikan seluruh stakeholder sistem Siliwangi Rental beserta peran, kepentingan, dan level keterlibatan mereka.

---

## Metadata Dokumen

 | Atribut | Detail |
|---|---|
 | Nama Project | Siliwangi Rental |
 | Versi | 1.0.0 |
 | Tanggal | 2026-05-14 |

---

## 1. Daftar Stakeholder

 | No | Stakeholder | Kategori | Peran dalam Sistem |
|---|---|---|---|
 | 1 | Pemilik Usaha (Owner) | Internal | Pengambil keputusan strategis bisnis |
 | 2 | Admin Pusat | Internal | Pengelola operasional sistem harian |
 | 3 | Finance Staff | Internal | Pengelola keuangan dan pembayaran |
 | 4 | Manajer Cabang | Internal | Pengelola operasional per cabang |
 | 5 | Driver | Internal | Pelaksana rental — mengantar customer |
 | 6 | Customer Terdaftar | Eksternal | Pengguna layanan dengan akun |
 | 7 | Customer Guest | Eksternal | Pengguna layanan tanpa akun |
 | 8 | Tim Developer | Eksternal | Membangun dan memelihara sistem |

---

## 2. Detail Stakeholder

### 2.1 Owner (Pemilik Usaha)

 | Atribut | Detail |
|---|---|
 | Role Sistem | `owner` |
 | Akses | Dashboard analytics, laporan keuangan, KPI, multi-cabang |
 | Kepentingan Utama | Profitabilitas bisnis, pertumbuhan revenue, efisiensi operasional |
 | Keterlibatan | Tinggi — keputusan strategis dan approval kebijakan |
 | Ekspektasi Sistem | Laporan real-time, KPI, statistik cabang |

### 2.2 Admin Pusat

 | Atribut | Detail |
|---|---|
 | Role Sistem | `admin` |
 | Akses | Seluruh modul operasional kecuali laporan keuangan |
 | Kepentingan Utama | Kelancaran proses booking, armada, driver, customer |
 | Keterlibatan | Sangat Tinggi — pengguna sistem harian |
 | Ekspektasi Sistem | Interface admin yang cepat, approval mudah, notifikasi real-time |

### 2.3 Finance Staff

 | Atribut | Detail |
|---|---|
 | Role Sistem | `finance` |
 | Akses | Modul payment, laporan keuangan, export |
 | Kepentingan Utama | Akurasi data keuangan, kemudahan export laporan |
 | Keterlibatan | Tinggi — setiap ada transaksi masuk |
 | Ekspektasi Sistem | Laporan otomatis, export PDF/Excel, tracking pembayaran |

### 2.4 Manajer Cabang

 | Atribut | Detail |
|---|---|
 | Role Sistem | `admin` (scoped ke cabang) |
 | Akses | Kendaraan dan booking di cabang sendiri |
 | Kepentingan Utama | Armada cabang selalu available, kendaraan tidak idle |
 | Keterlibatan | Tinggi |
 | Ekspektasi Sistem | Statistik cabang, monitoring armada |

### 2.5 Driver

 | Atribut | Detail |
|---|---|
 | Role Sistem | `driver` |
 | Akses | Jadwal sendiri, status ketersediaan, inspeksi |
 | Kepentingan Utama | Jadwal yang jelas, tidak bentrok |
 | Keterlibatan | Sedang |
 | Ekspektasi Sistem | Notifikasi jadwal, tampilan sederhana |

### 2.6 Customer Terdaftar

 | Atribut | Detail |
|---|---|
 | Role Sistem | `customer` |
 | Akses | Portal customer: booking, payment, invoice, profil |
 | Kepentingan Utama | Booking mudah, pembayaran aman, invoice tersedia |
 | Keterlibatan | Sedang (per transaksi) |
 | Ekspektasi Sistem | UX bersih, proses cepat, notifikasi jelas |

### 2.7 Customer Guest

 | Atribut | Detail |
|---|---|
 | Role Sistem | `guest` (tanpa akun) |
 | Akses | Guest checkout, tracking via link email |
 | Kepentingan Utama | Booking cepat tanpa harus daftar |
 | Keterlibatan | Rendah (transaksional) |
 | Ekspektasi Sistem | Form sederhana, konfirmasi email |

---

## 3. Stakeholder Interest vs Influence Matrix

```
High Influence
      │
      │  Owner ★         Admin ★
      │
      │  Finance ★        Manajer Cabang
      │
      │  Driver           Tim Developer
      │
      │  Customer Terdaftar
      │  Customer Guest
      │
Low Influence
      └─────────────────────────────────
        Low Interest          High Interest
```

---

## 4. Komunikasi & Laporan

 | Stakeholder | Frekuensi Laporan | Format |
|---|---|---|
 | Owner | Bulanan + real-time dashboard | PDF + Dashboard web |
 | Admin | Harian — real-time | Admin Panel |
 | Finance | Mingguan + bulanan | PDF + Excel |
 | Driver | Harian — real-time jadwal | Notifikasi WhatsApp |
 | Customer | Per-transaksi | WhatsApp + Email + PDF |

---

Versi: 1.0.0 | Tanggal: 2026-05-14
