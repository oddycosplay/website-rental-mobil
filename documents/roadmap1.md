# 📋 Roadmap Implementasi Siliwangi Rental

Roadmap ini merinci tahapan pengembangan teknis untuk menyelesaikan platform Siliwangi Rental secara sistematis dari backend (Admin) hingga frontend (Customer).

## 🚀 Fase 1: Standarisasi Database & Backend (Selesai)

**Tujuan:** Memiliki fondasi database yang sah (Migration) dan Admin Panel yang 100% fungsional.

- [x] Merancang ERD Kelas Enterprise (Multi-cabang, Multi-role, Payment).
- [x] Menginstal Filament PHP sebagai Admin Dashboard.
- [x] Menerjemahkan skema SQL mentah menjadi file `Migration` Laravel resmi.
- [x] Menyempurnakan Eloquent `Models` dengan mutator dan _casts_.
- [x] Membuat `Database Seeder` (mengonversi data dummy SQL ke format class Seeder).
- [x] Kustomisasi Filament Resource (Menambahkan file upload untuk gambar mobil, KTP pelanggan, relasi Select dropdown, dan Validasi).

## 🏢 Fase 2: Manajemen Operasional & Roles (Selesai)

**Tujuan:** Mengatur hak akses staf dan sistem penjadwalan.

- [x] Integrasi `spatie/laravel-permission` ke dalam Filament.
- [x] Setup Role: Super Admin, Owner, Finance, dan Customer.
- [x] Sistem Penjadwalan Driver (`DriverSchedule`) yang mencegah _double-booking_ sopir pada hari yang sama.
- [x] Status Armada Real-time (Available, Rented, Maintenance) yang ter-update otomatis saat Booking berjalan.

## 🛍️ Fase 3: Frontend PWA & Sistem Booking Online (Selesai)

**Tujuan:** Membangun antarmuka untuk pelanggan yang bisa digunakan di web & mobile.

- [x] Merombak Landing Page dengan desain UI modern (menggunakan Tailwind & Anime.js).
- [x] Halaman Katalog Mobil dengan fitur filter (berdasarkan cabang, harga, transmisi).
- [x] Flow Checkout Multi-Step (Livewire):
    1. Pilih Tanggal & Lokasi (Cabang).
    2. Cek Ketersediaan Mobil & Harga.
    3. Pilih Ekstra (Pakai Sopir / Lepas Kunci).
    4. Masukkan Promo.
    5. Konfirmasi Ringkasan Pembayaran.
- [x] Customer Dashboard (Melihat riwayat sewa, update profil, mencetak invoice).

## 💳 Fase 4: Integrasi Payment Gateway (Midtrans) (Selesai)

**Tujuan:** Otomatisasi penerimaan uang.

- [x] Integrasi Midtrans Snap API di halaman Checkout.
- [x] Pembuatan Webhook / Endpoint Callback untuk menerima notifikasi dari Midtrans.
- [x] Sistem auto-update status pembayaran (`payment_logs`) dari `pending` ke `paid` atau `expired`.
- [x] Auto-generate PDF Invoice & Surat Jalan (Kwitansi Sewa).

## 📈 Fase 5: Reporting, Analytics & Ekstra (Selesai)

**Tujuan:** Fitur pelaporan untuk Owner dan optimalisasi Marketing.

- [x] Dashboard Widgets di Filament (Grafik Pendapatan Bulanan, Mobil Paling Sering Disewa).
- [x] Modul Ekspor Laporan ke Excel / PDF untuk divisi Finance.
- [x] Sistem Analytics (Utilization Rate, Customer Lifetime Value).
- [x] Integrasi API WhatsApp Gateway (menggunakan Fonnte) untuk notifikasi otomatis saat Booking sukses atau Pembayaran tertunda.
- [x] Testing E2E (End-to-End) dan persiapan _Deployment_ ke Production Server (VPS / Cloud).

## 🛡️ Fase 6: System Hardening & Advanced Features (Selesai)

**Tujuan:** Meningkatkan keandalan sistem untuk traffic tinggi dan manajemen armada yang lebih ketat.

- [x] **Sistem Pengawasan Armada (Damage Reporting)**: Modul inspeksi mobil (Checklist & Foto) saat serah terima (Pickup & Return).
- [x] **Otomatisasi Penalti (Late Return)**: Perhitungan denda otomatis jika pengembalian melewati batas waktu yang ditentukan.
- [x] **Reliability Notifikasi (Job Queues)**: Implementasi Laravel Queue untuk pengiriman WhatsApp agar lebih tahan terhadap gangguan API ekspor.
- [x] **Sinkronisasi Pembayaran (Auto-Polling)**: Task Scheduler untuk verifikasi status transaksi ke API Midtrans secara berkala.
- [x] **Security & Optimization**: Implementasi Rate Limiting, Sitemap otomatis untuk SEO, dan caching ketersediaan mobil.
