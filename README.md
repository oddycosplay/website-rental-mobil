# 🚗 Siliwangi Rental — Platform Manajemen & Penyewaan Mobil Premium

[![Laravel Version](https://img.shields.io/badge/laravel-v11.x-red.svg?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/php-v8.2+-blue.svg?style=flat-square&logo=php)](https://php.net)
[![Livewire Version](https://img.shields.io/badge/livewire-v3.x-pink.svg?style=flat-square&logo=livewire)](https://livewire.laravel.com)
[![Midtrans](https://img.shields.io/badge/payment-Midtrans-orange.svg?style=flat-square)](https://midtrans.com)
[![License](https://img.shields.io/badge/license-Proprietary-darkgreen.svg?style=flat-square)](https://github.com/oddycosplay/website-rental-mobil)

Siliwangi Rental adalah platform berbasis web enterprise untuk manajemen dan penyewaan mobil premium. Dibangun menggunakan arsitektur modular yang kuat pada framework **Laravel 11**, **Livewire 3**, dan **TailwindCSS**, sistem ini dirancang untuk memberikan pengalaman transaksi sewa mobil yang instan, aman, dan mulus secara *real-time*.

---

## 🌟 Fitur Utama (Berdasarkan Roadmap Pengembangan)

Sistem ini telah melewati berbagai fase pengembangan strategis dengan fitur-fitur tingkat lanjut sebagai berikut:

### ⚡ Fitur Utama & Keunggulan Arsitektur

* **5-Step Checkout Wizard:** Antarmuka pemesanan interaktif berbasis Livewire yang mulus tanpa muat ulang halaman (*zero-reload*).
* **Gerbang Pembayaran Midtrans Snap:** Integrasi penuh transaksi pembayaran DP (*Down Payment*) atau Lunas menggunakan Midtrans dengan sinkronisasi status otomatis via *Webhook API*.
* **Dynamic Fee & Kalkulasi Pajak:** Sistem penghitungan otomatis untuk PPN 12%, Biaya Admin, Potongan Promo, serta Biaya Layanan Tambahan (*Pickup, Delivery, & Ojol Fee*).
* **Integrasi Sistem Notifikasi WhatsApp & In-App:** Pengiriman notifikasi pemesanan otomatis lewat WhatsApp (Antrean WhatsApp Queue) dan Lonceng Notifikasi *real-time* di panel atas aplikasi.
* **Fleet Health & Maintenance (Kolom JSON Terpusat):** Manajemen pemeliharaan kendaraan modern langsung di tabel `cars` menggunakan struktur data JSON untuk performa basis data maksimum dan pencatatan riwayat terperinci.
* **Ekspor Dokumen & Laporan PDF:** Pembuatan Invoice digital secara otomatis menjadi berkas PDF siap unduh menggunakan DomPDF.

---

## 🛠️ Stack Teknologi

Platform ini memanfaatkan teknologi modern kelas industri:

* **Core Backend:** PHP 8.2+ & Laravel 11.x
* **Frontend UI:** Livewire 3 (Reaktivitas tingkat tinggi), Alpine.js, TailwindCSS
* **Database Management:** MySQL (dengan relasi teroptimasi dan kolom JSON)
* **Payment Gateway:** Midtrans Snap API & Webhooks
* **Library Pendukung:** DomPDF (Invoice Generator), Maatwebsite Excel (Ekspor Laporan Keuangan)

---

## 📈 Roadmap Pengembangan & Status Rilis

Berikut adalah status pencapaian fase proyek berdasarkan berkas [roadmap.md](file:///c:/laragon/www/rental_project/documents/roadmap.md):

* [x] **PHASE 1 — Inisialisasi Proyek & Paket Dependensi**
* [x] **PHASE 2 — Desain Database & Struktur Migrasi Terpadu**
* [x] **PHASE 3 — Sistem Autentikasi Multi-Role & Middleware Spatie**
* [x] **PHASE 4 — Arsitektur Backend (Repository & Service Pattern)**
* [x] **PHASE 5 — Pembayaran & Gerbang Midtrans Snap**
* [x] **PHASE 6 — Sistem Notifikasi WhatsApp & In-App Bell**
* [x] **PHASE 7 — Antarmuka Pengguna Premium & Mobile-First Design**
* [x] **PHASE 8 — Pembuatan Invoice PDF Otomatis**
* [x] **PHASE 9 — Analitik Dashboard Admin & Ekspor Laporan Bulanan**
* [ ] **PHASE 10 — DevOps & Strategi Deployment (Sedang Berjalan)**
  * [x] Strategi DevOps & Dokumentasi Server Hardening
  * [ ] Dockerization (Dockerfile & docker-compose)
  * [ ] CI/CD Pipeline (GitHub Actions Workflow)
  * [ ] Konfigurasi Server Produksi & SSL

---

## 🚀 Petunjuk Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan repositori ini di komputer lokal Anda:

### **1. Klon Repositori**

```bash
git clone https://github.com/oddycosplay/website-rental-mobil.git
cd website-rental-mobil
```

### **2. Instal Dependensi Composer & NPM**

Instal library PHP menggunakan Composer (abaikan kebutuhan ekstensi jika sistem CLI lokal Anda belum mengaktifkannya):

```bash
composer install --ignore-platform-reqs
npm install
```

### **3. Salin & Konfigurasi Berkas Lingkungan (.env)**

Salin berkas contoh `.env` dan sesuaikan nama database, kredensial Midtrans, serta konfigurasi lainnya:

```bash
copy .env.example .env
```

Setelah menyalin, jalankan perintah untuk menghasilkan kunci aplikasi:

```bash
php artisan key:generate
```

### **4. Eksekusi Migrasi & Data Seeder**

Buat struktur tabel dan isi dengan data dummy bawaan (Pengguna, Armada Mobil, Merek, dll):

```bash
php artisan migrate:fresh --seed
```

### **5. Hubungkan Folder Penyimpanan**

Buat tautan simbolis ke folder publik agar berkas lampiran pemeliharaan/inspeksi dapat diakses secara publik:

```bash
php artisan storage:link
```

### **6. Jalankan Server Pengembangan**

Jalankan server Laravel lokal dan server kompilasi aset Vite secara bersamaan:

```bash
# Terminal 1 - Server Laravel
php artisan serve

# Terminal 2 - Compiler Aset Vite
npm run dev
```

Aplikasi kini dapat diakses melalui browser Anda di tautan: **`http://127.0.0.1:8000`**

---

## 🔒 Lisensi & Hak Cipta

Hak Cipta © 2026 **Siliwangi Rental**. Seluruh hak dilindungi undang-undang. Sistem ini dilisensikan secara proprietary untuk penggunaan operasional internal Siliwangi Rental.
