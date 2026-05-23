# API Specification — Siliwangi Rental

**Nama File:** `api-specification.md`  
**Lokasi:** `documents/SRS/`  
**Tujuan:** Dokumentasi seluruh API endpoint sistem Siliwangi Rental.

---

## Metadata Dokumen

 | Atribut | Detail |
|---|---|
 | Nama Project | Siliwangi Rental |
 | Base URL | `https://siliwangirental.com/api` |
 | Auth | Laravel Sanctum (Bearer Token) |
 | Format | JSON |
 | Versi | v1 |
 | Tanggal | 2026-05-14 |

---

## 1. Authentication

### POST /api/auth/login

```
Body:
  { "email": "string", "password": "string" }

Response 200:
  { "token": "string", "user": { id, name, email, role } }

Response 422:
  { "message": "Email atau password salah." }
```

### POST /api/auth/logout

```
Header: Authorization: Bearer {token}

Response 200:
  { "message": "Logout berhasil." }
```

---

## 2. Cars (Public)

### GET /api/cars

```
Query Params:
  - search (string, optional)
  - type_id (integer, optional)
  - brand_id (integer, optional)
  - transmission (manual|automatic, optional)
  - category (Pribadi|Perusahaan, optional)
  - min_price (integer, optional)
  - max_price (integer, optional)
  - sort_by (price_asc|price_desc|newest|name_asc, optional)
  - per_page (integer, default:12)

Response 200:
  {
    "data": [
      {
        "id": 1,
        "name": "Toyota Avanza 2023",
        "slug": "toyota-avanza-2023",
        "brand": "Toyota",
        "type": "MPV",
        "transmission": "automatic",
        "capacity": 7,
        "price_per_day": 350000,
        "status": "available",
        "image": "url",
        "is_featured": true
      }
    ],
    "meta": { "current_page": 1, "last_page": 3, "total": 30 }
  }
```

### GET /api/cars/{slug}

```
Response 200:
  {
    "id": 1,
    "name": "Toyota Avanza 2023",
    "slug": "toyota-avanza-2023",
    "brand": { "id": 1, "name": "Toyota" },
    "type": { "id": 2, "name": "MPV" },
    "year": 2023,
    "color": "Silver",
    "transmission": "automatic",
    "fuel": "bensin",
    "capacity": 7,
    "mileage": 15000,
    "category": "Pribadi",
    "price_per_day": 350000,
    "price_per_month": 8000000,
    "driver_price": 150000,
    "description": "...",
    "features": ["AC", "Musik", "GPS"],
    "status": "available",
    "image": "url"
  }

Response 404:
  { "message": "Kendaraan tidak ditemukan." }
```

### GET /api/cars/{id}/availability

```
Query Params:
  - start_date (required|date)
  - end_date (required|date)

Response 200:
  { "available": true, "message": "Kendaraan tersedia." }
  { "available": false, "message": "Kendaraan tidak tersedia pada periode ini." }
```

---

## 3. Bookings (Auth Required)

### GET /api/bookings

```
Header: Authorization: Bearer {token}

Response 200:
  {
    "data": [
      {
        "id": 1,
        "booking_code": "SR-20260514-0001",
        "car": { "name": "Toyota Avanza", "image": "url" },
        "start_date": "2026-05-20",
        "end_date": "2026-05-25",
        "duration": 5,
        "total_amount": 1750000,
        "status": "confirmed",
        "payment_status": "partial"
      }
    ]
  }
```

### GET /api/bookings/{id}

```
Response 200:
  {
    "id": 1,
    "booking_code": "SR-20260514-0001",
    "car": { ... },
    "driver": { ... } | null,
    "start_date": "2026-05-20",
    "end_date": "2026-05-25",
    "duration": 5,
    "booking_category": "harian",
    "with_driver": true,
    "subtotal": 1750000,
    "driver_fee": 750000,
    "discount_amount": 0,
    "total_amount": 2500000,
    "dp_amount": 750000,
    "remaining_amount": 1750000,
    "fine_amount": 0,
    "status": "confirmed",
    "payments": [ ... ]
  }

Response 403:
  { "message": "Anda tidak memiliki akses ke booking ini." }
```

---

## 4. Payments (Auth Required)

### GET /api/payments/{booking_id}/snap-token

```
Response 200:
  { "snap_token": "string", "redirect_url": "string" }

Response 422:
  { "message": "Booking tidak dalam status yang valid untuk pembayaran." }
```

---

## 5. Midtrans Webhook (Public — Signature Required)

### POST /midtrans/callback

```
Body (dikirim oleh Midtrans):
  {
    "order_id": "SR-20260514-0001-DP",
    "transaction_status": "settlement|pending|deny|expire|cancel",
    "payment_type": "bank_transfer|qris|credit_card",
    "gross_amount": "750000.00",
    "signature_key": "...",
    "transaction_time": "2026-05-14 10:30:00"
  }

Validasi:
  - Signature: SHA512(order_id + status_code + gross_amount + server_key)

Proses:
  - settlement → Update payment status, update booking status
  - expire/cancel/deny → Update payment status FAILED/EXPIRED

Response 200:
  { "status": "ok" }
```

---

## 6. Profile (Auth Required)

### GET /api/profile

```
Response 200:
  {
    "id": 1,
    "name": "Budi Santoso",
    "email": "budi@email.com",
    "phone": "08123456789",
    "customer": {
      "nik": "3201...",
      "address": "Jl. ...",
      "date_of_birth": "1990-01-01"
    }
  }
```

### PUT /api/profile

```
Body: { name, phone, address, date_of_birth }
Response 200: { "message": "Profil berhasil diperbarui.", "user": { ... } }
```

---

## 7. Error Responses

 | HTTP Code | Kondisi |
|---|---|
 | 200 | Success |
 | 201 | Created |
 | 400 | Bad Request |
 | 401 | Unauthenticated |
 | 403 | Unauthorized |
 | 404 | Not Found |
 | 422 | Validation Error |
 | 429 | Too Many Requests |
 | 500 | Server Error |

```json
// Contoh error 422:
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["Email sudah terdaftar."],
    "password": ["Password minimal 8 karakter."]
  }
}
```

---

Versi: 1.0.0 | Tanggal: 2026-05-14
