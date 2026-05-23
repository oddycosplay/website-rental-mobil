# Coding Standard — Siliwangi Rental

**Nama File:** `coding-standard.md`  
**Lokasi:** `documents/DEV/`  
**Tujuan:** Panduan coding standard Laravel yang wajib diikuti seluruh tim developer.

---

## 1. PHP & Laravel Standards

- Ikuti **PSR-12** untuk formatting kode PHP.
- Gunakan **Laravel Pint** untuk auto-formatting: `./vendor/bin/pint`.
- Semua class menggunakan **PascalCase**: `BookingService`, `CarType`.
- Method menggunakan **camelCase**: `calculateFine()`, `sendNotification()`.
- Variable menggunakan **camelCase**: `$totalAmount`, `$bookingCode`.
- Konstanta menggunakan **UPPER_SNAKE_CASE**: `STATUS_PENDING`.

---

## 2. Naming Conventions

 | Tipe | Convention | Contoh |
|---|---|---|
 | Model | PascalCase Singular | `Booking`, `Car`, `Driver` |
 | Controller | PascalCase + Controller | `BookingController` |
 | Service | PascalCase + Service | `MidtransService` |
 | Job | PascalCase + Job | `SendWhatsAppJob` |
 | Migration | snake_case timestamp | `create_bookings_table` |
 | Route name | snake_case dot notation | `bookings.show`, `cars.index` |
 | View file | kebab-case | `booking-detail.blade.php` |
 | Blade component | kebab-case | `<x-car-card />` |

---

## 3. Model Conventions

```php
class Booking extends Model
{
    use HasFactory, SoftDeletes;

    // Mass assignment
    protected $fillable = ['booking_code', 'status', ...];
    protected $guarded = [];  // atau $fillable

    // Casts
    protected $casts = [
        'start_date' => 'date',
        'total_amount' => 'decimal:2',
        'with_driver' => 'boolean',
    ];

    // Constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';

    // Scopes — prefix 'scope'
    public function scopePending($query) { ... }
    public function scopeActive($query) { ... }

    // Relations — return type hint
    public function car(): BelongsTo { ... }
    public function payments(): HasMany { ... }
}
```

---

## 4. Controller Conventions

```php
class BookingController extends Controller
{
    // Satu action per method — resource-style
    public function index() {}   // List
    public function show() {}    // Detail
    public function store() {}   // Create
    public function update() {}  // Edit
    public function destroy() {} // Delete

    // Tidak ada logika bisnis di controller
    // Delegasikan ke Service atau Model
}
```

---

## 5. Database Conventions

```php
// Selalu eager load — hindari N+1
$bookings = Booking::with(['car', 'customer', 'payments'])->get();

// Gunakan exists() bukan count() > 0
if (Booking::where('car_id', $id)->exists()) { ... }

// Gunakan chunking untuk data besar
Booking::chunk(100, function ($bookings) { ... });
```

---

## 6. Security Rules

```php
// WAJIB: Selalu validasi input
$request->validate([
    'email' => 'required|email|max:255',
    'amount' => 'required|numeric|min:0',
]);

// WAJIB: Gunakan policy untuk otorisasi
$this->authorize('update', $booking);

// WAJIB: Escape output di Blade — pakai {{ }} bukan {!! !!}
{{ $booking->notes }}  // AMAN
{!! $booking->notes !!}  // BAHAYA — hanya untuk trusted HTML
```

---

## 7. Blade Conventions

```blade
{{-- Komentar menggunakan Blade comment --}}
{{-- Bukan HTML comment <!-- --> --}}

{{-- Variable --}}
{{ $booking->status }}

{{-- Condition --}}
@if ($booking->status === 'confirmed')
    <span>Terkonfirmasi</span>
@endif

{{-- Komponen --}}
<x-primary-button>Booking Sekarang</x-primary-button>

{{-- Slot --}}
<x-modal>
    <x-slot:title>Konfirmasi</x-slot:title>
    Apakah Anda yakin?
</x-modal>
```

---

Versi: 1.0.0 | Tanggal: 2026-05-14
