# UI/UX Design System — Siliwangi Rental

**Nama File:** `ui-ux-design.md`  
**Lokasi:** `documents/UIUX/`  
**Tujuan:** Panduan lengkap design system, typography, color palette, responsive design, halaman frontend, halaman admin, UX flow, dan dashboard design.

---

## 1. Design System Overview

Siliwangi Rental menggunakan pendekatan **Modern Glassmorphism** dengan sentuhan **Premium Dark/Light Hybrid**, mengutamakan kepercayaan (trust) dan kemudahan navigasi.

**Design Principles:**

- Clean & Professional — tidak berantakan, hierarchy jelas
- Trust-First — customer harus merasa aman bertransaksi
- Mobile-First — mayoritas user akses via smartphone
- Conversion-Oriented — setiap halaman mendorong aksi booking

---

## 2. Color Palette

| Token | Nama | Hex | Digunakan Untuk |
|:---|:---|:---|:---|
| `primary-600` | Deep Blue | `#1D4ED8` | CTA button, link utama |
| `primary-500` | Blue | `#3B82F6` | Hover, focus ring |
| `primary-50` | Light Blue | `#EFF6FF` | Background highlight |
| `secondary-600` | Slate | `#475569` | Teks sekunder |
| `accent-500` | Amber | `#F59E0B` | Badge featured, promo tag |
| `success-500` | Green | `#22C55E` | Status available, sukses |
| `danger-500` | Red | `#EF4444` | Status error, cancel |
| `warning-500` | Orange | `#F97316` | Status pending, peringatan |
| `neutral-900` | Near Black | `#0F172A` | Teks utama dark |
| `neutral-100` | Near White | `#F1F5F9` | Background light |
| `white` | White | `#FFFFFF` | Card background |

---

## 3. Typography

| Element | Font | Size | Weight |
|:---|:---|:---|:---|
| Heading H1 | Inter | 36px / 2.25rem | Bold (700) |
| Heading H2 | Inter | 28px / 1.75rem | SemiBold (600) |
| Heading H3 | Inter | 22px / 1.375rem | SemiBold (600) |
| Body Text | Inter | 16px / 1rem | Regular (400) |
| Small Text | Inter | 14px / 0.875rem | Regular (400) |
| Caption | Inter | 12px / 0.75rem | Regular (400) |
| Button | Inter | 14px / 0.875rem | Medium (500) |
| Price | Inter | 20px / 1.25rem | Bold (700) |

**Google Fonts Import:**

```html
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
```

---

## 4. Responsive Breakpoints (Tailwind)

| Breakpoint | Lebar | Device |
|:---|:---|:---|
| `sm` | 640px+ | Smartphone landscape |
| `md` | 768px+ | Tablet portrait |
| `lg` | 1024px+ | Tablet landscape / Small desktop |
| `xl` | 1280px+ | Desktop |
| `2xl` | 1536px+ | Large desktop |

**Grid System:**

- Mobile: 1 kolom
- Tablet: 2 kolom
- Desktop: 3-4 kolom

---

## 5. Frontend Pages

### 5.1 Homepage (`/`)

**Sections:**

1. **Hero Section** — Headline besar, CTA booking, gambar kendaraan
2. **Search Bar** — Quick search kendaraan (lokasi, tanggal)
3. **Armada Unggulan** — Featured cars carousel (4 cards)
4. **Tipe Kendaraan** — Icon grid: SUV, MPV, Sedan, Hatchback
5. **Keunggulan** — 4 value proposition cards
6. **Cara Pemesanan** — Step 1-4 visual dengan icon
7. **Testimoni** — Review customer slider
8. **CTA Banner** — Tombol "Lihat Semua Armada"
9. **Footer** — Info perusahaan, link navigasi, kontak

### 5.2 Catalog (`/catalog`)

**Layout:**

- Sidebar filter (kiri) + Grid kendaraan (kanan)
- Responsive: filter collapse jadi modal di mobile

**Components:**

- Filter panel (type, brand, transmisi, kategori, harga)
- Search bar dengan debounce Livewire
- Sort dropdown
- Car grid (3 kolom desktop, 2 tablet, 1 mobile)
- Car card dengan badge status, harga, tombol Detail & Booking
- Pagination

### 5.3 Detail Kendaraan (`/cars/{slug}`)

**Sections:**

1. Foto kendaraan (gallery / carousel)
2. Info utama: nama, tipe, brand, transmisi, kapasitas
3. Harga per hari dan per bulan
4. Deskripsi dan fasilitas (icon list)
5. Availability checker (pilih tanggal, cek ketersediaan)
6. CTA: "Booking Sekarang" button
7. Kendaraan serupa (related cars)

### 5.4 Checkout (`/checkout/{car}`)

**5 Step Wizard (Livewire Component):**

- **Step 1: Schedule**: Pilih tanggal dan lokasi.
- **Step 2: Details**: Input data pemesan.
- **Step 3: Documents**: Upload KTP/SIM.
- **Step 4: Extras**: Opsi Driver & Promo.
- **Step 5: Review**: Ringkasan biaya (Subtotal + PPN 12% - Promo).

### 5.7 Navbar Components

- **Notification Bell (Log-in Users only)**:
  - Tampilkan indikator dot merah jika ada booking dengan status `unpaid`.
  - Dropdown list menampilkan booking yang belum dibayar dengan link cepat ke pembayaran.
  - Efek `pulse` pada ikon lonceng untuk menarik perhatian user.

### 5.8 Post-Booking UX (Redirection)

- **Guest Flow**: Setelah checkout, otomatis diarahkan ke WhatsApp Admin dengan format pesan detail pesanan dan link Invoice PDF.
- **Member Flow**: Setelah checkout, diarahkan kembali ke halaman Home dengan Flash Message sukses. Notifikasi pembayaran muncul otomatis di Ikon Lonceng.

---

## 6. Admin Panel Pages (Filament v4)

### 6.1 Dashboard Admin

**Widgets:**

- Total booking hari ini (Stat Widget)
- Revenue bulan ini (Stat Widget)
- Kendaraan tersedia (Stat Widget)
- Booking pending (Stat Widget)
- Chart booking per minggu (Line Chart)
- Chart revenue per bulan (Bar Chart)
- Tabel booking terbaru

---

## 7. UX Flow

### Customer Journey

```text
Awareness → Homepage → Lihat Featured Cars
Consideration → Catalog → Filter → Detail Mobil
Decision → Checkout → Bayar DP → Tunggu Approval
Retention → Notifikasi → Invoice → Review
```

### Admin Flow

```text
Login → Dashboard → Booking Pending → Review → Approve
      → Assign Driver → Monitoring → Return Processing → Complete
```

---

## 8. Component Design Standards

| Komponen | Style |
|:---|:---|
| Button Primary | bg-blue-600, rounded-lg, px-6 py-3, hover:bg-blue-700 |
| Button Outline | border border-blue-600, text-blue-600, hover:bg-blue-50 |
| Card | bg-white, rounded-2xl, shadow-md, p-6 |
| Badge Available | bg-green-100, text-green-800, text-xs, rounded-full |
| Badge Pending | bg-yellow-100, text-yellow-800 |
| Badge Cancelled | bg-red-100, text-red-800 |
| Input Field | border border-gray-300, rounded-lg, focus:ring-2 ring-blue-500 |
| Alert Success | bg-green-50, border-l-4 border-green-500, text-green-800 |
| Alert Error | bg-red-50, border-l-4 border-red-500, text-red-800 |

---

Versi: 1.1.0 | Tanggal: 2026-05-14
