# Testing — Siliwangi Rental

**Nama File:** `testing.md`  
**Lokasi:** `documents/QA/`  
**Tujuan:** Panduan testing Laravel menggunakan Pest PHP dan strategi testing sistem Siliwangi Rental.

---

## 1. Framework Testing

| Framework              | Digunakan Untuk                           |
| ---------------------- | ----------------------------------------- |
| **Pest PHP**           | Unit test, Feature test, Integration test |
| **Laravel HTTP Tests** | Endpoint testing                          |
| **Livewire Testing**   | Komponen Livewire                         |

---

## 2. Struktur Test

```
tests/
├── Unit/
│   ├── Models/
│   │   ├── BookingTest.php
│   │   ├── CarTest.php
│   │   └── PaymentTest.php
│   └── Services/
│       ├── MidtransServiceTest.php
│       └── WhatsAppServiceTest.php
│
└── Feature/
    ├── Auth/
    │   ├── RegistrationTest.php
    │   ├── LoginTest.php
    │   └── PasswordResetTest.php
    ├── Booking/
    │   ├── BookingCreationTest.php
    │   ├── BookingCancellationTest.php
    │   └── BookingExpireTest.php
    ├── Payment/
    │   ├── MidtransWebhookTest.php
    │   └── InvoiceTest.php
    └── Admin/
        ├── BookingApprovalTest.php
        └── ReportTest.php
```

---

## 3. Contoh Test Cases (Pest)

### Unit Test — Booking Model

```php
// tests/Unit/Models/BookingTest.php

test('booking code follows SR-YYYYMMDD-XXXX format', function () {
    $booking = Booking::factory()->create();
    expect($booking->booking_code)->toMatch('/^SR-\d{8}-\d{4}$/');
});

test('booking is expired when payment_due_at is in the past', function () {
    $booking = Booking::factory()->create([
        'status' => 'pending',
        'payment_due_at' => now()->subHours(25),
    ]);
    expect($booking->isExpired())->toBeTrue();
});

test('fine is calculated correctly', function () {
    $booking = Booking::factory()->create([
        'end_date' => '2026-05-10',
        'actual_return_date' => '2026-05-12',
    ]);
    // 2 hari terlambat
    expect($booking->calculateFine())->toBe(2);
});
```

### Feature Test — Auth Registration

```php
// tests/Feature/Auth/RegistrationTest.php

test('user can register with valid data', function () {
    $response = $this->post('/register', [
        'name' => 'Budi Santoso',
        'email' => 'budi@test.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'phone' => '08123456789',
    ]);

    $response->assertRedirect('/email/verify');
    $this->assertDatabaseHas('users', ['email' => 'budi@test.com']);
});

test('user cannot register with duplicate email', function () {
    User::factory()->create(['email' => 'existing@test.com']);

    $response = $this->post('/register', [
        'email' => 'existing@test.com',
        'password' => 'password123',
    ]);

    $response->assertSessionHasErrors('email');
});
```

### Feature Test — Midtrans Webhook

```php
// tests/Feature/Payment/MidtransWebhookTest.php

test('valid settlement webhook updates payment and booking status', function () {
    $booking = Booking::factory()->create(['status' => 'pending']);
    $payment = Payment::factory()->create([
        'booking_id' => $booking->id,
        'order_id' => 'SR-20260514-0001-DP',
        'status' => 'pending',
    ]);

    $payload = [
        'order_id' => 'SR-20260514-0001-DP',
        'transaction_status' => 'settlement',
        'gross_amount' => '750000.00',
        'status_code' => '200',
        'signature_key' => hash('sha512',
            'SR-20260514-0001-DP200750000.00' . config('services.midtrans.server_key')
        ),
    ];

    $this->postJson('/midtrans/callback', $payload)->assertOk();

    expect($payment->fresh()->status)->toBe('paid');
    expect($booking->fresh()->status)->toBe('paid');
});
```

---

## 4. Run Tests

```bash
# Jalankan semua test
php artisan test

# Jalankan dengan coverage (membutuhkan Xdebug)
php artisan test --coverage

# Jalankan test spesifik
php artisan test tests/Feature/Auth/RegistrationTest.php

# Jalankan filter test tertentu
php artisan test --filter="user can register"
```

---

## 5. Test Database

```php
// phpunit.xml
<php>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>
</php>
```

Gunakan `RefreshDatabase` atau `DatabaseTransactions` trait di setiap test class.

---

---

## 7. Blackbox Testing Results

Pengujian Blackbox dilakukan untuk memvalidasi fungsionalitas sistem dari sudut pandang pengguna tanpa melihat struktur kode internal.

| ID    | Fitur             | Input / Skenario                      | Hasil yang Diharapkan                             | Status  |
| :---- | :---------------- | :------------------------------------ | :------------------------------------------------ | :-----: |
| BT-01 | Registrasi        | Input form registrasi lengkap & valid | Akun terbuat, redirect ke verifikasi email        | ✅ Pass |
| BT-02 | Login             | Input email & password yang terdaftar | Berhasil masuk ke sistem, session aktif           | ✅ Pass |
| BT-03 | Katalog Mobil     | Filter berdasarkan brand/kategori     | Daftar mobil terfilter sesuai kriteria            | ✅ Pass |
| BT-04 | Checkout Step 1   | Pilih paket sewa & durasi             | Subtotal terhitung otomatis secara real-time      | ✅ Pass |
| BT-05 | Checkout Step 2   | Pilih opsi driver (Pakai/Lepas)       | Biaya driver (Rp 150rb/hari) ditambahkan ke total | ✅ Pass |
| BT-06 | Checkout Step 4   | Review rincian biaya & promo          | Subtotal, Pajak 12%, & Diskon tampil akurat       | ✅ Pass |
| BT-07 | Notifikasi (Auth) | Selesaikan checkout saat login        | Ikon lonceng di navbar muncul notifikasi baru     | ✅ Pass |
| BT-08 | Direct WA (Guest) | Selesaikan checkout tanpa login       | Browser otomatis redirect ke WhatsApp Admin       | ✅ Pass |
| BT-09 | Invoice PDF       | Klik tombol "Download Invoice"        | File PDF terunduh dengan rincian biaya lengkap    | ✅ Pass |

---

## 8. Visual Testing & UI Screenshots

Berikut adalah dokumentasi visual hasil testing fitur dan antarmuka (UI) Siliwangi Rental.

### 6.1 Landing Page & Navigation

![Landing Page](./screenshots/landing_page.png)
_Deskripsi: Tampilan beranda dengan hero section dan navigasi utama._

### 6.2 Catalog & Car Details

![Catalog Page](./screenshots/catalog_page.png)
_Deskripsi: Filter kendaraan dan tampilan grid mobil._

### 6.3 Checkout 5-Step Wizard

![Checkout Flow](./screenshots/checkout_flow.png)
_Deskripsi: Proses pemesanan 5 langkah (Pilih Paket, Driver, Identitas, Review, Finish)._

### 6.4 Notification System (Navbar)

![Payment Notification](./screenshots/payment_notification.png)
_Deskripsi: Ikon lonceng notifikasi pembayaran di navbar untuk user yang login._

### 6.5 Invoice PDF (Dynamic Layout)

![Invoice PDF](./screenshots/invoice_pdf.png)
_Deskripsi: Rincian invoice dengan subtotal, promo, PPN 12%, dan total akhir._

### 6.6 Guest Flow (WhatsApp Redirect)

![WhatsApp Redirect](./screenshots/wa_redirect.png)
_Deskripsi: Tampilan redirect otomatis ke WhatsApp untuk guest user._

---

Versi: 1.1.0 | Tanggal: 2026-05-14
