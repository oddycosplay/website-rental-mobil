# Class Diagram — Siliwangi Rental

**Nama File:** `class-diagram.md`  
**Lokasi:** `documents/UML/`  
**Tujuan:** Dokumentasi class diagram Eloquent Models dan Service Layer sistem Siliwangi Rental.

---

## 1. Class Diagram — Core Models

```mermaid
classDiagram
    class User {
        +bigint id
        +string name
        +string email
        +string password
        +string phone
        +boolean is_active
        +hasOne() Customer
        +hasOne() Driver
        +assignRole()
        +hasRole()
        +hasPermissionTo()
    }

    class Customer {
        +bigint id
        +bigint user_id
        +string nik
        +string address
        +date date_of_birth
        +string ktp_path
        +string sim_path
        +belongsTo() User
        +hasMany() Bookings
    }

    class Car {
        +bigint id
        +string name
        +string slug
        +string plate_number
        +decimal price_per_day
        +decimal price_per_month
        +decimal driver_price
        +enum status
        +boolean is_featured
        +boolean is_active
        +scopeAvailable()
        +scopeFeatured()
        +scopeActive()
        +belongsTo() CarBrand
        +belongsTo() CarType
        +belongsTo() Branch
        +hasMany() Bookings
        +hasMany() CarMaintenances
        +hasMany() CarInspections
    }

    class Booking {
        +bigint id
        +string booking_code
        +enum status
        +date start_date
        +date end_date
        +decimal total_amount
        +decimal dp_amount
        +decimal fine_amount
        +datetime payment_due_at
        +date actual_return_date
        +belongsTo() Car
        +belongsTo() Customer
        +belongsTo() Driver
        +belongsTo() Promo
        +hasMany() Payments
        +hasMany() CarInspections
        +hasOne() Review
        +isExpired()
        +calculateFine()
    }

    class Payment {
        +bigint id
        +string order_id
        +enum type
        +decimal amount
        +enum status
        +string midtrans_token
        +datetime paid_at
        +belongsTo() Booking
        +hasMany() PaymentLogs
        +isPaid()
    }

    class Driver {
        +bigint id
        +string name
        +string phone
        +string license_number
        +enum status
        +decimal rating
        +belongsTo() User
        +belongsTo() Branch
        +hasMany() DriverSchedules
        +isAvailable()
        +updateRating()
    }

    class MidtransService {
        +createSnapToken(Booking) string
        +handleWebhook(array payload) void
        +validateSignature(array data) bool
        +updatePaymentStatus(string orderId, string status) void
    }

    class WhatsAppService {
        +send(string phone, string message) void
        +sendBookingCreated(Booking) void
        +sendPaymentSuccess(Payment) void
        +sendBookingConfirmed(Booking) void
    }

    User "1" --> "0..1" Customer
    User "1" --> "0..1" Driver
    Car "1" --> "0..*" Booking
    Customer "1" --> "0..*" Booking
    Driver "0..1" --> "0..*" Booking
    Booking "1" --> "1..*" Payment
```

---

## 2. Service Layer Classes

 | Kelas | File | Tanggung Jawab |
|---|---|---|
 | `MidtransService` | `app/Services/MidtransService.php` | Midtrans API integration, token, webhook |
 | `WhatsAppService` | `app/Services/WhatsAppService.php` | Kirim pesan WhatsApp via API |
 | `BookingService` | Belum ditentukan pada requirement | Logika booking, kalkulasi harga |
 | `ReportService` | Belum ditentukan pada requirement | Generate laporan, aggregate data |
 | `InvoiceService` | Belum ditentukan pada requirement | Generate PDF invoice |

---

Versi: 1.0.0 | Tanggal: 2026-05-14
