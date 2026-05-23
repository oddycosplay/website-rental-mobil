# User Acceptance Test — Siliwangi Rental

**Nama File:** `user-acceptance-test.md`  
**Lokasi:** `documents/QA/`  
**Tujuan:** Skenario UAT yang dijalankan oleh stakeholder sebelum go-live.

---

## 1. UAT Skenario — Customer

 | ID | Skenario | Langkah | Accepted? |
|---|---|---|---|
 | UAT-CUS-01 | Registrasi akun | Register → verifikasi email → login | ⏳ |
 | UAT-CUS-02 | Browse dan cari kendaraan | Buka catalog → filter → search | ⏳ |
 | UAT-CUS-03 | Booking kendaraan harian | Pilih mobil → checkout 5 step → konfirmasi | ⏳ |
 | UAT-CUS-04 | Bayar DP via QRIS | Setelah booking → bayar DP → status PAID | ⏳ |
 | UAT-CUS-05 | Cek notifikasi pembayaran | Cek ikon lonceng (navbar) → klik invoice | ⏳ |
 | UAT-CUS-06 | Konfirmasi via WhatsApp (Guest) | Guest checkout → redirect ke WhatsApp | ⏳ |
 | UAT-CUS-07 | Download invoice | Buka invoice → klik download PDF | ⏳ |
 | UAT-CUS-08 | Update profil | Buka profil (frontend) → edit data | ⏳ |

## 2. UAT Skenario — Admin

 | ID | Skenario | Langkah | Accepted? |
|---|---|---|---|
 | UAT-ADMIN-01 | Login admin panel | Masuk /admin → dashboard | ⏳ |
 | UAT-ADMIN-02 | Approve booking | Booking PAID → review → approve | ⏳ |
 | UAT-ADMIN-03 | Assign driver | Booking confirmed → pilih driver | ⏳ |
 | UAT-ADMIN-04 | Tambah kendaraan baru | CRUD kendaraan → tambah → simpan | ⏳ |
 | UAT-ADMIN-05 | Proses pengembalian | Return processing → input tanggal | ⏳ |
 | UAT-ADMIN-06 | Lihat dashboard analytics | Dashboard → stats widget → chart | ⏳ |
 | UAT-ADMIN-07 | Export laporan booking | Laporan → filter → Export PDF | ⏳ |

## 3. UAT Skenario — Finance

 | ID | Skenario | Langkah | Accepted? |
|---|---|---|---|
 | UAT-FIN-01 | Lihat semua transaksi | Payment management → daftar payment | ⏳ |
 | UAT-FIN-02 | Export laporan keuangan | Laporan revenue → export Excel | ⏳ |
 | UAT-FIN-03 | Proses refund | Booking cancelled → proses refund manual | ⏳ |

## 4. UAT Skenario — Owner

 | ID | Skenario | Langkah | Accepted? |
|---|---|---|---|
 | UAT-OWN-01 | Lihat KPI dashboard | Dashboard → metrics → trend chart | ⏳ |
 | UAT-OWN-02 | Lihat laporan bulanan | Laporan → filter bulan → tampilkan | ⏳ |
 | UAT-OWN-03 | Kelola role user | Sistem → User → assign role | ⏳ |

---

**Sign-off UAT:**

 | Stakeholder | Nama | Tanda Tangan | Tanggal |
|---|---|---|---|
 | Owner | — | — | — |
 | Admin | — | — | — |
 | Finance | — | — | — |

---

Versi: 1.0.0 | Tanggal: 2026-05-14
