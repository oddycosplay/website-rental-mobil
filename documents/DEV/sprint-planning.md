# Sprint Planning — Siliwangi Rental

**Nama File:** `sprint-planning.md`  
**Lokasi:** `documents/DEV/`  
**Tujuan:** Detail sprint planning per iterasi pengembangan Siliwangi Rental.

---

## Sprint 6 (Active) — Reports & Multi-Branch

**Periode:** 2 Minggu | **Status:** 🔄 In Progress

### Sprint Goal

Mengimplementasikan modul laporan keuangan, booking, dan analytics dashboard yang dapat diexport ke PDF dan Excel.

### User Stories

 | ID | Story | Points | Status |
|---|---|---|---|
 | US-61 | Sebagai admin, saya ingin melihat dashboard analytics real-time | 5 | 🔄 |
 | US-62 | Sebagai finance, saya ingin export laporan revenue ke Excel | 8 | ⏳ |
 | US-63 | Sebagai owner, saya ingin melihat laporan KPI per cabang | 5 | ⏳ |
 | US-64 | Sebagai admin, saya ingin export laporan booking ke PDF | 5 | 🔄 |
 | US-65 | Sebagai admin, saya ingin melihat statistik per cabang | 3 | ⏳ |

### Sprint Backlog

 | Task | Story | Assignee | Est |
|---|---|---|---|
 | Buat Filament StatsOverview Widget | US-61 | BE | 4j |
 | Buat Line Chart widget booking trend | US-61 | BE | 3j |
 | Buat Bar Chart widget revenue | US-61 | BE | 3j |
 | Install maatwebsite/excel | US-62 | BE | 1j |
 | Buat ReportBookingExport class | US-62 | BE | 4j |
 | Buat ReportRevenueExport class | US-62 | BE | 4j |
 | Buat Blade view laporan booking PDF | US-64 | FE | 4j |
 | Buat ReportController | US-64 | BE | 3j |
 | Buat branch statistics widget | US-65 | BE | 3j |
 | Testing laporan PDF & Excel | — | QA | 4j |

---

## Sprint 7 — QA & Hardening

**Periode:** 2 Minggu | **Status:** ⏳ Planned

### Sprint Goal

Memastikan kualitas sistem melalui testing menyeluruh, security audit, dan performance optimization.

### Sprint Backlog

 | Task | Priority | Assignee |
|---|---|---|
 | Unit test Models (Booking, Car, Payment) | HIGH | BE |
 | Feature test Auth flow | HIGH | BE |
 | Feature test Booking flow end-to-end | HIGH | BE |
 | Feature test Midtrans webhook | HIGH | BE |
 | Security audit (OWASP Top 10) | HIGH | BE |
 | Performance: N+1 check & eager loading audit | HIGH | BE |
 | Cache implementation (car types, stats) | MEDIUM | BE |
 | Cross-browser testing | MEDIUM | FE |
 | Mobile responsiveness check | MEDIUM | FE |
 | UAT dengan Owner | HIGH | PM |
 | UAT dengan Admin | HIGH | PM |
 | UAT dengan Finance | HIGH | PM |
 | Bug fixing dari UAT | HIGH | ALL |

---

## Sprint 8 — Deployment

**Periode:** 1 Minggu | **Status:** ⏳ Planned

### Sprint Goal

Deploy sistem ke production server dan pastikan semua berjalan stabil.

### Sprint Backlog

 | Task | Priority | Assignee |
|---|---|---|
 | Setup VPS (Ubuntu 22.04) | HIGH | DevOps |
 | Install Nginx + PHP 8.2 + MySQL 8 | HIGH | DevOps |
 | SSL Certificate (Let's Encrypt) | HIGH | DevOps |
 | Configure .env production | HIGH | DevOps |
 | Deploy aplikasi ke server | HIGH | DevOps |
 | Migrate & seed database production | HIGH | DevOps |
 | Setup supervisor queue worker | HIGH | DevOps |
 | Setup cron scheduler | HIGH | DevOps |
 | Setup backup automation | HIGH | DevOps |
 | Smoke testing production | HIGH | QA |
 | DNS configuration | HIGH | DevOps |
 | Go-live announcement | HIGH | PM |

---

**Legend:** 🔄 In Progress | ⏳ Planned | ✅ Done | ❌ Blocked

---

Versi: 1.0.0 | Tanggal: 2026-05-14
