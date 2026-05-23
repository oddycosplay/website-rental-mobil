# Roadmap & Sprint Planning — Siliwangi Rental

**Nama File:** `roadmap.md`  
**Lokasi:** `documents/DEV/`  
**Tujuan:** Roadmap pengembangan detail dan sprint planning sistem Siliwangi Rental.

---

## Timeline Overview

 | Sprint | Durasi | Goal |
|---|---|---|
 | Sprint 1 | 2 Minggu | Foundation, Database, Auth |
 | Sprint 2 | 2 Minggu | Customer Portal, Catalog |
 | Sprint 3 | 2 Minggu | Booking System |
 | Sprint 4 | 2 Minggu | Payment Integration |
 | Sprint 5 | 2 Minggu | Driver, Vehicle, Notification |
 | Sprint 6 | 2 Minggu | Report, Analytics, Multi-Branch |
 | Sprint 7 | 2 Minggu | QA & Hardening |
 | Sprint 8 | 1 Minggu | Deployment & Go-Live |

Total: **~15 minggu**

---

## Sprint 6 (Current) — Reports & Analytics

**Goal:** Laporan keuangan, booking, kendaraan, dan dashboard analytics.

### Backlog Sprint 6

 | Task | Priority | Status | Assignee |
|---|---|---|---|
 | Dashboard analytics widgets (Filament) | HIGH | 🔄 | BE |
 | Laporan booking (filter + export PDF) | HIGH | 🔄 | BE |
 | Laporan revenue & expense | HIGH | ⏳ | BE |
 | Export Excel (maatwebsite/excel) | HIGH | ⏳ | BE |
 | Laporan KPI dashboard | MEDIUM | ⏳ | BE |
 | Multi-branch statistics | MEDIUM | ⏳ | BE |
 | Laporan kendaraan & driver | MEDIUM | ⏳ | BE |

---

## Sprint 7 (Next) — QA & Hardening

 | Task | Priority |
|---|---|
 | Unit test — Models & Services | HIGH |
 | Feature test — Booking + Payment | HIGH |
 | Security audit | HIGH |
 | Performance optimization (N+1, cache) | MEDIUM |
 | Cross-browser testing | MEDIUM |
 | UAT dengan stakeholder | HIGH |
 | Bug fixing | HIGH |

---

## Sprint 8 (Final) — Deployment

 | Task | Priority |
|---|---|
 | VPS setup (Ubuntu 22.04 + Nginx) | HIGH |
 | SSL certificate (Let's Encrypt) | HIGH |
 | PHP 8.2 + MySQL 8 production install | HIGH |
 | `.env` production configuration | HIGH |
 | Database migration production | HIGH |
 | Seed data awal (admin, branches, cars) | HIGH |
 | Queue worker setup (supervisor) | HIGH |
 | Cron scheduler setup | HIGH |
 | Backup automation | HIGH |
 | DNS pointing ke server | HIGH |
 | Go-live | HIGH |

---

Versi: 1.0.0 | Tanggal: 2026-05-14
