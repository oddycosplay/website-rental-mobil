# Sequence Diagram — Siliwangi Rental

**Nama File:** `sequence-diagram.md`  
**Lokasi:** `documents/UML/`  
**Tujuan:** Dokumentasi sequence diagram interaksi antar komponen sistem.

---

## 1. Sequence Diagram — Booking + Payment

```mermaid
sequenceDiagram
    actor Customer
    participant Browser
    participant LivewireCheckout
    participant BookingController
    participant MidtransService
    participant Midtrans
    participant WebhookController
    participant Database
    participant Queue
    participant WhatsApp

    Customer->>Browser: Klik "Booking Sekarang"
    Browser->>LivewireCheckout: Load checkout form
    Customer->>LivewireCheckout: Isi 5 step form + submit

    LivewireCheckout->>Database: Simpan Booking (status: pending)
    Database-->>LivewireCheckout: Booking ID

    LivewireCheckout->>Queue: Dispatch SendWhatsAppJob (booking created)
    Queue->>WhatsApp: Kirim notifikasi customer
    Queue->>WhatsApp: Kirim notifikasi admin

    LivewireCheckout->>MidtransService: createSnapToken(booking)
    MidtransService->>Midtrans: POST /snap/v1/transactions
    Midtrans-->>MidtransService: snap_token
    MidtransService-->>LivewireCheckout: snap_token

    Browser->>Midtrans: Tampilkan Snap UI (snap_token)
    Customer->>Midtrans: Pilih metode & bayar
    Midtrans-->>Customer: Konfirmasi pembayaran

    Midtrans->>WebhookController: POST /midtrans/callback
    WebhookController->>WebhookController: Validasi signature key
    WebhookController->>Database: Update Payment status = paid
    WebhookController->>Database: Update Booking status = paid
    WebhookController->>Queue: Dispatch notifikasi payment success
    Queue->>WhatsApp: Notifikasi DP berhasil
```

---

## 2. Sequence Diagram — Admin Approval

```mermaid
sequenceDiagram
    actor Admin
    participant FilamentPanel
    participant BookingResource
    participant Database
    participant Queue
    participant Email

    Admin->>FilamentPanel: Buka Booking Management
    FilamentPanel->>Database: Query bookings where status = paid
    Database-->>FilamentPanel: List booking

    Admin->>BookingResource: Klik Approve Booking
    BookingResource->>Database: Update booking.status = confirmed
    BookingResource->>Database: Update car.status = booked
    BookingResource->>Database: Assign driver_id (jika ada)
    BookingResource->>Database: Log aktivitas admin

    BookingResource->>Queue: Dispatch BookingConfirmedNotification
    Queue->>Email: Kirim email ke customer
    Queue->>Email: Kirim WhatsApp ke customer

    FilamentPanel-->>Admin: Success notification
```

---

## 3. Sequence Diagram — Return Processing

```mermaid
sequenceDiagram
    actor Admin
    participant FilamentPanel
    participant Database
    participant Queue
    participant Customer

    Admin->>FilamentPanel: Buka Return Processing
    Admin->>FilamentPanel: Input tanggal kembali aktual

    FilamentPanel->>FilamentPanel: Hitung keterlambatan
    alt Ada keterlambatan
        FilamentPanel->>Database: Buat Payment (type: fine)
        FilamentPanel->>Database: Update booking.fine_amount
        FilamentPanel->>Queue: Dispatch FineCreatedNotification
        Queue->>Customer: Notifikasi denda
    end

    FilamentPanel->>Database: Update booking.status = returned
    FilamentPanel->>Database: Update car.status = returned

    alt Tidak ada denda atau denda lunas
        FilamentPanel->>Database: Update booking.status = completed
        FilamentPanel->>Queue: Dispatch BookingCompletedNotification
        Queue->>Customer: Notifikasi selesai + invoice
    end
```

---

Versi: 1.0.0 | Tanggal: 2026-05-14
