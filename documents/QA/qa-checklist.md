# QA Checklist — Siliwangi Rental

**Nama File:** `qa-checklist.md`  
**Lokasi:** `documents/QA/`  
**Tujuan:** Checklist QA komprehensif sebelum release ke production.

---

## 1. Functional Checklist

### Authentication

- [x] Register customer — semua validasi bekerja
- [x] Login customer — kredensial valid dan invalid
- [x] Email verification — link aktif dan expired
- [x] Forgot & reset password — token valid dan expired
- [x] Guest redirection — tamu diarahkan ke login/registrasi atau WhatsApp flow saat booking
- [x] Logout — session dihapus

### Catalog

- [x] Halaman catalog load < 3 detik
- [x] Filter by tipe, brand, transmisi, kategori bekerja (Pribadi/Perusahaan)
- [x] Search realtime tanpa refresh halaman
- [x] Sort harga, nama, terbaru bekerja
- [x] Pagination berfungsi
- [x] Featured car muncul di homepage
- [x] Availability badge tampil di card

### Booking

- [x] 5-step checkout flow berjalan tanpa error (Wizard navigation)
- [x] Kalkulasi harga real-time di tiap step (Subtotal, Tax, Promo, Driver Fee)
- [x] Upload KTP & SIM tersimpan di storage (Private folder)
- [x] Promo code valid diterapkan
- [x] Promo code invalid menampilkan error
- [x] Booking code ter-generate format SR-YYYYMMDD-XXXX
- [ ] Auto-expire scheduler berjalan (test manual - background process)
- [x] Cancellation berfungsi untuk status yang diizinkan

### Payment

- [x] Midtrans Snap UI muncul saat checkout
- [x] Webhook settlement memperbarui status secara otomatis
- [x] Webhook deny/expire memperbarui status secara otomatis
- [x] Signature validation berfungsi (Security check)
- [x] Invoice PDF bisa diunduh setelah completed (Dynamic duration & fee calculations)
- [ ] Denda dikalkulasi otomatis dan dibayar via Midtrans

### Admin Panel

- [x] Dashboard menampilkan stats real-time (Revenue, Bookings, Fleet)
- [x] Approval booking bekerja (Status change logic)
- [x] Rejection dengan alasan bekerja
- [x] Return processing bekerja (Vehicle inspection integration)
- [x] CRUD semua modul berfungsi (Filament v4 standard)
- [x] Role akses: admin, finance, owner sesuai hak akses (Spatie)

### Notification

- [x] WhatsApp Queue — Notifikasi masuk ke antrean dan terkirim otomatis
- [x] WhatsApp terkirim saat booking baru, DP berhasil, dan status Confirmed
- [x] Real-time Bell — Ikon Lonceng memantulkan notifikasi status tanpa refresh
- [ ] Email reminder H-1 terkirim (test scheduler)

---

## 2. Non-Functional Checklist

### Performance

- [x] Homepage load < 3 detik (mobile + desktop)
- [x] Catalog load < 3 detik
- [x] API response < 500ms
- [x] PDF invoice generate < 5 detik

### Security

- [x] CSRF protection aktif di semua form
- [x] SQL injection test — semua query via Eloquent
- [x] XSS test — semua output via Blade (auto-escape)
- [x] Brute force throttling aktif
- [x] File upload hanya terima tipe yang diizinkan (Validation rules)
- [x] Private files (KTP, SIM) tidak bisa diakses publik (Storage protection)
- [x] .env tidak ter-expose di production

### Responsiveness

- [x] Mobile (375px) — semua halaman responsif
- [x] Tablet (768px) — layout grid benar
- [x] Desktop (1280px) — full layout
- [x] Navbar mobile hamburger berfungsi

### Browser Compatibility

- [x] Chrome (latest) ✅
- [x] Firefox (latest) ✅
- [ ] Safari (latest)
- [x] Edge (latest) ✅

---

## 3. Pre-Deployment Checklist

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` ter-generate
- [ ] Midtrans key production ter-set
- [ ] WhatsApp API key production ter-set
- [ ] Mail konfigurasi production ter-set
- [x] `php artisan config:cache`
- [x] `php artisan route:cache`
- [x] `php artisan view:cache`
- [x] `npm run build` — assets production
- [ ] Cron scheduler terdaftar di server
- [ ] Queue worker berjalan sebagai service (Supervisor)
- [ ] SSL certificate aktif
- [ ] Backup database ter-setup

---

Versi: 1.2.0 | Tanggal: 2026-05-14
