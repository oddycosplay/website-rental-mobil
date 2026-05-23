# System Workflow — Siliwangi Rental

**Nama File:** `workflow.md`  
**Lokasi:** `documents/BRD/`  
**Tujuan:** Dokumentasi alur kerja sistem dari sisi User (Customer/Guest) dan Backend.

---

## 1. Booking Workflow

### 1.1 Alur Booking Customer (Logged In)

```text
Customer → Login → Catalog → Pilih Mobil → Detail Mobil → Klik Booking → Multi-Step Form
  Step 1: Pilih Tanggal & Lokasi (Jemput/Diantar)
  Step 2: Konfirmasi Data Diri (Auto-fill dari Profile)
  Step 3: Upload Dokumen (KTP, SIM, dsb)
  Step 4: Opsi Driver & Catatan Tambahan
  Step 5: Ringkasan Bayar (Subtotal + PPN 12% - Promo) → Selesaikan

  → Sistem: Create Booking + Generate Snap Token
  → Redirect: Home Page
  → Notifikasi: Muncul Ikon Lonceng Merah (Payment Required)
  → Customer: Klik Lonceng → Pilih Booking → Bayar via Midtrans
```

### 1.2 Alur Booking Guest (Non-Login)

```text
Guest → Catalog → Pilih Mobil → Form Booking (Multi-Step)
  Step 1: Pilih Tanggal & Lokasi
  Step 2: Input Data Manual (Nama, Email, HP, dsb)
  Step 3: Upload Dokumen
  Step 4: Opsi Driver & Catatan
  Step 5: Ringkasan Bayar (Dynamic Calculation) → Selesaikan

  → Sistem: Create Booking
  → Redirect: Otomatis ke WhatsApp Admin (dengan detail pesanan & link invoice)
  → Sistem: Kirim WhatsApp Notifikasi ke Customer (via Queue)
```

---

## 2. Payment Workflow

### 2.1 Midtrans Gateway Integration

```text
Sistem → Request Snap Token → Tampilkan Pop-up Pembayaran
Customer → Pilih Metode (VA/QRIS/CC) → Bayar
Midtrans Webhook → Update `payments` table & `bookings` status
  - Settlement: Status Booking → PAID, Status Payment → SUCCESS
  - Pending: Status Booking → PENDING, Status Payment → PENDING
  - Expire/Deny: Status Booking → EXPIRED/CANCELLED
```

---

## 3. Notification Flow

### 3.1 Real-time & Queue Notification

```text
Event: Booking Created
  - Auth User: Navbar Bell Indicator (Database Notification)
  - Guest: WhatsApp Redirect + WhatsApp Message (Queue)

Event: Payment Success
  - Admin: Dashboard Notification + WhatsApp Notification
  - Customer: WhatsApp Confirmation + Invoice PDF Link
```

---

## 4. Invoice Generation

```text
Sistem → Load Data (Booking + Car + Customer + Extra Fees)
Calculation:
  Subtotal = (Rental Rate * Days) + Driver Fee + Admin Fee
  Pajak = (Subtotal - Promo) * 0.12
  Grand Total = Subtotal - Promo + Pajak
Template: resources/views/pdf/invoice.blade.php
Output: PDF Downloadable & Viewable
```

---

Versi: 1.1.0 | Tanggal: 2026-05-14
