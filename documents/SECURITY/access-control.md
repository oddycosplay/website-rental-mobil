# Access Control — Siliwangi Rental

**Nama File:** `access-control.md`  
**Lokasi:** `documents/SECURITY/`  
**Tujuan:** Dokumentasi implementasi access control sistem Siliwangi Rental.

---

## 1. Role-Based Access Control (RBAC)

Sistem menggunakan **Spatie Laravel Permission** untuk RBAC.

### Roles

```
owner > admin > finance > driver > customer
```

### Middleware Guard

```php
// Admin panel — role check
Route::middleware(['auth', 'role:admin|owner|finance'])->group(...);

// Finance only
Route::middleware(['auth', 'role:finance|owner'])->group(...);

// Customer
Route::middleware(['auth', 'verified'])->group(...);
```

---

## 2. Policy Implementation

```php
// app/Policies/BookingPolicy.php

class BookingPolicy
{
    // Customer hanya bisa lihat booking sendiri
    public function view(User $user, Booking $booking): bool
    {
        if ($user->hasRole(['admin', 'owner', 'finance'])) {
            return true;
        }
        return $booking->user_id === $user->id;
    }

    // Hanya admin yang bisa approve
    public function approve(User $user): bool
    {
        return $user->hasRole('admin');
    }

    // Customer bisa cancel booking sendiri yang masih pending
    public function cancel(User $user, Booking $booking): bool
    {
        if ($user->hasRole('admin')) return true;
        return $booking->user_id === $user->id &&
               in_array($booking->status, ['pending', 'paid']);
    }
}
```

---

## 3. Route Protection

```php
// routes/web.php

// Public routes — tanpa auth
Route::get('/', [HomeController::class, 'index']);
Route::get('/catalog', CarCatalog::class);
Route::get('/cars/{car:slug}', CarDetail::class);

// Auth + verified routes — customer
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/checkout/{car:slug}', [CheckoutController::class, 'index']);
    Route::get('/customer/bookings', [BookingController::class, 'index']);
    Route::get('/invoice/{booking}', [InvoiceController::class, 'show']);
});

// Midtrans webhook — public dengan signature validation
Route::post('/midtrans/callback', [MidtransController::class, 'handleCallback']);
```

---

## 4. Filament Admin Auth

```php
// app/Providers/Filament/AdminPanelProvider.php

public function panel(Panel $panel): Panel
{
    return $panel
        ->authGuard('web')
        ->login()
        ->authMiddleware([
            Authenticate::class,
        ]);
}
```

Hanya user dengan `is_admin_panel = true` atau role admin/owner/finance yang bisa akses `/admin`.

---

## 5. File Access Control

```php
// Serve private files (KTP, SIM) — hanya admin
Route::get('/private/{path}', function (string $path) {
    abort_unless(auth()->user()?->hasRole(['admin', 'owner']), 403);
    return response()->file(storage_path('app/private/' . $path));
})->where('path', '.*')->middleware('auth');
```

---

Versi: 1.0.0 | Tanggal: 2026-05-14
