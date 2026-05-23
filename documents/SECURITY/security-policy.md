# Security Policy — Siliwangi Rental

**Nama File:** `security-policy.md`  
**Lokasi:** `documents/SECURITY/`  
**Tujuan:** Kebijakan keamanan sistem Siliwangi Rental yang wajib dipatuhi oleh semua tim.

---

## 1. Prinsip Keamanan

Sistem Siliwangi Rental mengikuti prinsip **Defense in Depth** — lapisan keamanan berlapis dari UI hingga database.

### Prinsip Utama

- **Least Privilege** — setiap role hanya memiliki akses minimum yang diperlukan.
- **Input Validation** — semua input divalidasi di server (tidak hanya client-side).
- **Secure by Default** — konfigurasi default selalu aman, bukan permissive.
- **Fail Securely** — saat error, sistem gagal dengan aman (tidak expose info sensitif).
- **Audit Trail** — semua aksi penting tercatat dengan user dan timestamp.

---

## 2. Authentication Policy

 | Kebijakan | Detail |
|---|---|
 | Password minimum | 8 karakter |
 | Password complexity | Belum ditentukan pada requirement |
 | Throttling | 5 gagal → lockout 60 detik |
 | Session lifetime | 120 menit tidak aktif |
 | Session cookie | HTTP-only, Secure, SameSite=Lax |
 | Remember me | Token disimpan di DB, di-rotate tiap login |
 | Email verification | Wajib untuk akses penuh |

---

## 3. Data Protection Policy

 | Data | Perlindungan |
|---|---|
 | Password user | Bcrypt — tidak disimpan plain text |
 | Midtrans server key | Hanya di `.env` — tidak di DB, tidak di code |
 | WhatsApp token | Hanya di `.env` |
 | File KTP/SIM | Storage private — tidak bisa diakses publik |
 | Data customer (nama, email, telepon) | Hanya diakses oleh role yang berwenang |

---

## 4. API Security Policy

 | Kebijakan | Detail |
|---|---|
 | API Authentication | Sanctum Bearer Token |
 | Midtrans webhook | Validasi signature SHA512 wajib |
 | CORS | Restrictive — hanya origin yang diizinkan |
 | Rate Limiting | 60 request/menit per IP (default throttle) |
 | HTTPS | Wajib di semua endpoint production |

---

## 5. Production Security Checklist

```
✅ APP_DEBUG=false di production
✅ .env tidak ter-expose (proteksi Nginx)
✅ HTTPS aktif + SSL certificate valid
✅ Semua secret di .env — tidak di kode
✅ File storage tidak bisa diakses publik langsung
✅ Database tidak menggunakan user root di production
✅ MySQL hanya listen di localhost
✅ Nginx tidak menampilkan versi server
✅ Error log tidak menampilkan info sensitif ke user
✅ Backup database terjadwal
```

---

## 6. Incident Response

 | Jenis Insiden | Tindakan |
|---|---|
 | Data breach | Nonaktifkan akses → audit log → notifikasi admin |
 | Payment fraud | Blokir transaksi → audit → lapor ke Midtrans |
 | Server compromise | Isolasi server → restore dari backup |
 | Bug kritis | Maintenance mode → fix → deploy → laporan |

---

Versi: 1.0.0 | Tanggal: 2026-05-14
