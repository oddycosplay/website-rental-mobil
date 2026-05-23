# Progress & Roadmap — Siliwangi Rental

**Nama File:** `progress.md`  
**Lokasi:** `documents/BRD/`  
**Tujuan:** Mendokumentasikan roadmap pengembangan, sprint planning, milestone, dan development tracker.

---

## Metadata Dokumen

 | Atribut | Detail |
|---|---|
 | Nama Project | Siliwangi Rental |
 | Versi | 1.0.0 |
 | Tanggal | 2026-05-14 |

---

## 1. Project Milestones

 | Milestone | Target | Status |
|---|---|---|
 | M1 — Project Setup & Database | Sprint 1 | ✅ Selesai |
 | M2 — Auth & Customer Portal | Sprint 2 | ✅ Selesai |
 | M3 — Catalog & Car Management | Sprint 2-3 | ✅ Selesai |
 | M4 — Booking System | Sprint 3-4 | ✅ Selesai |
 | M5 — Payment Integration (Midtrans) | Sprint 4 | ✅ Selesai |

### Technical Notes (Sesi 14 Mei 2026)

- **PHP 8.2 Compatibility**: Locked dependencies in `composer.json` using `config.platform.php: 8.2.12`.
- **Filament v3 Revert**: All resources, pages, and widgets reverted to Filament v3 compatibility (static properties, Form/Infolist types).
- **Multi-Branch Readiness**: Platform is stabilized and ready for branch-id scoping.

 | M6 — Admin Panel (Filament v4) | Sprint 4-5 | ✅ Selesai |
 | M7 — Driver & Vehicle Management | Sprint 5 | ✅ Selesai |
 | M8 — Notification System | Sprint 5-6 | ✅ Selesai |
 | M9 — Report & Analytics | Sprint 6 | ✅ Selesai |
 | M10 — Multi-Branch | Sprint 6-7 | ✅ Selesai |
 | M11 — QA & Testing | Sprint 7-8 | ⏳ Planned |
 | M12 — Deployment & Go-Live | Sprint 8 | ⏳ Planned |

---

## 2. Sprint Planning

### Sprint 1 — Foundation (2 Minggu)

**Goal:** Setup project, database, dan environment dasar.

 | Task | Status |
|---|---|
 | Setup Laravel 12 project | ✅ |
 | Konfigurasi Tailwind CSS + Alpine.js | ✅ |
 | Install Filament v4 admin panel | ✅ |
 | Install Spatie Permission | ✅ |
 | Buat migrasi database utama | ✅ |
 | Seed data awal (roles, users, cars) | ✅ |
 | Konfigurasi .env dan storage | ✅ |

### Sprint 2 — Auth & Customer Portal (2 Minggu)

**Goal:** Sistem autentikasi dan portal customer berfungsi penuh.

 | Task | Status |
|---|---|
 | Implementasi Auth (Login, Register) | ✅ |
 | Forgot password & reset password | ✅ |
 | Email verification | ✅ |
 | Guest checkout flow | ✅ |
 | Halaman profil customer | ✅ |
 | Riwayat booking customer | ✅ |

### Sprint 3 — Catalog & Car Features (2 Minggu)

**Goal:** Catalog kendaraan dengan filter, search, dan detail.

 | Task | Status |
|---|---|
 | Halaman catalog mobil | ✅ |
 | Filter by tipe, brand, harga, transmisi | ✅ |
 | Search kendaraan | ✅ |
 | Sort kendaraan | ✅ |
 | Detail halaman kendaraan | ✅ |
 | Featured car di homepage | ✅ |
 | Availability checking real-time | ✅ |
 | Car Management di Admin (Filament) | ✅ |

### Sprint 4 — Booking & Payment (2 Minggu)

**Goal:** Sistem booking dan pembayaran berfungsi end-to-end.

 | Task | Status |
|---|---|
 | Form booking 5-step (Livewire) | ✅ |
 | Upload KTP/SIM | ✅ |
 | Pilih driver | ✅ |
 | Integrasi Midtrans (DP) | ✅ |
 | Midtrans Webhook handler | ✅ |
 | Auto-expired booking (Scheduler) | ✅ |
 | Booking cancellation | ✅ |
 | Invoice PDF generation | ✅ |
 | Payment Management di Admin | ✅ |
 | Booking Management di Admin | ✅ |

### Sprint 5 — Driver, Maintenance & Inspection (2 Minggu)

**Goal:** Manajemen driver, maintenance, dan inspeksi kendaraan.

 | Task | Status |
|---|---|
 | Driver CRUD di Admin (Filament) | ✅ |
| Task | Status |
|---|---|
| Driver CRUD di Admin (Filament) | ✅ |
| Driver scheduling | ✅ |
| Driver availability status | ✅ |
| Maintenance scheduling & tracking | ✅ |
| Car inspection (pre/post rental) | ✅ |
| Vehicle return management | ✅ |
| Denda keterlambatan | ✅ |
| WhatsApp Notification (queued) | ✅ |
| Email Notification | ✅ |

### Sprint 6 — Reports, Analytics & Multi-Branch (2 Minggu)

**Goal:** Laporan lengkap dan dukungan multi-cabang.

| Task | Status |
|---|---|
| Dashboard analytics widget | ✅ |
| Laporan booking | ✅ |
| Laporan revenue & laba | ✅ |
| Laporan kendaraan | ✅ |
| Laporan driver | ✅ |
| Laporan denda | ✅ |
| Export PDF (barryvdh/dompdf) | ✅ |
| Export Excel (maatwebsite/excel) | ✅ |
| Multi-branch management | ✅ |
| Branch statistics | ✅ |

### Sprint 7 — Quality Assurance & Bug Fixing (1 Minggu)

**Goal:** Memastikan sistem stabil dan bebas bug kritis.

| Task | Status |
|---|---|
| Bug fixing & UI polish | ⏳ |
| Security hardening | ⏳ |
| User testing & feedback | ⏳ |
| Unit testing (Models, Services) | ⏳ |
| Feature testing (Booking, Payment flow) | ⏳ |
| Browser testing (Pest + Dusk) | ⏳ |
| Security audit | ⏳ |
| Performance optimization | ⏳ |
| Bug fixing dari QA | ⏳ |
| User Acceptance Testing (UAT) | ⏳ |

### Sprint 8 — Deployment & Go-Live (1 Minggu)

**Goal:** Deploy ke production dan monitoring.

 | Task | Status |
|---|---|
 | Setup VPS / cloud server | ⏳ |
 | Konfigurasi Nginx / Apache | ⏳ |
 | SSL certificate (Let's Encrypt) | ⏳ |
 | Deployment pipeline | ⏳ |
 | Backup automation | ⏳ |
 | Monitoring (uptime, error logging) | ⏳ |
 | Go-live | ⏳ |

---

## 3. Development Tracker

 | Modul | Backend | Frontend | Admin Panel | Testing |
|---|---|---|---|---|
 | Auth | ✅ | ✅ | ✅ | ⏳ |
 | Catalog | ✅ | ✅ | ✅ | ⏳ |
 | Booking | ✅ | ✅ | ✅ | ⏳ |
 | Payment | ✅ | ✅ | ✅ | ⏳ |
 | Driver | ✅ | ✅ | ✅ | ⏳ |
 | Vehicle | ✅ | ✅ | ✅ | ⏳ |
 | Inspection | ✅ | ⏳ | ✅ | ⏳ |
 | Maintenance | ✅ | ⏳ | ✅ | ⏳ |
 | Notification | ✅ | — | — | ⏳ |
 | Report | ✅ | — | ✅ | ⏳ |
 | Multi-Branch | ✅ | ✅ | ✅ | ⏳ |
 | Promo | ✅ | ✅ | ✅ | ⏳ |

**Legend:** ✅ Selesai | 🔄 In Progress | ⏳ Planned | — Tidak Relevan

---

Versi: 1.0.0 | Tanggal: 2026-05-14
