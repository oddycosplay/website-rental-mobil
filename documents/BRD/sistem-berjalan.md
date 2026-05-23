# Analisis Sistem Berjalan (Manual) — Siliwangi Rental

**Nama File:** `sistem-berjalan.md`  
**Lokasi:** `documents/BRD/`  
**Tujuan:** Mendokumentasikan prosedur bisnis yang ada secara mendetail berdasarkan pembagian peran (Swimlane).

---

## 1. Deskripsi Sistem Berjalan

Proses operasional Siliwangi Rental melibatkan koordinasi antara pelanggan, admin marketing, admin operasional, petugas lapangan, dan bagian keuangan. Saat ini, seluruh alur ini masih sangat bergantung pada pencatatan manual dan komunikasi via WhatsApp, yang memiliki risiko human error dan keterlambatan data yang tinggi.

---

## 2. Diagram Alur Sistem Berjalan (Swimlane Flowchart)

```mermaid
graph TD
    A[Pelanggan] -->|WA/Telepon| B[Tanya Ketersediaan Mobil]
    B --> C[Admin Cek Buku/Spreadsheet Manual]
    C -->|Tersedia| D[Admin Info Harga & Syarat]
    C -->|Penuh| E[pilih mobil lain]
    D --> F[Pelanggan Kirim data diri & Foto KTP/SIM/KK via WA]
    F --> G[Admin Cek Kelengkapan & Keaslian Data via data Base]
    G -->|vertifikasi| H[Admin lanjutkan proses]
    G -->|tidak verifikasi| I[Admin Tolak Data / blacklist]
    H --> J[Admin Catat Jadwal Manual]
    H --> J[Admin kirim persyaratan peminjaman dan total harga yang harus di bayar]
    K --> J[Pelanggan Transfer Manual ke Rekening]
    J --> L[Admin Cek Mutasi Rekening Manual]
    L -->|Valid| M[Admin Konfirmasi via WA lanjutkan proses]
    L -->|tidak Valid| M[Customer ganti cara pembayaran /cek mutasi rekening tidak ada]
    L --> M[Admin mengecek ketersediaan mobil dan mengirimkan info jadwal]
    M --> L[Admin menginfokan mobil dan driver ke customer]
    L --> M[Proses Sewa & Ambil Mobil]
    M --> N[Pengembalian & Cek Fisik Manual]
    N --> O[Hitung Denda Jika Ada - Manual]

    A[Pelanggan] -->|Akses Website| B[Lihat Katalog & Cek Ketersediaan Otomatis]
    B --> C[Lakukan Booking via 5-Step Checkout]
    C --> D[Upload Dokumen Digital & Validasi Real-time]
    D --> E[Pilih Metode Bayar DP/Lunas - Midtrans]
    E --> F{Pembayaran Valid?}
    F -->|Ya| G[Sistem Update Status PAID & Booking CONFIRMED]
    F -->|Tidak/Pending| H[Booking Status PENDING/EXPIRED]
    G --> I[Kirim Notifikasi via Queue WA & In-App Bell]
    I --> J[Admin Approval & Assign Driver via Dashboard]
    J --> K[Sistem Generate Invoice PDF & Hitung Durasi Otomatis]
    K --> L[Proses Sewa & Monitoring Status On-Rent]
    L --> M[Pengembalian & Input Hasil Inspeksi]
    M --> N[Sistem Hitung Denda & Biaya Tambahan Otomatis]
    N --> O[Pelunasan & Status COMPLETED]
```

---

## 3. Identifikasi Masalah Utama

| No  | Kategori               | Kendala Berdasarkan Alur                                                                         |
| :-- | :--------------------- | :----------------------------------------------------------------------------------------------- |
| 1   | **Koordinasi**         | Perpindahan data dari Marketing ke Operasional sering terlambat (manual).                        |
| 2   | **Validasi**           | Pengecekan mutasi bank oleh Keuangan sering terhambat jika transaksi terjadi di luar jam kantor. |
| 3   | **Fisik & Inventaris** | Form inspeksi fisik kertas sering hilang atau sulit dibaca saat proses pengembalian.             |
| 4   | **Transparansi**       | Customer tidak memiliki akses langsung untuk melihat status booking-nya secara mandiri.          |
| 5   | **Risiko Keamanan**    | Pengecekan data KTP/SIM hanya bersifat visual, rawan dokumen palsu.                              |

---

Versi: 1.2.0 | Tanggal: 2026-05-14
