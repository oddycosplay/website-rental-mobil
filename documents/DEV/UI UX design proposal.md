# Proposal Desain UX/UI Siliwangi Rental (Front-End & Back-End)

Dokumen ini menyajikan perancangan **User Experience (UX)** dan **User Interface (UI)** premium untuk sistem **Siliwangi Rental** berbasis **Laravel 12 / Livewire 3 / Filament v4**. Desain ini dirancang khusus untuk memberikan pengalaman pengguna yang sangat modern, intuitif, berkelas, dan selaras dengan identitas brand "Siliwangi Rental" yang andal dan gagah.

---

## 1. Filosofi Desain & Palet Warna

Sistem ini mengadopsi pendekatan **Glassmorphic Neo-SaaS** dengan nuansa gelap/slate modern untuk antarmuka publik pelanggan (agar terkesan mewah dan eksklusif) serta antarmuka hibrida terang/gelap yang bersih untuk panel administrasi.

### Token Warna (Color Tokens)

- **Primary (Slate-Blue):** `#1e293b` dan `#0f172a` — Warna latar belakang utama dan kontainer gelap yang kokoh.
- **Accent (Teal & Cyan):** `#0d9488` dan `#06b6d4` — Merepresentasikan modernitas, kecepatan, dan teknologi (pelacakan GPS, status _available_).
- **Success (Emerald):** `#10b981` — Untuk status pembayaran _settlement_, persetujuan dokumen, dan sewa selesai.
- **Warning/Alert (Rose/Amber):** `#f43f5e` dan `#f59e0b` — Untuk tanda keterlambatan, mobil dalam masa _maintenance_, atau transaksi batal.

### Tipografi

- Menggunakan font modern **Outfit** (untuk heading halaman agar terkesan dinamis dan futuristik) dipadukan dengan **Inter** (untuk body teks guna memaksimalkan keterbacaan data).

---

## 2. Antarmuka Pengguna Front-End (Catalog & Booking Customer)

Halaman ini berfokus pada kemudahan konversi pelanggan dalam mencari dan memesan kendaraan.

### Elemen Kunci Halaman Katalog

1. **Filter Cepat (Quick Search Bar):** Diletakkan di atas lipatan layar (_above-the-fold_) dengan inputan: Tanggal Ambil, Tanggal Kembali, Lokasi Toko, dan Pilihan Layanan (Lepas Kunci / Dengan Supir).
2. **Kartu Mobil Glassmorphism (Glassmorphic Car Cards):**
    - Gambar unit beresolusi tinggi dengan efek bayangan halus (_soft shadow_).
    - Badge kategori jenis sewa ("Pribadi" / "Perusahaan").
    - Spesifikasi cepat: Kapasitas Kursi, Jenis Transmisi (Manual/Matic), dan Fitur Utama (AC, GPS, Asuransi).
    - Tombol aksi ganda: **Detail** (untuk info lengkap & ulasan) dan **Booking Sekarang** (untuk langsung masuk ke checkout).

### Visualisasi Desain Katalog Pelanggan (Front-End Mockup)

![Mockup Tampilan Katalog Mobil Pelanggan (Front-End)](/C:/Users/oddycosplay/.gemini/antigravity/brain/69330417-ef7c-4e0e-9ac4-93bf6ceae9b4/customer_catalog_ui_1780051384123.png)

---

## 3. Antarmuka Pengguna Back-End (Admin Dashboard Filament)

Panel admin dirancang dengan struktur data padat (_high data-density_), memungkinkan staf toko memantau dan mengelola armada kendaraan secara real-time.

### Elemen Kunci Panel Admin

1. **Kartu KPI Ringkasan Bisnis (SaaS Dashboard Cards):**
    - _Total Inspeksi Aktif:_ Menggunakan ikon check-shield.
    - _Ketersediaan Armada:_ Menampilkan rasio mobil siap pakai (`available`), sedang disewa (`rented`), dan di bengkel (`maintenance`).
    - _Grafik Pendapatan Bulanan:_ Line-chart interaktif yang terhubung langsung dengan tabel transaksi pembayaran Midtrans.
2. **Tabel Transaksi & Inspeksi Terintegrasi:**
    - Daftar antrean inspeksi aktif berdasarkan status sewa (`confirmed` ➔ butuh Check-out, `ongoing` ➔ butuh Check-in).
    - Badge status yang dinamis dan kontras untuk kemudahan pemantauan visual.
3. **Pelacak Armada GPS Terintegrasi:**
    - Widget peta interaktif mini yang menunjukkan koordinat latitude dan longitude mobil yang sedang berstatus _ongoing_ di jalan.

### Visualisasi Desain Panel Admin (Back-End Mockup)

![Mockup Tampilan Admin Dashboard Siliwangi Rental (Back-End)](/C:/Users/oddycosplay/.gemini/antigravity/brain/69330417-ef7c-4e0e-9ac4-93bf6ceae9b4/admin_dashboard_ui_1780051403068.png)

---

## 4. Keunggulan Arsitektur UX/UI Sistem Ini

> [!TIP]
>
> - **Integrasi Penuh:** Desain visual di atas diselaraskan 100% dengan class diagram, database migration, dan controller backend Laravel Anda.
> - **Bebas Placeholder Kosong:** Semua data pada mockup menyajikan visualisasi riil dari entitas kendaraan (SUV Premium, Sedan Mewah) dan metrik keuangan riil.
> - **Responsivitas Maksimal:** Layout front-end menggunakan grid CSS flexbox yang otomatis beradaptasi dari resolusi smartphone hingga desktop ultra-wide.
