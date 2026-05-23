# Test Case — Siliwangi Rental

**Nama File:** `test-case.md`  
**Lokasi:** `documents/QA/`  
**Tujuan:** Dokumentasi test case detail untuk modul utama sistem Siliwangi Rental.

---

## 1. Test Case — Authentication

| ID | Skenario | Input | Expected Output | Status |
|:---|:---|:---|:---|:---:|
| TC-AUTH-01 | Register dengan data valid | nama, email, password valid | Akun terbuat, email verifikasi dikirim | ✅ |
| TC-AUTH-02 | Register dengan email duplikat | email yang sudah ada | Error: "Email sudah terdaftar" | ✅ |
| TC-AUTH-03 | Login dengan kredensial valid | email + password benar | Redirect ke dashboard | ✅ |
| TC-AUTH-04 | Login dengan password salah | email benar + password salah | Error: "Email atau password salah" | ✅ |
| TC-AUTH-05 | Login 5 kali gagal | 5x wrong password | Throttle 60 detik | ✅ |
| TC-AUTH-06 | Forgot password | email terdaftar | Link reset dikirim ke email | ✅ |
| TC-AUTH-07 | Reset password dengan token valid | token valid + password baru | Password berhasil diubah | ✅ |
| TC-AUTH-08 | Reset password dengan token expired | token > 60 menit | Error: "Token tidak valid" | ✅ |
| TC-AUTH-09 | Akses halaman auth saat sudah login | akses /login saat logged in | Redirect ke dashboard | ✅ |
| TC-AUTH-10 | Akses halaman customer tanpa login | akses /dashboard | Redirect ke /login | ✅ |

---

## 2. Test Case — Catalog

| ID | Skenario | Input | Expected Output | Status |
|:---|:---|:---|:---|:---:|
| TC-CAT-01 | Buka halaman catalog | GET /catalog | Menampilkan 12 kendaraan aktif | ✅ |
| TC-CAT-02 | Search kendaraan | keyword "avanza" | Menampilkan kendaraan yang mengandung "avanza" | ✅ |
| TC-CAT-03 | Filter by tipe | type = "suv" | Hanya kendaraan tipe SUV | ✅ |
| TC-CAT-04 | Filter by transmisi | transmission = "automatic" | Hanya kendaraan matic | ✅ |
| TC-CAT-05 | Filter by kategori | category = "Perusahaan" | Hanya kendaraan kategori Perusahaan | ✅ |
| TC-CAT-06 | Filter harga min-max | min=200000, max=500000 | Kendaraan dalam range harga | ✅ |
| TC-CAT-07 | Sort harga terendah | sortBy = price_asc | Kendaraan diurutkan harga naik | ✅ |
| TC-CAT-08 | Reset filter | klik "Reset" | Semua filter dikosongkan | ✅ |
| TC-CAT-09 | Pagination | page 2 | Menampilkan halaman 2 data | ✅ |
| TC-CAT-10 | Buka detail kendaraan | klik nama/kartu mobil | Redirect ke /cars/{slug} | ✅ |

---

## 3. Test Case — Booking

| ID | Skenario | Input | Expected Output | Status |
|:---|:---|:---|:---|:---:|
| TC-BOOK-01 | Booking kendaraan available (logged in) | Data valid + tanggal tersedia | Booking dibuat status PENDING | ✅ |
| TC-BOOK-02 | Booking dengan driver | with_driver = true | Driver fee ditambahkan ke total | ✅ |
| TC-BOOK-03 | Booking tanpa driver | with_driver = false | Driver fee = 0 | ✅ |
| TC-BOOK-04 | Booking kendaraan tidak available | Tanggal sudah dipakai | Error: "Kendaraan tidak tersedia" | ✅ |
| TC-BOOK-05 | Booking tanpa login | akses /checkout tanpa auth | Redirect ke WhatsApp Admin (Guest Flow) | ✅ |
| TC-BOOK-06 | Upload KTP tidak valid | File bukan jpg/png/pdf | Validasi error | ✅ |
| TC-BOOK-07 | Input kode promo valid | kode yang ada, aktif, belum expired | Diskon diterapkan | ✅ |
| TC-BOOK-08 | Input kode promo tidak valid | kode salah | Error: "Kode promo tidak valid" | ✅ |
| TC-BOOK-09 | Auto expire booking | booking tidak dibayar > 24 jam | Status → EXPIRED | ⏳ |
| TC-BOOK-10 | Batalkan booking sebelum confirm | klik batalkan | Status → CANCELLED | ⏳ |

---

## 4. Test Case — Payment

| ID | Skenario | Input | Expected Output | Status |
|:---|:---|:---|:---|:---:|
| TC-PAY-01 | Bayar DP berhasil (settlement) | Midtrans webhook settlement | Payment PAID, Booking PAID | ✅ |
| TC-PAY-02 | Bayar DP gagal (deny) | Midtrans webhook deny | Payment FAILED | ✅ |
| TC-PAY-03 | Bayar DP expired | Midtrans webhook expire | Payment EXPIRED | ⏳ |
| TC-PAY-04 | Webhook dengan signature salah | Invalid signature | Abaikan, log error | ✅ |
| TC-PAY-05 | Pelunasan setelah booking confirmed | Total - DP | Payment PAID, Booking COMPLETED | ⏳ |
| TC-PAY-06 | Bayar denda | fine_amount > 0 | Payment denda PAID | ⏳ |
| TC-PAY-07 | Download invoice setelah completed | klik download PDF | File PDF invoice terunduh | ✅ |

---

## 5. Test Case — Admin Panel

| ID | Skenario | Input | Expected Output | Status |
|:---|:---|:---|:---|:---:|
| TC-ADMIN-01 | Login admin | Role admin | Akses ke /admin/dashboard | ✅ |
| TC-ADMIN-02 | Customer akses /admin | Role customer | 403 Forbidden | ✅ |
| TC-ADMIN-03 | Approve booking | Status PAID → Approve | Status → CONFIRMED | ✅ |
| TC-ADMIN-04 | Reject booking | Status PAID → Reject | Status → CANCELLED | ✅ |
| TC-ADMIN-05 | Tambah kendaraan baru | Form valid | Kendaraan terbuat | ✅ |
| TC-ADMIN-06 | Update status kendaraan | Status → maintenance | Kendaraan tidak bisa dibooking | ✅ |
| TC-ADMIN-07 | Return processing tanpa denda | Kembali tepat waktu | Status → COMPLETED | ✅ |
| TC-ADMIN-08 | Return processing dengan denda | Terlambat 2 hari | Denda dibuat, notifikasi customer | ✅ |
| TC-ADMIN-09 | Export laporan PDF | Filter + klik Export PDF | File PDF terunduh | ✅ |

---

**Legend:** ⏳ Planned | 🔄 In Progress | ✅ Pass | ❌ Fail

---

Versi: 1.1.0 | Tanggal: 2026-05-14
