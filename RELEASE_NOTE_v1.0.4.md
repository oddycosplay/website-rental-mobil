# Catatan Rilis (Release Notes) - Siliwangi Rental v1.0.4
**Tanggal Rilis:** 20 Juni 2026  
**Versi:** v1.0.4  
**Tipe Rilis:** Maintenance & Quality Assurance (Bug Fixes)

---

## Deskripsi Singkat
Rilis **v1.0.4** berfokus pada peningkatan kualitas kode (code quality), perbaikan analisis statis (static analysis & linter warnings), peningkatan fleksibilitas sistem upload gambar (image upload), serta optimalisasi komponen pemesanan dan manajemen profil pengguna pada sistem Siliwangi Rental.

Seluruh unit testing telah dijalankan dan dipastikan lulus 100% untuk menjaga stabilitas sistem sebelum dideploy ke server produksi.

---

## Daftar Perubahan & Perbaikan

### 1. ⚙️ Manajemen Gambar & Modul Unggahan (`ImageUploadService`)
* **Dynamic Loading untuk Imagick:** Memodifikasi mekanisme penanganan gambar pada `ImageUploadService`. Instansiasi kelas `Imagick` kini dilakukan secara dinamis (`class_exists`) dengan blok tangkapan `Throwable` umum.
* **Tujuan:** Menghilangkan peringatan kesalahan analisis statis (*Undefined type 'Imagick'*) ketika ekstensi php-imagick tidak terinstal secara lokal di perangkat pengembang, tanpa mengorbankan fungsionalitas di server hosting produksi yang memiliki ekstensi tersebut.

### 2. 🖥️ Antarmuka & Logika Komponen Livewire
* **Profil Pelanggan (`CustomerProfileEditor`):** 
  * Menggantikan penggunaan metode `$user->update()` dengan penulisan penugasan properti langsung (direct assignment) diikuti pemanggilan `$user->save()`.
  * Langkah ini membersihkan peringatan parameter linter dan memastikan pembaruan data pengguna (Nama, NIK, Telepon, Alamat) lebih eksplisit dan aman.
* **Katalog Mobil (`CarCatalog`):**
  * Menambahkan parameter binding kosong `[]` pada kueri `orderByRaw()` untuk penyortiran status ketersediaan mobil demi memenuhi validasi tanda tangan fungsi linter secara ketat.

### 3. 📂 Halaman Administrasi Filament (`CreateBooking`)
* **Query Builder Eksplisit:** Mengubah pemanggilan model instan `Car::find()` menjadi `Car::query()->find()` pada penentuan harga otomatis saat pembuatan transaksi sewa baru oleh Admin, mengeliminasi peringatan jumlah parameter koding.

### 4. 🧪 Pengujian Unit & Otomatisasi (Testing & Scratch Utilities)
* **Kueri Pengujian Stabil (`BookingPaymentTest`):**
  * Mengoptimalkan metode pencarian transaksi menggunakan `Booking::query()->where()` dan `Payment::query()->whereIn()`.
  * Mengganti metode pembaruan status mobil dari kueri massal menjadi assign direct property + `save()`.
* **Script Utilitas Lokal:**
  * Menambahkan berkas `clear_admin_docs.php` di dalam direktori `scratch` untuk mempermudah administrator membersihkan dokumen pengujian Super Admin (KTP, SIM, dsb.) secara aman tanpa melalui antarmuka web.
  * Menambahkan utilitas `show_users.php` untuk kebutuhan kueri cepat daftar user lokal.

---

## Status Pengujian (QA Status)
Seluruh rangkaian uji otomatis (test suite) PHPUnit telah dijalankan dengan hasil akhir sebagai berikut:
* **Total Tes:** 133 Skenario Uji
* **Assertions:** 266 Validasi
* **Hasil:** **100% Lolos (OK)** (dengan 7 skenario *skipped* yang telah ditentukan sebelumnya).

---

## Panduan Migrasi & Deployment ke Server
Untuk memperbarui aplikasi di server ke versi `v1.0.4`:

1. Tarik pembaruan kode terbaru dari repositori Git:
   ```bash
   git pull origin main
   ```
2. Bersihkan cache konfigurasi dan rute Laravel untuk menerapkan pembaruan Filament & Livewire:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
3. Lakukan pengujian cepat pada server staging (opsional namun direkomendasikan):
   ```bash
   vendor/bin/phpunit
   ```
