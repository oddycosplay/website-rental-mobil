# Siliwangi Rental — Enterprise Car Rental System

Siliwangi Rental adalah platform manajemen rental mobil berbasis Laravel yang dirancang untuk memberikan pengalaman booking yang seamless bagi pelanggan dan manajemen armada yang efisien bagi admin.

## 🚀 Fitur Utama

- **Premium UI/UX:** Tampilan modern dengan Glassmorphism dan Responsive Design.
- **5-Step Checkout:** Alur pemesanan terstruktur menggunakan Livewire.
- **Midtrans Integration:** Pembayaran DP dan Pelunasan otomatis.
- **Notification System:** Notifikasi WhatsApp (Queue) dan Ikon Lonceng (In-App).
- **Dynamic Invoice:** Perhitungan biaya admin, operasional, pajak (PPN 12%), dan promo secara otomatis.
- **Admin Dashboard:** Manajemen armada, driver, booking, dan laporan keuangan via Filament.

## 🛠️ Tech Stack

- **Framework:** Laravel 12
- **Frontend:** Livewire, Alpine.js, Tailwind CSS
- **Database:** MySQL
- **Integrasi:** Midtrans (Payment), WhatsApp API (Notification)
- **Reporting:** DomPDF (Invoice)

## 📦 Instalasi

1. **Clone Repositori:**

   ```bash
   git clone [url-repo]
   cd rental_project
   ```

2. **Install Dependencies:**

   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment:**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database & Seeder:**

   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Run Development:**

   ```bash
   php artisan serve
   npm run dev
   ```

## 📑 Dokumentasi QA

Semua dokumentasi pengujian tersedia di folder `documents/QA/`:

- `test-case.md`: Daftar skenario pengujian dan status.
- `testing.md`: Tabel pengujian Blackbox dan Screenshot.
- `qa-checklist.md`: Daftar periksa kesiapan rilis.

---

**Developed by Antigravity (Advanced Agentic Coding)**
