# Notification System — Siliwangi Rental

**Nama File:** `notification-system.md`  
**Lokasi:** `documents/SRS/`  
**Tujuan:** Spesifikasi teknis sistem notifikasi — WhatsApp, Email, dan SMS — beserta trigger event setiap notifikasi.

---

## Metadata Dokumen

 | Atribut | Detail |
|---|---|
 | Nama Project | Siliwangi Rental |
 | Versi | 1.0.0 |
 | Tanggal | 2026-05-14 |

---

## 1. Channel Notifikasi

 | Channel | Service | Status |
|---|---|---|
 | WhatsApp | WhatsApp Cloud API / Fonnte | ✅ Aktif |
 | Email | Laravel Mail (SMTP) | ✅ Aktif |
 | SMS | Belum ditentukan pada requirement | ⏳ Planned |

---

## 2. Queue System

Semua notifikasi dikirim via **Laravel Queue** (asinkron) untuk menghindari blocking request:

```php
// app/Jobs/SendWhatsAppJob.php
class SendWhatsAppJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60; // detik, exponential

    public function handle(): void
    {
        app(WhatsAppService::class)->send($this->phone, $this->message);
    }
}
```

Queue worker: `php artisan queue:work --queue=notifications`

---

## 3. Event & Trigger Matrix

 | Event | WhatsApp | Email | Penerima |
|---|---|---|---|
 | Booking baru dibuat | ✅ | ✅ | Customer + Admin |
 | DP berhasil dibayar | ✅ | ✅ | Customer + Admin |
 | Booking diapprove | ✅ | ✅ | Customer |
 | Booking ditolak | ✅ | ✅ | Customer |
 | Booking dibatalkan (customer) | ✅ | ✅ | Customer + Admin |
 | Booking dibatalkan (admin) | ✅ | ✅ | Customer |
 | Booking expired (auto) | ❌ | ✅ | Customer |
 | Pelunasan berhasil | ✅ | ✅ | Customer |
 | Denda dibuat | ✅ | ✅ | Customer |
 | Denda berhasil dibayar | ✅ | ✅ | Customer |
 | Booking selesai (completed) | ✅ | ✅ | Customer |
 | Reminder H-1 | ✅ | ✅ | Customer |
 | Keterlambatan pengembalian | ✅ | ✅ | Customer + Admin |
 | Invoice tersedia | ❌ | ✅ | Customer |

---

## 4. WhatsApp Notification

**File:** `app/Services/WhatsAppService.php`

```php
class WhatsAppService
{
    public function send(string $phone, string $message): void
    {
        // Kirim via Fonnte API / WhatsApp Cloud API
        Http::withToken(config('services.whatsapp.token'))
            ->post(config('services.whatsapp.url'), [
                'target' => $phone,
                'message' => $message,
            ]);
    }
}
```

### 4.1 Template Pesan WhatsApp

**Booking Baru:**

```
Halo {nama}!
Booking Anda telah diterima 🎉

📋 Kode Booking: {kode}
🚗 Kendaraan: {mobil}
📅 Tanggal: {start} s/d {end}
💰 Total: Rp {total}
💳 DP: Rp {dp}

Silakan lakukan pembayaran DP dalam 24 jam.
Terima kasih telah mempercayai Siliwangi Rental!
```

**Booking Diapprove:**

```
Halo {nama}! ✅

Booking Anda telah dikonfirmasi!

📋 Kode: {kode}
🚗 {mobil}
📅 {start} - {end}
{driver_info}

Kami akan menghubungi Anda sebelum hari H.
Siliwangi Rental 🚗
```

**Reminder H-1:**

```
Halo {nama}! 🔔

Pengingat: Masa sewa Anda dimulai BESOK!

🚗 {mobil}
📅 Mulai: {start_date}
📍 Lokasi: {pickup_address}
{driver_info}

Pastikan dokumen Anda sudah siap.
Siliwangi Rental 🚗
```

**Booking Selesai:**

```
Halo {nama}! 🎊

Terima kasih telah menggunakan layanan Siliwangi Rental.

📋 Kode: {kode}
🚗 {mobil}
📅 {start} - {end}

Invoice dapat diunduh di: {invoice_link}

Sampai jumpa kembali! 🚗
```

---

## 5. Email Notification

**File:** `app/Notifications/`

 | Kelas Notifikasi | Trigger | Template View |
|---|---|---|
 | `BookingCreatedNotification` | Booking baru | `emails.booking.created` |
 | `PaymentSuccessNotification` | DP berhasil | `emails.payment.success` |
 | `BookingConfirmedNotification` | Admin approve | `emails.booking.confirmed` |
 | `BookingCancelledNotification` | Booking dibatalkan | `emails.booking.cancelled` |
 | `BookingExpiredNotification` | Auto-expire | `emails.booking.expired` |
 | `BookingCompletedNotification` | Selesai | `emails.booking.completed` |
 | `FineCreatedNotification` | Denda dibuat | `emails.fine.created` |
 | `ReminderNotification` | H-1 reminder | `emails.booking.reminder` |

**Konfigurasi:**

```php
// .env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io  // atau mail server production
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=noreply@siliwangirental.com
MAIL_FROM_NAME="Siliwangi Rental"
```

---

## 6. SMS Notification

Belum ditentukan pada requirement.

Infrastruktur siap: dapat diintegrasikan via SMS gateway (Twilio, Nexmo, Zenziva) menggunakan pattern yang sama dengan WhatsApp Job.

---

## 7. Admin Notification (Internal)

Admin menerima notifikasi melalui:

- Email ke alamat admin saat ada booking baru.
- Email ke alamat admin saat ada DP masuk.
- Indikator visual (badge) di admin panel (Filament notification widget).

---

Versi: 1.0.0 | Tanggal: 2026-05-14
