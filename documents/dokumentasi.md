# Dokumentasi Teknis Siliwangi Rental

**Nama File:** `dokumentasi.md`  
**Lokasi:** `documents/`  
**Tujuan:** Panduan teknis utama untuk pengembangan dan operasional sistem.

---

## 1. Alur Utama Sistem

### 1.1 Booking & Checkout

Sistem menggunakan **Multi-step Checkout** (5 langkah) yang kini mendukung **Multi-Car Booking** (beberapa mobil dalam satu transaksi):

1. **Schedule**: Pilih tanggal dan lokasi.
2. **Details**: Input data pemesan.
3. **Documents**: Upload KTP/SIM.
4. **Extras**: Opsi Driver & Promo (berlaku untuk seluruh armada yang dipilih).
5. **Review**: Ringkasan biaya itemized (Total semua mobil + PPN 12% - Promo).

### 1.2 Notifikasi Kondisional

- **User Login**: Diarahkan ke halaman Home. Notifikasi tagihan muncul di **Ikon Lonceng** Navbar.
- **Guest**: Diarahkan otomatis ke **WhatsApp Admin** dengan detail booking.

---

## 2. Struktur Data & Perhitungan

### 2.1 Komponen Biaya (Invoice)

Perhitungan dilakukan secara akumulatif untuk semua mobil di `app/Livewire/Checkout.php`:

- `Subtotal`: Σ (Harga Mobil \* Hari) + Biaya Operasional + Biaya Admin.
- `Pajak`: 12% dari (Subtotal - Diskon).
- `Total Akhir`: Subtotal - Diskon + Pajak.

_Rincian item disimpan dalam tabel `booking_items` untuk mendukung pelaporan per unit._

### 2.2 Status Penting

- **Booking Status**: `pending`, `paid`, `confirmed`, `completed`, `expired`.
- **Payment Status**: `pending`, `partial`, `paid`.

---

## 3. Informasi Kredensial (Default Seeder)

| Role            | Email                             | Password   |
| --------------- | --------------------------------- | ---------- |
| **Super Admin** | email = `[admin@siliwangi.com]`   | `password` |
| **Owner**       | email = `[owner@siliwangi.com]`   | `password` |
| **Finance**     | email = `[finance@siliwangi.com]` | `password` |
| **Driver**      | email = `[asep@siliwangi.com]`    | `password` |
| **Customer**    | email = `[budi@gmail.com]`        | `password` |

---

## 4. Pengembangan Lanjutan (To-do)

- [x] Implementasi Scheduler `Auto-Expire` (24 jam).
- [ ] Dashboard Analytics per Cabang (Multi-branch) - _Locked / Disabled_.
- [x] Manajemen Driver (Availability Real-time).
- [x] Integrasi E-Faktur Pajak. belum tersedia
- [x] Booking Mobile Apps (Native/PWA). belum tersedia

---

Versi: 1.1.0 | Tanggal: 2026-05-14
