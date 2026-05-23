# Risk & Assessment — Siliwangi Rental

**Nama File:** `assessment.md`  
**Lokasi:** `documents/BRD/`  
**Tujuan:** Mendokumentasikan risk assessment, fraud prevention, scalability assessment, dan security assessment sistem Siliwangi Rental.

---

## Metadata Dokumen

 | Atribut | Detail |
|---|---|
 | Nama Project | Siliwangi Rental |
 | Versi | 1.0.0 |
 | Tanggal | 2026-05-14 |

---

## 1. Risk Assessment

 | ID | Risiko | Kemungkinan | Dampak | Level | Mitigasi |
|---|---|---|---|---|---|
 | RSK-01 | Midtrans downtime / error gateway | Rendah | Tinggi | HIGH | Retry mechanism + fallback notifikasi manual |
 | RSK-02 | Double booking kendaraan | Rendah | Tinggi | HIGH | Database lock + availability check real-time |
 | RSK-03 | Data KTP/SIM palsu dari customer | Sedang | Tinggi | HIGH | Manual review admin sebelum approval |
 | RSK-04 | Kendaraan tidak dikembalikan tepat waktu | Sedang | Sedang | MEDIUM | Reminder otomatis H-1, H+1; sistem denda |
 | RSK-05 | Server down / website tidak bisa diakses | Rendah | Tinggi | HIGH | Deployment multi-environment + backup rutin |
 | RSK-06 | Kebocoran data customer (PII) | Rendah | Sangat Tinggi | CRITICAL | Enkripsi data sensitif + HTTPS wajib |
 | RSK-07 | Driver tidak hadir saat jadwal | Sedang | Sedang | MEDIUM | Konfirmasi driver D-1 + driver backup |
 | RSK-08 | Kendaraan mengalami kerusakan saat rental | Sedang | Sedang | MEDIUM | Inspeksi pra-rental + asuransi kendaraan |
 | RSK-09 | Bug pada sistem kalkulasi denda/harga | Rendah | Sedang | MEDIUM | Unit test coverage + staging environment |
 | RSK-10 | Spam booking / akun palsu | Sedang | Rendah | LOW | Email verification + rate limiting |

---

## 2. Fraud Prevention

### 2.1 Identitas Customer

 | Kontrol | Implementasi |
|---|---|
 | Verifikasi email wajib | Email verification link saat registrasi |
 | Upload KTP & SIM wajib | Form booking step 2, file validation |
 | Review manual admin | Admin wajib cek dokumen sebelum approve |
 | Blacklist customer | Admin dapat blokir akun customer bermasalah |

### 2.2 Pembayaran

 | Kontrol | Implementasi |
|---|---|
 | Webhook signature validation | Midtrans signature key divalidasi di setiap callback |
 | Idempotency check | Duplicate order_id dicegah di database |
 | HTTPS enforced | Semua transaksi melalui koneksi terenkripsi |
 | Payment log | Setiap event pembayaran dicatat di PaymentLog |
 | Refund manual | Refund hanya bisa diproses oleh Finance role |

### 2.3 Sistem

 | Kontrol | Implementasi |
|---|---|
 | Rate limiting | Throttle pada endpoint booking dan payment |
 | CSRF protection | Laravel default CSRF token pada semua form |
 | SQL injection prevention | Eloquent ORM + parameterized queries |
 | XSS prevention | Blade template auto-escaping |
 | Brute force protection | Laravel Fortify login throttling |
 | Activity log | Semua aksi admin dicatat dengan user_id + timestamp |

---

## 3. Scalability Assessment

### 3.1 Database Scalability

 | Aspek | Strategi |
|---|---|
 | Indexing | Index pada FK, kolom status, tanggal sewa |
 | Query optimization | Eager loading untuk relasi, avoid N+1 |
 | Connection pooling | MySQL connection pool via Laravel DB config |
 | Soft deletes | Gunakan soft delete untuk data historis |
 | Archiving | Data booking > 2 tahun dapat diarsipkan |

### 3.2 Application Scalability

 | Aspek | Strategi |
|---|---|
 | Queue system | Laravel Queue untuk notifikasi dan job berat |
 | Cache | Laravel Cache (file/redis) untuk catalog dan availability |
 | Modular architecture | Service layer terpisah dari controller |
 | Multi-branch | Branch ID di semua entitas terkait |
 | Stateless API | API endpoint siap untuk mobile app di masa depan |

### 3.3 Infrastructure Scalability

 | Aspek | Strategi |
|---|---|
 | Server | VPS/Cloud server dengan kemampuan vertical scaling |
 | File storage | Storage driver (local/S3) untuk upload dokumen |
 | Session | Database/Redis session driver untuk multi-server |
 | Job workers | Multiple queue worker process |

---

## 4. Security Assessment

### 4.1 Authentication Security

 | Kontrol | Detail |
|---|---|
 | Password hashing | Bcrypt via Laravel Hash facade |
 | Session security | HTTP-only cookie, SameSite, session fixation protection |
 | Remember me | Secure remember token di database |
 | 2FA (opsional) | Infrastruktur tersedia via Fortify (belum aktif) |
 | Role-based access | Spatie Permission — setiap route dilindungi middleware |

### 4.2 Data Security

 | Data | Perlindungan |
|---|---|
 | Password | Tidak disimpan plain text — bcrypt |
 | Midtrans keys | Disimpan di .env — tidak di database |
 | File KTP/SIM | Disimpan di storage (non-public) dengan akses terbatas |
 | Personal data | Hanya admin/finance yang dapat mengakses |

### 4.3 API Security

 | Kontrol | Detail |
|---|---|
 | API authentication | Sanctum token untuk endpoint API internal |
 | Midtrans callback | Validasi signature sebelum proses |
 | CORS | Konfigurasi CORS restrictive di API routes |
 | Rate limiting | Throttle middleware pada API endpoints |

### 4.4 OWASP Top 10 Mitigation

 | OWASP Risk | Status |
|---|---|
 | Injection | MITIGATED — Eloquent ORM |
 | Broken Authentication | MITIGATED — Fortify + RBAC |
 | Sensitive Data Exposure | MITIGATED — HTTPS + .env secrets |
 | XML External Entities | N/A |
 | Broken Access Control | MITIGATED — Policy + Permission |
 | Security Misconfiguration | MITIGATED — Production config checklist |
 | XSS | MITIGATED — Blade auto-escape |
 | Insecure Deserialization | MITIGATED — No custom deserialization |
 | Known Vulnerabilities | MONITORED — composer audit |
 | Insufficient Logging | MITIGATED — Activity Log + PaymentLog |

---

Versi: 1.0.0 | Tanggal: 2026-05-14
