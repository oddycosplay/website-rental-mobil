# Fraud Prevention — Siliwangi Rental

**Nama File:** `fraud-prevention.md`  
**Lokasi:** `documents/SECURITY/`  
**Tujuan:** Dokumentasi langkah-langkah pencegahan fraud pada sistem Siliwangi Rental.

---

## 1. Fraud Booking

 | Risiko | Mitigasi |
|---|---|
 | Dokumen KTP/SIM palsu | Review manual admin sebelum approval |
 | Booking spam | Rate limiting + email verification wajib |
 | Double booking kendaraan | Database constraint + availability lock |
 | Booking dibuat tapi tidak bayar | Auto-expire setelah 24 jam |
 | Identity fraud (orang lain) | Verifikasi KTP + telepon saat pengambilan |

---

## 2. Fraud Payment

 | Risiko | Mitigasi |
|---|---|
 | Fake webhook | Validasi Midtrans signature SHA512 |
 | Duplicate transaction | Idempotency: order_id unik di DB |
 | Refund fraud | Refund hanya via Finance manual, tidak otomatis |
 | Payment bypassing | Status booking tidak bisa diubah tanpa payment record |
 | Replay attack | Timestamp validation pada webhook |

### Signature Validation

```php
// Midtrans signature validation
$serverKey = config('services.midtrans.server_key');
$orderId = $payload['order_id'];
$statusCode = $payload['status_code'];
$grossAmount = $payload['gross_amount'];

$expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

if ($payload['signature_key'] !== $expectedSignature) {
    Log::warning('Invalid Midtrans signature', ['order_id' => $orderId]);
    abort(403, 'Invalid signature');
}
```

---

## 3. Fraud Account

 | Risiko | Mitigasi |
|---|---|
 | Akun bot/spam | Email verification wajib |
 | Brute force login | Fortify throttle 5x/60s |
 | Akun bermasalah | Admin bisa blokir akun (is_active = false) |
 | Credential stuffing | HTTPS wajib, bcrypt password |

---

## 4. Rate Limiting

```php
// routes/api.php
Route::middleware('throttle:60,1')->group(function () {
    // 60 request per menit per IP
});

// Endpoint sensitif
Route::middleware('throttle:10,1')->group(function () {
    Route::post('/login');
    Route::post('/register');
    Route::post('/forgot-password');
});
```

---

Versi: 1.0.0 | Tanggal: 2026-05-14
