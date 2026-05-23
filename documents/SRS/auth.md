# Authentication Specification — Siliwangi Rental

**Nama File:** `auth.md`  
**Lokasi:** `documents/SRS/`  
**Tujuan:** Spesifikasi teknis autentikasi — login flow, guest checkout, middleware, role permission, dan session management.

---

## Metadata Dokumen

 | Atribut | Detail |
|---|---|
 | Nama Project | Siliwangi Rental |
 | Versi | 1.0.0 |
 | Tanggal | 2026-05-14 |

---

## 1. Authentication Methods

 | Method | Digunakan Untuk |
|---|---|
 | Email + Password (Fortify) | Customer, Admin, Finance, Driver, Owner |
 | Guest Token (UUID) | Customer tanpa akun (guest checkout) |
 | Sanctum Token | API endpoint internal |
 | Filament Auth | Admin panel (pakai akun User dengan role) |

---

## 2. Login Flow

### 2.1 Customer Login

```
POST /login
Body: { email, password, remember (optional) }

Validasi:
  - email: required|email|exists:users
  - password: required|min:8

Sukses:
  - Cek email_verified_at → jika null: redirect ke verify email page
  - Cek role customer → set session
  - Redirect ke /dashboard

Gagal:
  - Return ValidationException: "Email atau password salah"
  - Throttle: 5 kali gagal → lockout 60 detik
```

### 2.2 Admin / Staff Login

```
Admin mengakses /admin (Filament Panel)
  → Filament auth guard menangani login
  → Cek user.is_admin_panel = true
  → Redirect ke /admin/dashboard
```

---

## 3. Register Flow

```
POST /register
Body: { name, email, password, password_confirmation, phone }

Validasi:
  - name: required|string|max:255
  - email: required|email|unique:users
  - password: required|min:8|confirmed
  - phone: required|string|max:20

Proses:
  1. Buat User baru (role: customer)
  2. Buat record Customers (data profil terpisah)
  3. Kirim email verifikasi
  4. Login otomatis → redirect ke /email/verify notice
```

---

## 4. Forgot Password & Reset

```
Forgot Password:
  POST /forgot-password
  Body: { email }
  → Kirim link reset via email (expire: 60 menit)

Reset Password:
  POST /reset-password
  Body: { token, email, password, password_confirmation }
  → Validasi token
  → Update password (bcrypt)
  → Invalidate semua session lama
  → Redirect ke login dengan pesan sukses
```

---

## 5. Email Verification

```
Setelah register → user menerima email verifikasi

GET /email/verify/{id}/{hash}
  → Validasi hash
  → Set email_verified_at = now()
  → Redirect ke /dashboard

Resend verifikasi:
  POST /email/verification-notification
  → Kirim ulang email (throttle: 1 kali per 60 detik)

Middleware 'verified':
  Dipakai di semua route yang butuh email verified
```

---

## 6. Guest Checkout

```
Guest tidak memiliki akun User.

Flow:
  1. Customer pilih kendaraan → klik "Booking sebagai Tamu"
  2. Isi form: nama, email, telepon
  3. Sistem generate guest_token (UUID)
  4. Booking tersimpan dengan guest_token (user_id = null)
  5. Kirim email konfirmasi + link tracking: /booking/track/{guest_token}
  6. Pembayaran via Midtrans menggunakan email guest

Keterbatasan Guest:
  - Tidak bisa melihat riwayat booking di dashboard
  - Tracking hanya via link email
  - Tidak bisa download invoice langsung (link di email)
```

---

## 7. Middleware Stack

### 7.1 Route Middleware

 | Middleware | Alias | Fungsi |
|---|---|---|
 | `Authenticate` | `auth` | Cek user sudah login |
 | `EnsureEmailIsVerified` | `verified` | Cek email sudah diverifikasi |
 | `RoleMiddleware` | `role:admin` | Spatie — cek role user |
 | `PermissionMiddleware` | `permission:view booking` | Spatie — cek permission |
 | `RedirectIfAuthenticated` | `guest` | Redirect jika sudah login |
 | `ThrottleRequests` | `throttle` | Rate limiting |

### 7.2 Contoh Penggunaan di Routes

```php
// Customer routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/checkout/{car:slug}', [CheckoutController::class, 'index']);
});

// Admin routes (via Filament Panel)
Route::middleware(['auth', 'role:admin|owner|finance'])->group(function () {
    // Filament handles internally
});
```

---

## 8. Role & Permission (Spatie)

### 8.1 Roles

```php
'customer', 'driver', 'finance', 'admin', 'owner'
```

### 8.2 Permissions (Daftar Utama)

```
Booking:
  view-any-booking, view-own-booking, create-booking,
  approve-booking, cancel-booking, return-booking, assign-driver

Payment:
  view-any-payment, view-own-payment, confirm-payment, process-refund,
  view-revenue-report, export-financial-report

Vehicle:
  view-vehicle, create-vehicle, edit-vehicle, delete-vehicle,
  update-vehicle-status, schedule-maintenance, create-inspection

Driver:
  view-any-driver, create-driver, edit-driver, update-availability,
  view-own-schedule

Report:
  view-booking-report, view-revenue-report, view-vehicle-report,
  view-driver-report, view-kpi-dashboard, export-report

System:
  manage-users, manage-roles, manage-branches, manage-promos,
  view-activity-log
```

### 8.3 Checking Permission di Blade

```blade
@can('approve-booking')
    <button>Approve</button>
@endcan

@role('admin|owner')
    <a href="/admin">Admin Panel</a>
@endrole
```

---

## 9. Session Management

 | Setting | Value | Keterangan |
|---|---|---|
 | `SESSION_DRIVER` | `database` | Session disimpan di tabel `sessions` |
 | `SESSION_LIFETIME` | `120` | Expire setelah 120 menit tidak aktif |
 | `SESSION_SECURE_COOKIE` | `true` | HTTPS only di production |
 | `SESSION_HTTP_ONLY` | `true` | Tidak bisa diakses via JavaScript |
 | `SESSION_SAME_SITE` | `lax` | Proteksi CSRF lintas domain |

---

## 10. Security Controls

 | Kontrol | Implementasi |
|---|---|
 | CSRF Protection | Laravel default — semua POST form pakai `@csrf` |
 | Password Hashing | `bcrypt` via `Hash::make()` |
 | Brute Force | Fortify throttle: 5 attempt / 60 detik lockout |
 | Remember Me | Secure token di DB — di-rotate saat login ulang |
 | Session Fixation | Laravel regenerate session ID saat login |
 | Password Confirmation | Middleware `password.confirm` untuk aksi sensitif |

---

Versi: 1.0.0 | Tanggal: 2026-05-14
