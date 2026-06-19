# Analisis Sistem Berjalan — Siliwangi Rental

**Nama File:** `sistem-berjalan.md`
**Lokasi:** `documents/BRD/`
**Tujuan:** Mendokumentasikan prosedur bisnis yang sedang berjalan secara mendetail, mencakup sistem manual lama dan sistem digital yang telah diimplementasikan.

---

## 1. Deskripsi Umum

Siliwangi Rental mengoperasikan bisnis penyewaan kendaraan berbasis di Bandung. Proses operasional melibatkan koordinasi lintas aktor: **Pelanggan**, **Administrator**, **Tim Operasional (Auditor Lapangan)**, **Driver**, dan **Sistem Aplikasi Laravel**.

Sejak versi **v1.0.0**, sistem telah bertransisi dari alur manual berbasis WhatsApp ke platform digital terintegrasi. Per **v1.0.2**, seluruh alur inti (booking, pembayaran, inspeksi, pemeliharaan, dan pelaporan keuangan) telah berjalan secara otomatis dalam satu ekosistem.

---

## 2. Alur Sistem Manual (Sebelum Digitalisasi — Referensi Historis)

Proses ini **sudah tidak digunakan**, namun didokumentasikan sebagai acuan komparasi dan audit bisnis.

```mermaid
graph TD
    A([Pelanggan]) -->|WA/Telepon| B[Tanya Ketersediaan Mobil]
    B --> C[Admin Cek Buku / Spreadsheet Manual]
    C -->|Tersedia| D[Admin Info Harga & Syarat]
    C -->|Penuh| E[Pelanggan Pilih Mobil Lain]
    D --> F[Pelanggan Kirim KTP/SIM/KK via WA]
    F --> G[Admin Cek Kelengkapan Data Manual]
    G -->|Terverifikasi| H[Admin Catat Jadwal di Buku]
    G -->|Tidak Valid| I[Tolak / Blacklist Pelanggan]
    H --> J[Admin Kirim Total Harga via WA]
    J --> K[Pelanggan Transfer Manual ke Rekening]
    K --> L[Admin Cek Mutasi Rekening Manual]
    L -->|Valid| M[Admin Konfirmasi via WA]
    L -->|Tidak Valid| N[Customer Hubungi Ulang Admin]
    M --> O[Proses Serah Terima & Ambil Mobil]
    O --> P[Pengembalian & Cek Fisik Manual - Kertas]
    P --> Q[Hitung Denda Manual jika Ada]
    Q --> R([Selesai])
```

### Identifikasi Masalah Sistem Manual

| No  | Kategori                | Kendala                                                                 |
| :-- | :---------------------- | :---------------------------------------------------------------------- |
| 1   | **Koordinasi**          | Perpindahan data antar divisi terlambat karena bergantung pada pesan WA |
| 2   | **Validasi Pembayaran** | Cek mutasi bank manual, rentan keterlambatan dan human error            |
| 3   | **Arsip Inspeksi**      | Form fisik kertas sering hilang, rusak, atau tidak terbaca              |
| 4   | **Transparansi**        | Customer tidak bisa memantau status booking secara mandiri              |
| 5   | **Keamanan Dokumen**    | Pengecekan KTP/SIM hanya visual, rawan pemalsuan                        |
| 6   | **Pelaporan**           | Laporan keuangan dibuat manual di Excel, tidak real-time                |

---

## 3. Alur Sistem Digital yang Saat Ini Berjalan (v1.0.2)

### 3.1 Alur Booking & Pembayaran (Customer → Sistem → Admin)

```mermaid
graph TD
    C1([Pelanggan Akses Website]) --> C2[Lihat Katalog & Cek Ketersediaan Real-time]
    C2 --> C3[Isi Form Booking — 5-Step Checkout Wizard]
    C3 --> C4[Upload Dokumen Digital: KTP, SIM, Selfie]
    C4 --> C5[Pilih Metode Sewa: Lepas Kunci / Dengan Sopir]
    C5 --> C6[Pilih Pembayaran: DP atau Lunas — Midtrans]
    C6 --> C7{Gateway Pembayaran}
    C7 -->|PAID| C8[Sistem Update Status: Booking CONFIRMED]
    C7 -->|Gagal/Pending| C9[Booking Status PENDING / EXPIRED]
    C8 --> C10[Sistem Kirim Invoice PDF & Notifikasi WA Otomatis]
    C10 --> C11[Admin Terima Notifikasi Dashboard]
    C11 --> C12[Admin Review & Assign Driver jika Dengan Sopir]
    C12 --> C13[Booking Status: READY FOR CHECKOUT]
```

### 3.2 Alur Inspeksi Check-out (Serah Terima Kendaraan)

```mermaid
graph TD
    A1([Admin Buka Vehicle Inspection Hub]) --> A2[Pilih Booking: Status CONFIRMED]
    A2 --> A3[Tim Operasional Audit Fisik Kendaraan]
    A3 --> A4[Input Form Check-out: Odometer Awal, Kondisi Fisik, Level BBM]
    A4 --> A5[Sistem Validasi & Simpan Log Inspeksi]
    A5 --> A6[Sistem Update Status Mobil: available -> rented]
    A6 --> A7[Sistem Update Status Booking: confirmed -> ongoing]
    A7 --> A8{Metode Sewa?}
    A8 -->|Lepas Kunci| A9[Kunci Diserahkan ke Customer]
    A8 -->|Dengan Sopir| A10[Driver Mengambil Unit & Berangkat]
    A9 --> A11([Kendaraan Aktif Beroperasi])
    A10 --> A11
```

### 3.3 Alur Inspeksi Check-in (Pengembalian Kendaraan)

```mermaid
graph TD
    B1([Customer / Driver Tiba di Pool]) --> B2[Tim Operasional Audit Fisik Pengembalian]
    B2 --> B3[Input Form Check-in: Odometer Akhir & Checklist Kondisi]
    B3 --> B4{Sistem Validasi Odometer}
    B4 -->|Odometer Akhir lebih rendah| B5[Error Validasi — Input Ulang]
    B4 -->|Valid| B6{Ada Kerusakan Berat?}
    B6 -->|Ya — dent/damaged| B7[Sistem Set Status Mobil: rented -> maintenance]
    B6 -->|Tidak — ok/scratch| B8[Sistem Set Status Mobil: rented -> available]
    B7 --> B9[Booking Status: ongoing -> completed]
    B8 --> B9
    B9 --> B10[Admin Finalisasi Tagihan & Kembalikan Deposit]
    B10 --> B11([Transaksi Selesai])
```

### 3.4 Alur Car Health & Maintenance

```mermaid
graph TD
    M1([Admin Buka Car Health Dashboard]) --> M2{Sumber Maintenance?}
    M2 -->|Dari Inspeksi Check-in| M3[Otomatis: Status Mobil maintenance]
    M2 -->|Manual Admin| M4[Admin Buat Catatan Perawatan Baru]
    M3 --> M5[Input Detail: Biaya, Tanggal, Bengkel, Keterangan]
    M4 --> M5
    M5 --> M6[Sistem Catat Riwayat Perawatan per Unit]
    M6 --> M7[Dashboard Tampilkan Grafik Biaya & Frekuensi Servis]
    M7 --> M8{Perawatan Selesai?}
    M8 -->|Ya| M9[Admin Update Status Mobil: maintenance -> available]
    M8 -->|Belum| M10[Status Tetap maintenance]
```

### 3.5 Alur Keuangan & Pembayaran

```mermaid
graph TD
    F1([Booking CONFIRMED]) --> F2[Sistem Catat Pembayaran DP / Lunas via Midtrans]
    F2 --> F3[Admin Monitor Financial Records Dashboard]
    F3 --> F4[Dashboard Tampilkan Grafik Analitik: Pendapatan Harian & Bulanan]
    F4 --> F5[Admin Export Laporan Keuangan — PDF / Excel]
    F5 --> F6[Rekap P&L per Periode — Otomatis]
```

---

## 4. Peta Aktor & Tanggung Jawab (RACI Summary)

| Aktivitas                      | Customer | Admin | Tim Ops | Driver |    Sistem    |
| :----------------------------- | :------: | :---: | :-----: | :----: | :----------: |
| Booking kendaraan              |  **R**   |   I   |    —    |   —    |      A       |
| Upload dokumen identitas       |  **R**   |   I   |    —    |   —    |      A       |
| Konfirmasi & assign driver     |    —     | **R** |    —    |   I    |      A       |
| Audit inspeksi check-out       |    —     |   I   |  **R**  |   —    |      A       |
| Mengemudikan kendaraan (sopir) |    —     |   —   |    —    | **R**  |      I       |
| Audit inspeksi check-in        |    I     |   I   |  **R**  |   —    |      A       |
| Update status kendaraan        |    —     |   —   |    —    |   —    | **R** (auto) |
| Pencatatan maintenance         |    —     | **R** |    —    |   —    |      A       |
| Finalisasi tagihan             |    —     | **R** |    —    |   —    |      A       |
| Laporan keuangan               |    —     | **R** |    —    |   —    |      A       |

> **R** = Responsible (Pelaksana) · **A** = Accountable (Penanggung Jawab Sistem) · **I** = Informed

---

## 5. Status Kendaraan & Transisi (State Machine)

```mermaid
stateDiagram-v2
    [*] --> available : Unit Didaftarkan
    available --> rented : Check-out Berhasil
    rented --> available : Check-in — Kondisi Prima
    rented --> maintenance : Check-in — Terdeteksi Kerusakan
    maintenance --> available : Admin Konfirmasi Selesai Servis
    available --> [*] : Unit Dinonaktifkan
```

---

## 6. Modul Sistem yang Aktif (v1.0.2)

| Modul                         |     Status     | Keterangan                                 |
| :---------------------------- | :------------: | :----------------------------------------- |
| Katalog & Booking Public      |    ✅ Aktif    | Checkout 5-langkah dengan upload dokumen   |
| Gateway Pembayaran Midtrans   |    ✅ Aktif    | DP dan pelunasan otomatis                  |
| Admin Dashboard (Filament)    |    ✅ Aktif    | Manajemen booking, pelanggan, armada       |
| Vehicle Inspection Hub        |    ✅ Aktif    | Check-out & Check-in dengan audit fisik    |
| Car Health & Maintenance      |    ✅ Aktif    | Grafik biaya & riwayat perawatan per unit  |
| Financial Records & Analytics |    ✅ Aktif    | Grafik pendapatan harian dan bulanan       |
| Live GPS Tracking             | 🚧 Placeholder | Belum terintegrasi dengan perangkat GPS    |
| WhatsApp Notification         | 🚧 Placeholder | Antrean siap, integrasi API WA belum aktif |
| Loyalty Program               |   🔜 Planned   | Direncanakan pada roadmap v1.1.x           |

---

## 7. Referensi Dokumen Terkait

| Dokumen                     | Lokasi                                         |
| :-------------------------- | :--------------------------------------------- |
| Sistem Usulan Digital       | `documents/BRD/sistem-usulan.md`               |
| Business Requirement Design | `documents/BRD/business-requirement-design.md` |
| Flowchart Inspeksi Draw.io  | `documents/flowchart_inspeksi.drawio`          |
| Diagram UML Sistem Berjalan | `documents/UML/sistem berjalan manual.drawio`  |
| ERD Database                | `documents/DATABASE/erd_siliwangi.drawio`      |
| Progress & Changelog        | `documents/BRD/progress.md`                    |

---

**Versi:** 2.0.0 | **Tanggal:** 2026-05-29 | **Update oleh:** Siliwangi Dev Team
