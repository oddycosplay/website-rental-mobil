# Laravel Project Structure — Siliwangi Rental

**Nama File:** `laravel-structure.md`  
**Lokasi:** `documents/DEV/`  
**Tujuan:** Daftar lengkap file Laravel project enterprise Siliwangi Rental.

---

## Metadata Dokumen

 | Atribut | Detail |
|---|---|
 | Framework | Laravel 12 |
 | Versi | 1.0.0 |
 | Tanggal | 2026-05-14 |

---

## 1. Models

```
app/Models/
├── Booking.php
├── Branch.php
├── Car.php
├── CarBrand.php
├── CarInspection.php
├── CarLocation.php
├── CarMaintenance.php
├── CarType.php
├── Customer.php
├── Driver.php
├── DriverSchedule.php
├── Expense.php
├── ExpenseCategory.php
├── Payment.php
├── PaymentLog.php
├── Promo.php
├── Review.php
└── User.php
```

---

## 2. Controllers

```
app/Http/Controllers/
├── Controller.php
├── MidtransController.php       (Webhook handler)
├── ProfileController.php        (Customer profile)
├── SitemapController.php        (SEO sitemap)
│
├── Auth/
│   ├── AuthenticatedSessionController.php
│   ├── ConfirmablePasswordController.php
│   ├── EmailVerificationNotificationController.php
│   ├── EmailVerificationPromptController.php
│   ├── NewPasswordController.php
│   ├── PasswordController.php
│   ├── PasswordResetLinkController.php
│   ├── RegisteredUserController.php
│   └── VerifyEmailController.php
│
├── Admin/
│   ├── BookingController.php    (Admin booking management)
│   ├── ReportController.php     (Generate laporan)
│   └── DashboardController.php  (Admin dashboard data)
│
└── Api/
    ├── CarController.php        (API cars)
    └── BookingController.php    (API bookings)
```

---

## 3. Livewire Components

```
app/Livewire/
├── CarCatalog.php
├── CarDetail.php
├── Checkout.php
└── CustomerProfileEditor.php
```

---

## 4. Services

```
app/Services/
├── MidtransService.php
├── WhatsAppService.php
├── BookingService.php           (Planned)
├── ReportService.php            (Planned)
└── InvoiceService.php           (Planned)
```

---

## 5. Jobs (Queue)

```
app/Jobs/
├── SendWhatsAppJob.php
├── SendEmailNotificationJob.php (Planned)
├── GenerateInvoiceJob.php       (Planned)
└── GenerateReportJob.php        (Planned)
```

---

## 6. Notifications

```
app/Notifications/
├── BookingCreatedNotification.php
├── BookingConfirmedNotification.php
├── BookingCancelledNotification.php
├── BookingCompletedNotification.php
├── BookingExpiredNotification.php
├── PaymentSuccessNotification.php
├── FineCreatedNotification.php
└── ReminderNotification.php
```

---

## 7. Policies

```
app/Policies/
├── BookingPolicy.php
├── CarPolicy.php
├── PaymentPolicy.php
└── DriverPolicy.php
```

---

## 8. Middleware

```
app/Http/Middleware/
├── Authenticate.php
├── RedirectIfAuthenticated.php
├── EnsureEmailIsVerified.php    (via Fortify)
└── HandleInertiaRequests.php    (jika digunakan)
```

---

## 9. Requests (Form Validation)

```
app/Http/Requests/
├── Auth/
│   ├── LoginRequest.php
│   └── RegisterRequest.php
├── Booking/
│   ├── StoreBookingRequest.php
│   └── UpdateBookingRequest.php
└── Profile/
    └── UpdateProfileRequest.php
```

---

## 10. Filament Resources

```
app/Filament/
├── Resources/
│   ├── Bookings/
│   │   ├── BookingResource.php
│   │   └── Pages/
│   │       ├── ListBookings.php
│   │       ├── CreateBooking.php
│   │       ├── EditBooking.php
│   │       └── ViewBooking.php
│   ├── Cars/
│   │   └── CarResource.php + Pages/
│   ├── Customers/
│   │   └── CustomerResource.php + Pages/
│   ├── Drivers/
│   │   └── DriverResource.php + Pages/
│   ├── Payments/
│   │   └── PaymentResource.php + Pages/
│   ├── Branches/
│   │   └── BranchResource.php + Pages/
│   ├── CarBrands/
│   │   └── CarBrandResource.php + Pages/
│   ├── CarTypes/
│   │   └── CarTypeResource.php + Pages/
│   ├── CarMaintenances/
│   │   └── CarMaintenanceResource.php + Pages/
│   ├── CarInspections/
│   │   └── CarInspectionResource.php + Pages/
│   ├── Expenses/
│   │   └── ExpenseResource.php + Pages/
│   ├── ExpenseCategories/
│   │   └── ExpenseCategoryResource.php + Pages/
│   ├── Users/
│   │   └── UserResource.php + Pages/
│   └── RoleResource.php
│
├── Pages/
│   └── Dashboard.php
│
└── Widgets/
    ├── BookingStatsWidget.php
    ├── RevenueStatsWidget.php
    ├── VehicleStatsWidget.php
    ├── BookingTrendChart.php
    ├── RevenueTrendChart.php
    └── LatestBookingsWidget.php
```

---

## 11. Routes

```
routes/
├── web.php         (Frontend + Customer routes)
├── auth.php        (Auth routes — Breeze/Fortify)
├── api.php         (API endpoints)
└── console.php     (Artisan commands schedule)
```

---

## 12. Views (Blade)

```
resources/views/
├── welcome.blade.php            (Homepage)
├── about.blade.php              (Tentang kami)
├── faq.blade.php                (FAQ)
├── contact.blade.php            (Kontak)
├── policy.blade.php             (Kebijakan privasi)
├── terms.blade.php              (Syarat & ketentuan)
├── dashboard.blade.php          (Customer dashboard)
├── invoice.blade.php            (Invoice viewer)
│
├── layouts/
│   ├── app.blade.php            (Master layout)
│   ├── guest.blade.php          (Auth layout)
│   └── navigation.blade.php     (Navbar)
│
├── auth/                        (Login, register, dll)
├── cars/                        (Car detail page)
├── checkout/                    (Checkout pages)
├── customer/                    (Customer portal)
├── profile/                     (Profile pages)
├── components/                  (Blade components)
├── livewire/                    (Livewire views)
├── emails/                      (Email templates)
├── pdf/                         (PDF templates)
└── filament/                    (Filament customizations)
```

---

## 13. Migrations

```
database/migrations/
├── 0001_01_01_000000_create_users_table.php
├── 0001_01_01_000001_create_cache_table.php
├── 0001_01_01_000002_create_jobs_table.php
├── 2026_05_03_210259_create_personal_access_tokens_table.php
├── 2026_05_05_000000_create_siliwangi_tables.php
├── 2026_05_05_154328_create_permission_tables.php
├── 2026_05_06_000001_add_nik_ktp_sim...php
├── 2026_05_06_000002_add_slug_to_car_types_table.php
├── 2026_05_06_000003_add_stock_to_cars_table.php
├── 2026_05_07_000001_create_finance_tables.php
├── 2026_05_07_000002_create_car_maintenances_table.php
├── 2026_05_07_000003_create_car_locations_table.php
├── 2026_05_07_120559_add_status_to_bookings_table.php
├── 2026_05_11_173200_create_car_inspections_table.php
├── 2026_05_12_080500_add_booking_category_to_bookings_table.php
├── 2026_05_12_110000_add_driver_price_and_call_status_to_cars_table.php
├── 2026_05_13_114000_add_category_to_cars_table.php
└── 2026_05_13_235953_add_mileage_to_cars_table.php
```

---

## 14. Seeders

```
database/seeders/
├── DatabaseSeeder.php           (Root seeder)
└── NewCarSeeder.php             (Kendaraan data)
```

---

## 15. Config Files

```
config/
├── app.php
├── auth.php
├── database.php
├── filament.php
├── mail.php
├── queue.php
├── services.php                 (Midtrans, WhatsApp keys)
└── rental.php                   (Custom config: dp_percentage, fine_rate)
```

---

Versi: 1.0.0 | Tanggal: 2026-05-14
