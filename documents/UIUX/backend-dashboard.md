# Backend Dashboard — Admin Panel

**Nama File:** `backend-dashboard.md`  
**Lokasi:** `documents/UIUX/`  
**Tujuan:** Dokumentasi layout dan komponen admin panel berbasis Filament v4.

---

## 1. Admin Panel Overview

- Framework: **Filament v4**
- URL: `/admin`
- Auth Guard: Filament auth (pakai akun User dengan role admin/owner/finance)
- Theme: Filament default dark/light hybrid

---

## 2. Navigation Sidebar

```
┌───────────────────────────────┐
│  SILIWANGI RENTAL             │
│  [Logo] Admin Panel           │
├───────────────────────────────┤
│  🏠 Dashboard                 │
├───────────────────────────────┤
│  📋 OPERASIONAL              │
│    📅 Booking                │
│    💳 Pembayaran             │
│    🚗 Kendaraan              │
│    👤 Driver                 │
│    👥 Customer               │
├───────────────────────────────┤
│  🔧 ARMADA                   │
│    🔩 Maintenance            │
│    🔍 Inspeksi               │
│    📍 Lokasi                 │
├───────────────────────────────┤
│  💰 KEUANGAN                 │
│    💸 Pengeluaran            │
│    🏷️ Kategori Pengeluaran   │
├───────────────────────────────┤
│  ⚙️ MASTER DATA              │
│    🏢 Cabang                 │
│    🏷️ Tipe Kendaraan         │
│    🔖 Merek Kendaraan        │
│    🎁 Promo                  │
├───────────────────────────────┤
│  👥 SISTEM                   │
│    👤 Pengguna               │
│    🔐 Role & Permission      │
└───────────────────────────────┘
```

---

## 3. Dashboard Widgets Layout

```
┌─────────────────────────────────────────────────────────┐
│  STAT WIDGETS (4 kolom)                                 │
│  [Total Booking]  [Revenue]  [Kendaraan]  [Driver]      │
│  Hari Ini         Bulan Ini   Tersedia    Tersedia      │
├────────────────────────────┬────────────────────────────┤
│  BOOKING TREND (Line)      │  REVENUE TREND (Bar)       │
│  7 hari terakhir           │  12 bulan terakhir         │
├────────────────────────────┴────────────────────────────┤
│  BOOKING TERBARU (Table)                                │
│  Kode | Customer | Mobil | Tgl | Status | Aksi          │
└─────────────────────────────────────────────────────────┘
```

---

## 4. Booking Management

### List View (Tabel)

| Kolom        | Keterangan              |
| ------------ | ----------------------- |
| Kode Booking | Link ke detail          |
| Customer     | Nama + email            |
| Kendaraan    | Nama + plat             |
| Periode      | Start - End date        |
| Total        | Nilai booking           |
| Status       | Badge berwarna          |
| Aksi         | Approve / Reject / View |

### Filter Tersedia

- Status booking
- Cabang
- Periode (tanggal)
- Jenis sewa

### Actions (per Row)

- **View** — Buka detail booking
- **Approve** — Ubah status → confirmed
- **Reject** — Ubah status → cancelled (input alasan)
- **Assign Driver** — Pilih driver untuk booking
- **Return Processing** — Input pengembalian

---

## 5. Car Management

### List View

| Kolom      | Keterangan           |
| ---------- | -------------------- |
| Foto       | Thumbnail            |
| Nama       | Nama kendaraan       |
| Plat       | Nomor plat           |
| Tipe       | Tipe kendaraan       |
| Harga/Hari | Formatted            |
| Status     | Badge                |
| Featured   | Toggle               |
| Aksi       | Edit / Delete / View |

### Create/Edit Form

- Informasi Dasar: nama, brand, tipe, tahun, warna, plat
- Spesifikasi: transmisi, kapasitas, bahan bakar, kategori
- Harga: harga harian, bulanan, driver price
- Fasilitas: checkbox multi-select
- Media: upload foto utama
- Status: available/maintenance/dll, is_featured, is_active
- Cabang: select cabang

---

## 6. Filament Action Colors

| Action  | Warna              |
| ------- | ------------------ |
| Approve | `success` (green)  |
| Reject  | `danger` (red)     |
| Edit    | `warning` (yellow) |
| View    | `info` (blue)      |
| Delete  | `danger` (red)     |
| Assign  | `primary` (blue)   |

---

_Versi: 1.0.0 | Tanggal: 2026-05-14_
