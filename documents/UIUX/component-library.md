# Component Library — Siliwangi Rental

**Nama File:** `component-library.md`  
**Lokasi:** `documents/UIUX/`  
**Tujuan:** Daftar lengkap Blade components, Livewire components, dan Anonymous Components yang digunakan.

---

## 1. Blade Anonymous Components

**Lokasi:** `resources/views/components/`

 | Komponen | File | Deskripsi |
|---|---|---|
 | `<x-app-layout>` | `app-layout.blade.php` | Layout wrapper halaman customer |
 | `<x-guest-layout>` | `guest-layout.blade.php` | Layout untuk auth pages |
 | `<x-nav-link>` | `nav-link.blade.php` | Navbar link |
 | `<x-dropdown>` | `dropdown.blade.php` | Dropdown menu Alpine.js |
 | `<x-dropdown-link>` | `dropdown-link.blade.php` | Item dalam dropdown |
 | `<x-input-label>` | `input-label.blade.php` | Label form |
 | `<x-text-input>` | `text-input.blade.php` | Input text standar |
 | `<x-input-error>` | `input-error.blade.php` | Pesan error validasi |
 | `<x-primary-button>` | `primary-button.blade.php` | Button utama |
 | `<x-secondary-button>` | `secondary-button.blade.php` | Button sekunder |
 | `<x-danger-button>` | `danger-button.blade.php` | Button berbahaya |
 | `<x-modal>` | `modal.blade.php` | Modal dialog Alpine.js |
 | `<x-action-message>` | `action-message.blade.php` | Flash message |

---

## 2. Livewire Components

**Lokasi:** `app/Livewire/`

 | Kelas | View | Fungsi |
|---|---|---|
 | `CarCatalog` | `livewire/car-catalog.blade.php` | Real-time filter & search catalog |
 | `CarDetail` | `livewire/car-detail.blade.php` | Detail kendaraan + availability |
 | `Checkout` | `livewire/checkout.blade.php` | 5-step booking form |
 | `CustomerProfileEditor` | `livewire/customer-profile-editor.blade.php` | Edit profil customer |

---

## 3. Email Views

**Lokasi:** `resources/views/emails/`

 | View | Digunakan Untuk |
|---|---|
 | `emails/booking/created.blade.php` | Email booking baru |
 | `emails/booking/confirmed.blade.php` | Email booking dikonfirmasi |
 | `emails/booking/cancelled.blade.php` | Email booking dibatalkan |
 | `emails/booking/completed.blade.php` | Email booking selesai |
 | `emails/booking/expired.blade.php` | Email booking expired |
 | `emails/booking/reminder.blade.php` | Email reminder H-1 |
 | `emails/payment/success.blade.php` | Email DP berhasil |
 | `emails/fine/created.blade.php` | Email tagihan denda |

---

## 4. PDF Views

**Lokasi:** `resources/views/pdf/`

 | View | Digunakan Untuk |
|---|---|
 | `pdf/invoice.blade.php` | Invoice PDF per booking |
 | `pdf/report-booking.blade.php` | Laporan booking PDF |
 | `pdf/report-revenue.blade.php` | Laporan keuangan PDF |

---

## 5. Filament Widgets

**Lokasi:** `app/Filament/Widgets/`

 | Widget | Tipe | Fungsi |
|---|---|---|
 | `BookingStatsWidget` | StatsOverviewWidget | Statistik booking hari ini |
 | `RevenueStatsWidget` | StatsOverviewWidget | Revenue bulan ini |
 | `VehicleStatsWidget` | StatsOverviewWidget | Status armada |
 | `BookingTrendChart` | ChartWidget (Line) | Trend booking mingguan |
 | `RevenueTrendChart` | ChartWidget (Bar) | Trend revenue bulanan |
 | `LatestBookingsWidget` | TableWidget | Booking terbaru |

---

Versi: 1.0.0 | Tanggal: 2026-05-14
