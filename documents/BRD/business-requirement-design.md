# Business Requirement Design (BRD)

**Nama File:** `business-requirement-design.md`
**Lokasi:** `documents/BRD/`
**Tujuan:** Mendokumentasikan kebutuhan bisnis, scope, stakeholder, KPI, target pengguna, dan requirement fungsional serta non-fungsional sistem Siliwangi Rental.

---

## Metadata Dokumen

 | Atribut | Detail |
|---|---|
 | Nama Project | Siliwangi Rental |
 | Jenis Sistem | Website Rental Mobil |
 | Versi Dokumen | 1.1.0 |
 | Status | Production Ready |
 | Tanggal | 2026-05-14 |
 | Tim | Software House — Full Stack Team |

---

## 1. Tujuan Bisnis

Siliwangi Rental adalah sistem informasi berbasis website yang dirancang untuk mengotomasi seluruh proses bisnis penyewaan mobil — mulai dari pencarian armada, pemesanan, pembayaran, penugasan driver, hingga pengembalian kendaraan dan pelaporan keuangan.

### 1.1 Tujuan Utama

- Menyediakan platform digital terpadu bagi pelanggan untuk memesan kendaraan sewaan secara online.
- Mengurangi ketergantungan pada proses manual (telepon, WhatsApp pribadi, nota fisik).
- Meningkatkan efisiensi operasional cabang dengan manajemen armada dan driver yang terstruktur.
- Mempercepat proses pembayaran melalui integrasi Midtrans payment gateway.
- Memberikan visibilitas data bisnis secara real-time kepada pemilik dan manajemen.

### 1.2 Tujuan Sekunder

- Membangun kepercayaan pelanggan melalui transparansi status booking dan invoice digital.
- Meminimalkan potensi fraud dan kesalahan human error pada proses pembayaran dan approval.
- Mendukung ekspansi multi-cabang tanpa overhead operasional yang signifikan.
- Menghasilkan laporan keuangan dan operasional yang akurat dan dapat diekspor.

---

## 2. Masalah Bisnis

 | No | Masalah | Dampak |
|---|---|---|
 | 1 | Proses pemesanan masih via telepon/WhatsApp pribadi | Rawan kehilangan order, tidak terdokumentasi |
 | 2 | Tidak ada sistem tracking status kendaraan real-time | Konflik jadwal armada, double booking |
 | 3 | Pembayaran via transfer manual tanpa konfirmasi otomatis | Proses lambat, potensi fraud |
 | 4 | Tidak ada laporan keuangan terintegrasi | Susah audit, keputusan bisnis tidak berbasis data |
 | 5 | Manajemen driver tidak terstruktur | Jadwal tumpang tindih, pelanggan tidak mendapat driver |
 | 6 | Tidak ada notifikasi otomatis kepada pelanggan | Pelanggan tidak mengetahui status order mereka |
 | 7 | Multi-cabang dikelola terpisah | Tidak ada visibilitas terpusat bagi pemilik |
 | 8 | Tidak ada sistem denda keterlambatan otomatis | Kehilangan pendapatan dari denda |

---

## 3. Solusi Sistem

 | Masalah | Solusi Sistem |
|---|---|
 | Pemesanan manual | Portal booking online dengan form terstruktur dan validasi data |
 | Double booking | Sistem availability checking real-time berbasis status kendaraan |
 | Pembayaran manual | Integrasi Midtrans (VA, QRIS, CC) dengan webhook otomatis |
 | Tidak ada laporan | Modul laporan dengan export PDF/Excel per periode |
 | Manajemen driver tidak terstruktur | Modul driver scheduling dan availability management |
 | Tidak ada notifikasi | WhatsApp, Email, dan Real-time In-App Bell otomatis per event |
 | Multi-cabang tidak terpadu | Modul branch management dengan statistik per cabang |
 | Denda tidak terhitung otomatis | Sistem kalkulasi denda keterlambatan berdasarkan hari sewa |

---

## 4. Scope Project

### 4.1 In Scope

- Website rental mobil berbasis Laravel 12 + Blade + Tailwind CSS + Alpine.js
- Sistem booking harian dan bulanan
- Integrasi Midtrans payment gateway (DP + Pelunasan + Denda)
- Manajemen armada kendaraan dan status kendaraan
- Manajemen driver dan penjadwalan
- Modul laporan keuangan dan operasional
- Multi-role access control menggunakan Spatie Permission
- Notifikasi WhatsApp, Email, dan Real-time Bell
- Guest Redirection logic (Redirect ke login/WA untuk tamu)
- Multi-branch management
- Admin panel berbasis Filament v4
- Invoice PDF generation
- Dashboard analytics real-time

### 4.2 Out of Scope

- Aplikasi mobile native (iOS/Android)
- Sistem GPS tracking kendaraan secara live
- Marketplace perbandingan harga dengan vendor lain
- Sistem asuransi kendaraan terintegrasi
- Manajemen kepegawaian (HR/payroll)

---

## 5. Stakeholder

 | Stakeholder | Peran | Kepentingan |
|---|---|---|
 | Owner / Pemilik Usaha | Pengambil keputusan bisnis tertinggi | Laporan keuangan, profit, KPI cabang |
 | Admin Pusat | Pengelola seluruh sistem | Semua modul manajemen |
 | Finance Staff | Verifikasi pembayaran, laporan keuangan | Modul payment, laporan revenue |
 | Driver | Menerima jadwal dan booking | Modul driver schedule, status |
 | Customer (Terdaftar) | Melakukan booking kendaraan | Portal booking, invoice, riwayat |
 | Customer (Guest) | Booking tanpa registrasi | Guest checkout flow & redirect |
 | Manajer Cabang | Kelola armada dan operasional cabang | Modul branch, kendaraan, laporan cabang |

---

## 6. KPI Sistem

### 6.1 KPI Bisnis

 | KPI | Target | Satuan |
|---|---|---|
 | Total Booking per Bulan | ≥ 100 | Booking/bulan |
 | Revenue Bulanan | Belum ditentukan pada requirement | IDR |
 | Occupancy Rate Kendaraan | ≥ 70% | % |
 | Booking Completion Rate | ≥ 90% | % |
 | Booking Cancellation Rate | ≤ 10% | % |
 | Rata-rata Waktu Approval Booking | ≤ 2 jam | Jam |

### 6.2 KPI Sistem

 | KPI | Target | Keterangan |
|---|---|---|
 | Uptime Sistem | ≥ 99.5% | Per bulan |
 | Response Time API | ≤ 500ms | Rata-rata |
 | Page Load Time | ≤ 3 detik | Frontend |
 | Payment Success Rate | ≥ 95% | Midtrans |
 | Notification Delivery Rate | ≥ 98% | WhatsApp/Email/Bell |
 | Report Generation Time | ≤ 10 detik | PDF/Excel |

---

## 7. Target User

### 7.1 Customer

- Individu yang membutuhkan kendaraan sewaan (harian/bulanan)
- Perusahaan yang membutuhkan armada kendaraan operasional
- Rentang usia: 22–55 tahun
- Familiar dengan pemesanan online
- Memiliki KTP dan SIM aktif

### 7.2 Admin & Staff

- Staff internal perusahaan Siliwangi Rental
- Memiliki akses admin panel Filament
- Bertugas mengelola booking, pembayaran, armada, driver, dan laporan

---

## 8. Business Requirement

### 8.1 Customer Portal

 | ID | Requirement |
|---|---|
 | BR-CUS-01 | Customer dapat melakukan registrasi dengan email dan password |
 | BR-CUS-02 | Customer dapat login menggunakan akun terdaftar |
 | BR-CUS-03 | Customer dapat melakukan booking sebagai guest (tanpa registrasi awal) |
 | BR-CUS-04 | Sistem secara otomatis mengarahkan guest ke login/registrasi atau jalur khusus WhatsApp jika diperlukan (Guest Redirection) |
 | BR-CUS-05 | Customer dapat melihat riwayat booking dan status terkini |
 | BR-CUS-06 | Customer dapat mengunduh invoice dalam format PDF |
 | BR-CUS-07 | Customer dapat mengelola profil dan data diri |
 | BR-CUS-08 | Customer mendapatkan notifikasi booking dan pembayaran melalui WA dan In-App Bell |
 | BR-CUS-09 | Customer dapat mengupload KTP dan SIM saat booking |

### 8.2 Booking System

 | ID | Requirement |
|---|---|
 | BR-BOOK-01 | Sistem mendukung rental harian dan bulanan |
 | BR-BOOK-02 | Customer dapat memilih menggunakan driver atau tidak |
 | BR-BOOK-03 | Sistem menampilkan ketersediaan kendaraan secara real-time |
 | BR-BOOK-04 | Booking memerlukan approval dari admin |
 | BR-BOOK-05 | Booking yang tidak dibayar dalam batas waktu otomatis expired |
 | BR-BOOK-06 | Customer dapat membatalkan booking sebelum confirmed |
 | BR-BOOK-07 | Sistem menghitung denda keterlambatan pengembalian otomatis |
 | BR-BOOK-08 | Sistem mengirimkan reminder sebelum tanggal sewa |

### 8.3 Payment System

 | ID | Requirement |
|---|---|
 | BR-PAY-01 | Pembayaran diproses melalui Midtrans payment gateway |
 | BR-PAY-02 | Sistem mendukung pembayaran DP (uang muka) |
 | BR-PAY-03 | Sistem mendukung pelunasan sisa pembayaran |
 | BR-PAY-04 | Sistem mendukung pembayaran denda |
 | BR-PAY-05 | Sistem mengenerate invoice PDF otomatis setelah pembayaran |
 | BR-PAY-06 | Notifikasi otomatis dikirim saat pembayaran berhasil |
 | BR-PAY-07 | Refund diproses manual oleh Finance setelah booking dibatalkan |

### 8.4 Vehicle Management

 | ID | Requirement |
|---|---|
 | BR-VEH-01 | Admin dapat mengelola data armada kendaraan |
 | BR-VEH-02 | Status kendaraan diperbarui otomatis berdasarkan workflow booking |
 | BR-VEH-03 | Sistem mendukung penjadwalan maintenance kendaraan |
 | BR-VEH-04 | Sistem mencatat inspeksi kendaraan sebelum dan sesudah rental |
 | BR-VEH-05 | Kendaraan yang sedang maintenance tidak dapat dibooking |

### 8.5 Driver Management

 | ID | Requirement |
|---|---|
 | BR-DRV-05 | Customer dapat memberikan rating kepada driver |

### 8.6 User & Access Management

 | ID | Requirement |
|---|---|
 | BR-ACC-01 | Registrasi publik hanya diperuntukkan bagi Customer (otomatis role: customer). |
| BR-ACC-02 | Pendaftaran akun internal (Admin, Finance, Driver, dll) hanya dapat dilakukan oleh Super Admin dan Owner melalui Admin Panel. |
| BR-ACC-03 | Super Admin dan Owner memiliki akses penuh untuk mengelola role dan permission seluruh pengguna. |
| BR-ACC-04 | Karyawan tidak diperbolehkan mendaftar melalui jalur publik untuk menjaga keamanan akses sistem. |

---

## 9. Functional Requirement

 | ID | Modul | Deskripsi |
|---|---|---|
 | FR-01 | Auth | Register (Customer Only), Login, Forgot Password, Reset Password, Email Verification |
 | FR-02 | Catalog | Tampilkan catalog mobil dengan filter, search, sort, dan featured car |
 | FR-03 | Booking | Form booking, pilih tanggal, pilih driver, upload dokumen |
 | FR-04 | Payment | Proses DP, pelunasan, denda via Midtrans; tracking status pembayaran |
 | FR-05 | Invoice | Generate invoice PDF otomatis dengan kalkulasi durasi dan biaya tambahan |
 | FR-06 | Vehicle | CRUD kendaraan, manajemen status, maintenance, inspeksi |
 | FR-07 | Driver | CRUD driver, jadwal, availability, rating |
 | FR-08 | Notification | Notifikasi WhatsApp (via Queue), Email, dan Real-time Notification Bell |
 | FR-09 | Admin Panel | Dashboard, manajemen seluruh entitas, laporan, multi-role (User & Role restricted to Super Admin) |
 | FR-10 | Report | Laporan keuangan, booking, kendaraan, driver; export PDF/Excel |
 | FR-11 | Branch | CRUD cabang, statistik per cabang, monitoring armada cabang |
 | FR-12 | Promo | CRUD promo, kode diskon, validasi saat checkout |
 | FR-13 | Activity Log | Pencatatan seluruh aktivitas user dan admin di sistem |

---

## 10. Non-Functional Requirement

 | ID | Kategori | Requirement |
|---|---|---|
 | NFR-01 | Performance | Halaman frontend load < 3 detik pada koneksi 10 Mbps |
 | NFR-02 | Availability | Sistem tersedia ≥ 99.5% per bulan (downtime < 3.6 jam/bulan) |
 | NFR-03 | Security | Autentikasi berbasis session dengan CSRF protection; enkripsi data sensitif |
 | NFR-04 | Scalability | Arsitektur mendukung penambahan cabang tanpa perubahan codebase signifikan |
 | NFR-05 | Maintainability | Codebase mengikuti PSR-12, Laravel best practice, dan modular service layer |
 | NFR-06 | Usability | Antarmuka responsif dan mendukung perangkat mobile (smartphone dan tablet) |
 | NFR-07 | Reliability | Transaksi pembayaran menggunakan webhook Midtrans dengan retry mechanism |
 | NFR-08 | Auditability | Seluruh aksi penting tercatat dalam activity log dengan timestamp |
 | NFR-09 | Compatibility | Browser modern: Chrome, Firefox, Safari, Edge (versi 2 tahun terakhir) |
 | NFR-10 | Data Integrity | Foreign key constraint aktif di seluruh relasi database MySQL |

---

## 11. Business Workflow (Overview)

```
Customer → Pilih Kendaraan → Form Booking → Upload Dokumen
       → Bayar DP (Midtrans) → Admin Approval → Status: Confirmed
       → Hari Rental → Ambil Kendaraan → Status: On Rent
       → Pengembalian → Inspeksi → Kalkulasi Denda (jika ada)
       → Pelunasan + Denda → Status: Completed → Invoice PDF
```

Detail workflow per proses terdapat di `documents/BRD/workflow.md`.

---

*Dokumen ini dibuat oleh tim Software House Siliwangi Rental.*
Versi: 1.1.0 | Tanggal: 2026-05-14
