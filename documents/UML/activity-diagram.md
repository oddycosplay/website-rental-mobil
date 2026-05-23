# Activity Diagram — Siliwangi Rental

**Nama File:** `activity-diagram.md`  
**Lokasi:** `documents/UML/`  
**Tujuan:** Dokumentasi activity diagram alur utama sistem Siliwangi Rental.

---

## 1. Activity Diagram — Booking Flow

```mermaid
flowchart TD
    Start([Mulai]) --> A[Customer buka Katalog Mobil]
    A --> B[Pilih dan lihat Detail Kendaraan]
    B --> C{Login?}
    C -->|Ya| D[Isi Form Booking Step 1: Tanggal & Jenis Sewa]
    C -->|Tidak| E[Pilih: Login / Daftar / Lanjut sebagai Guest]
    E --> D
    D --> F[Step 2: Data Penyewa]
    F --> G[Step 3: Upload KTP & SIM]
    G --> H[Step 4: Input Kode Promo Opsional]
    H --> I[Step 5: Konfirmasi & Submit]
    I --> J[(Simpan Booking - Status: PENDING)]
    J --> K[Redirect ke Halaman Pembayaran DP]
    K --> L[Pilih Metode Bayar di Midtrans Snap]
    L --> M{Bayar DP?}
    M -->|Berhasil| N[(Update: Payment PARTIAL, Booking PAID)]
    M -->|Gagal/Expire| O[(Update: Payment FAILED/EXPIRED)]
    O --> End1([Selesai - Booking Aktif Selama 24 Jam])
    N --> P[Admin Menerima Notifikasi]
    P --> Q{Admin Review Booking}
    Q -->|Approve| R[(Booking CONFIRMED, Kendaraan BOOKED)]
    Q -->|Reject| S[(Booking CANCELLED, Trigger Refund)]
    R --> T[Hari Sewa Tiba]
    T --> U[(Update: On Rent, Kendaraan ON_RENT)]
    U --> V[Masa Sewa Berlangsung]
    V --> W[Customer Kembalikan Kendaraan]
    W --> X[Admin Proses Return]
    X --> Y{Ada Keterlambatan?}
    Y -->|Ya| Z[Buat Tagihan Denda]
    Z --> AA[Customer Bayar Denda]
    AA --> AB[(Update: Booking COMPLETED)]
    Y -->|Tidak| AB
    AB --> AC[Generate Invoice Final PDF]
    AC --> End2([Selesai - Booking COMPLETED])
```

---

## 2. Activity Diagram — Payment Flow

```mermaid
flowchart TD
    Start([Mulai]) --> A[Customer akses halaman Payment]
    A --> B[Sistem generate Midtrans Snap Token]
    B --> C[Tampilkan Midtrans UI kepada Customer]
    C --> D{Customer Bayar?}
    D -->|Ya| E[Midtrans Proses Transaksi]
    D -->|Tidak / Tutup| F[Status: PENDING - tunggu 24 jam]
    E --> G{Status Midtrans?}
    G -->|settlement| H[Webhook: POST /midtrans/callback]
    G -->|pending| I[Status: PENDING - menunggu]
    G -->|deny/expire/cancel| J[Status: FAILED/EXPIRED]
    H --> K[Validasi Signature Key]
    K -->|Valid| L[(Update Payment Status)]
    K -->|Invalid| M[Log Error - Abaikan]
    L --> N[(Update Booking Status)]
    N --> O[Kirim Notifikasi Customer + Admin]
    O --> End([Selesai])
```

---

## 3. Activity Diagram — Admin Approval

```mermaid
flowchart TD
    Start([Mulai]) --> A[Admin Buka Booking Management]
    A --> B[Lihat Booking Status PAID]
    B --> C[Klik Detail Booking]
    C --> D[Cek Dokumen KTP & SIM]
    D --> E{Dokumen Valid?}
    E -->|Tidak| F[Reject dengan Alasan]
    F --> G[(Booking CANCELLED)]
    G --> H[Notifikasi Customer: Booking Ditolak]
    H --> End1([Selesai])
    E -->|Ya| I[Cek Ketersediaan Kendaraan]
    I --> J{Kendaraan Available?}
    J -->|Tidak| K[Pilih Kendaraan Alternatif / Reject]
    K --> F
    J -->|Ya| L{Perlu Driver?}
    L -->|Ya| M[Assign Driver Tersedia]
    L -->|Tidak| N[Approve Booking]
    M --> N
    N --> O[(Booking CONFIRMED)]
    O --> P[(Kendaraan Status: BOOKED)]
    P --> Q[Notifikasi Customer: Booking Dikonfirmasi]
    Q --> End2([Selesai])
```

---

Versi: 1.0.0 | Tanggal: 2026-05-14
