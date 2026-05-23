# Analisis Sistem Usulan (Digital) — Siliwangi Rental

**Nama File:** `sistem-usulan.md`  
**Lokasi:** `documents/BRD/`  
**Tujuan:** Mendokumentasikan alur kerja digital yang efisien dan terotomasi.

---

## 1. Deskripsi Sistem Usulan

Sistem digital Siliwangi Rental mengintegrasikan katalog real-time, gateway pembayaran, dan manajemen operasional dalam satu platform. Mayoritas koordinasi data yang sebelumnya manual kini ditangani secara otomatis oleh sistem melalui notifikasi real-time dan antrean pesan.

---

## 2. Diagram Alur Sistem Usulan (Swimlane Flowchart)

```mermaid
graph TB
    subgraph CUSTOMER [Pelanggan]
        C1([Mulai]) --> C2[Lihat Katalog & Cek Ketersediaan]
        C2 --> C3[Isi Form Booking & Upload Dokumen]
        C4[Pilih Metode Bayar - Midtrans] --> C5{Bayar?}
        C5 -- Ya --> C6[Terima Notifikasi & Invoice PDF]
        C7[Ambil Unit] --> C8[Sewa Selesai & Kembalikan Unit]
    end

    subgraph SYSTEM [Sistem / Automation]
        S1[Cek Ketersediaan Unit Otomatis] --> S2[Validasi Dokumen Digital]
        S2 --> C4
        C5 -- Webhook --> S3[Update Status: PAID & Terbit Invoice]
        S3 --> S4[Kirim Notifikasi WA & In-App Bell]
        S4 --> C6
        S5[Kalkulasi Denda & Biaya Otomatis] --> S6[Generate Laporan Keuangan Real-time]
    end

    subgraph ADMIN [Admin Dashboard]
        A1[Terima Notifikasi Booking Baru] --> A2[Review Dokumen & Approval]
        A2 --> A3[Assign Driver & Cabang]
        A3 --> A4[Monitor Status Unit: On-Rent]
    end

    subgraph PETUGAS [Petugas Lapangan]
        P1[Digital Inspection - Ambil] --> P2[Input Kondisi Unit via Mobile]
        P2 --> C7
        C8 --> P3[Digital Inspection - Kembali]
        P3 --> P4[Input Kerusakan/BBM via Mobile]
        P4 --> S5
    end

    %% Jalur Koordinasi
    C3 --> S1
    A2 --> S3
    S4 --> A1
    A4 --> P1
    P4 --> S6
```

---

## 3. Keunggulan Dibandingkan Sistem Manual

| Fitur | Dampak Positif |
|:---|:---|
| **Otomasi Bayar** | Keuangan tidak perlu cek mutasi manual; status update instan via Midtrans. |
| **Pusat Data** | Semua dokumen (KTP/SIM) tersimpan rapi dan aman di database, bukan di chat WA. |
| **Real-time Monitoring** | Admin bisa melihat posisi dan status semua unit (Available, Booked, On-Rent) dalam satu layar. |
| **Notifikasi Cerdas** | Antrean WhatsApp memastikan pesan tetap terkirim meskipun server sedang sibuk. |

---

Versi: 1.2.0 | Tanggal: 2026-05-14
