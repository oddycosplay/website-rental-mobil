# Activity Log — Siliwangi Rental

**Nama File:** `activity-log.md`  
**Lokasi:** `documents/SECURITY/`  
**Tujuan:** Dokumentasi implementasi activity log sistem untuk audit trail.

---

## 1. Package

Menggunakan **Spatie Laravel Activity Log** (`spatie/laravel-activitylog`).

```bash
composer require spatie/laravel-activitylog
php artisan activitylog:install
php artisan migrate
```

---

## 2. Event yang Dicatat

 | Event | Aktor | Keterangan |
|---|---|---|
 | Login berhasil | Customer/Admin | Session start |
 | Login gagal | — | IP + waktu |
 | Booking dibuat | Customer | Data booking |
 | Booking diapprove | Admin | Booking ID + admin |
 | Booking direject | Admin | Booking ID + alasan |
 | Booking dibatalkan | Customer/Admin | Booking ID + alasan |
 | Pembayaran berhasil | Midtrans (webhook) | Payment ID + amount |
 | Kendaraan diubah | Admin | Field yang diubah |
 | Driver diassign | Admin | Driver + Booking |
 | Return processing | Admin | Booking ID + tanggal |
 | Refund diproses | Finance | Booking + amount |
 | User diblokir | Admin | User ID |
 | Role/permission diubah | Owner | User + role |

---

## 3. Implementasi di Model

```php
// app/Models/Booking.php
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Booking extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Booking {$eventName}")
            ->useLogName('booking');
    }
}
```

---

## 4. Manual Log di Controller/Service

```php
// Contoh log aksi admin approve booking
activity('booking')
    ->causedBy(auth()->user())
    ->performedOn($booking)
    ->withProperties([
        'booking_code' => $booking->booking_code,
        'action' => 'approve',
        'previous_status' => 'paid',
        'new_status' => 'confirmed',
    ])
    ->log('Admin approved booking');
```

---

## 5. Melihat Activity Log di Admin Panel

- Tersedia di Admin Panel → System → Activity Log
- Filter: by user, by model, by tanggal, by event
- Tidak bisa dihapus oleh admin biasa — hanya owner

---

## 6. Retention Policy

- Activity log disimpan selama **6 bulan**.
- Setelah 6 bulan, log lama diarsipkan atau dihapus via scheduled command.

```php
// Cleanup command — jalankan bulanan
ActivityLog::where('created_at', '<', now()->subMonths(6))->delete();
```

---

Versi: 1.0.0 | Tanggal: 2026-05-14
