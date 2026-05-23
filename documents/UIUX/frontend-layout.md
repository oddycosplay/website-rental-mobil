# Frontend Layout — Siliwangi Rental

**Nama File:** `frontend-layout.md`  
**Lokasi:** `documents/UIUX/`  
**Tujuan:** Dokumentasi layout halaman frontend customer-facing.

---

## 1. Layout Struktur

### Master Layout (`layouts/app.blade.php`)

```
┌──────────────────────────────────────────┐
│              NAVIGATION BAR              │
│  Logo | Menu | Login/Profil | Booking    │
├──────────────────────────────────────────┤
│                                          │
│              PAGE CONTENT                │
│           (Slot konten halaman)          │
│                                          │
├──────────────────────────────────────────┤
│              FOOTER                      │
│  Logo | Links | Kontak | Copyright       │
└──────────────────────────────────────────┘
```

### Navbar Items

```
Mobile: hamburger menu → slide drawer
Desktop: horizontal nav

Items:
  - Beranda (/)
  - Catalog (/catalog)
  - Tentang (/tentang)
  - FAQ (/faq)
  - Kontak (/contact)
  - [Guest] Login / Daftar
  - [Auth] Profil Dropdown → Dashboard, Riwayat, Logout
```

---

## 2. Homepage Layout

```
┌─────────────────────────────────────────────────┐
│                  HERO SECTION                   │
│  "Sewa Mobil Mudah, Aman & Terpercaya"          │
│  [Tombol: Lihat Armada] [Tombol: Booking]       │
│  Background: Gambar mobil premium               │
├─────────────────────────────────────────────────┤
│              ARMADA UNGGULAN                    │
│  Grid 4 car cards (featured)                    │
│  [Lihat Semua →]                                │
├─────────────────────────────────────────────────┤
│              TIPE KENDARAAN                     │
│  Icon grid: SUV | MPV | Sedan | Hatchback       │
├─────────────────────────────────────────────────┤
│              KEUNGGULAN KAMI                    │
│  4 Cards: Armada Premium | Driver Profesional   │
│           Pembayaran Aman | Pelayanan 24/7      │
├─────────────────────────────────────────────────┤
│              CARA PEMESANAN                     │
│  Step 1: Pilih Mobil                            │
│  Step 2: Isi Form & Upload Dokumen              │
│  Step 3: Bayar DP                               │
│  Step 4: Nikmati Perjalanan                     │
├─────────────────────────────────────────────────┤
│                   FOOTER                        │
└─────────────────────────────────────────────────┘
```

---

## 3. Catalog Layout

```
┌─────────────────────────────────────────────────┐
│  HEADER: "Catalog Armada" + Breadcrumb          │
├────────────┬────────────────────────────────────┤
│  FILTER    │  SEARCH BAR + SORT DROPDOWN        │
│  PANEL     ├────────────────────────────────────┤
│            │  CAR GRID                          │
│  - Tipe    │  [Card] [Card] [Card]              │
│  - Brand   │  [Card] [Card] [Card]              │
│  - Trans.  │  [Card] [Card] [Card]              │
│  - Harga   │                                    │
│  - Kategori├────────────────────────────────────┤
│            │  PAGINATION                        │
└────────────┴────────────────────────────────────┘
```

---

## 4. Car Detail Layout

```
┌─────────────────────────────────────────────────┐
│  BREADCRUMB: Beranda > Catalog > Nama Mobil     │
├────────────────────┬────────────────────────────┤
│  IMAGE GALLERY     │  BOOKING FORM              │
│                    │  - Tanggal Mulai           │
│  [Foto Utama]      │  - Tanggal Selesai         │
│  [Thumbnail 1-4]   │  - Dengan Driver?          │
│                    │  - Harga: Rp XXX/hari      │
│                    │  - Total: Rp XXX           │
│                    │  [Tombol: Booking Sekarang]│
├────────────────────┴────────────────────────────┤
│  INFORMASI KENDARAAN                            │
│  Transmisi | Kapasitas | Bahan Bakar | Tahun    │
├─────────────────────────────────────────────────┤
│  DESKRIPSI & FASILITAS                          │
│  [AC] [GPS] [Musik] [USB] dst...                │
├─────────────────────────────────────────────────┤
│  KENDARAAN SERUPA                               │
└─────────────────────────────────────────────────┘
```

---

## 5. Checkout Wizard Layout

```text
Progress Bar: [1]──[2]──[3]──[4]──[5]
Step: Config | Data | Docs | Driver | Pay

Step 1: Konfigurasi & Rincian
┌──────────────────────────────────────────────┐
│  Tanggal Sewa [Start] s/d [End]              │
│  (!) Catatan: 1 Hari = 24 Jam                │
│  Jenis: [Jemput] [Diantar] | Lokasi: [Text]  │
│  Kategori: [Wisata] [Bisnis] [Personal]      │
│  ────────────────────────────────────────────│
│  RINCIAN HARGA (Sidebar/Bottom)              │
│  - Dasar: Rp XXX | Layanan: Rp XXX           │
│  - Promo: -Rp XX | Total: Rp XXX (Inc Tax)   │
│                             [Lanjutkan →]    │
└──────────────────────────────────────────────┘

Step 2: Data Pemesan
┌──────────────────────────────────────────────┐
│  Nama Lengkap: [Input]                       │
│  Alamat Domisili: [Textarea]                 │
│  No. HP (WhatsApp): [Input]                  │
│  Email: [Input]                              │
│                             [Lanjutkan →]    │
└──────────────────────────────────────────────┘

Step 3: Upload Dokumen Legal (Grid)
┌──────────────────────┬───────────────────────┐
│  [Upload KTP]        │  [Upload SIM]         │
├──────────────────────┼───────────────────────┤
│  [Upload KK]         │  [Upload NPWP]        │
├──────────────────────┼───────────────────────┤
│  [Upload K. Pelajar] │  [Upload K. Mhs]      │
└──────────────────────┴───────────────────────┘

Step 4: Opsi Driver & Catatan
┌──────────────────────────────────────────────┐
│  Pilihan: [Lepas Kunci] [Pake Driver]        │
│  Catatan Tambahan:                           │
│  [Textarea...]                               │
│                             [Lanjutkan →]    │
└──────────────────────────────────────────────┘

Step 5: Ringkasan & Konfirmasi
┌──────────────────────────────────────────────┐
│  RINGKASAN AKHIR                             │
│  - Unit: [Toyota Avanza]                     │
│  - Jadwal: [Tgl A] s/d [Tgl B]               │
│  - Fasilitas: [Driver/Tanpa Driver]          │
│  ────────────────────────────────────────────│
│  Total: Rp XXX | DP 30%: Rp XXX              │
│  [Metode Bayar: VA / QRIS / Transfer]        │
│  [← Kembali] [Bayar Sekarang (Snap)]         │
└──────────────────────────────────────────────┘
```

---

## 6. Customer Dashboard Layout

```
┌────────────────────────────────────────────────┐
│  HEADER: "Selamat datang, [Nama]"              │
├─────────────┬──────────────┬───────────────────┤
│  Total      │  Booking     │  Booking          │
│  Booking    │  Aktif       │  Selesai          │
├─────────────┴──────────────┴───────────────────┤
│  BOOKING TERBARU                               │
│  [Kode] [Kendaraan] [Tanggal] [Status] [Aksi]  │
│  [Kode] [Kendaraan] [Tanggal] [Status] [Aksi]  │
├────────────────────────────────────────────────┤
│  [Booking Baru] [Lihat Semua Riwayat]          │
└────────────────────────────────────────────────┘
```

---

Versi: 1.0.0 | Tanggal: 2026-05-14
