# Laporan Sistem — Siliwangi Rental

**Nama File:** `laporan.md`  
**Lokasi:** `documents/BRD/`  
**Tujuan:** Mendokumentasikan semua jenis laporan yang tersedia dalam sistem — booking, revenue, kendaraan, driver, denda — serta format export PDF dan Excel.

---

## Metadata Dokumen

 | Atribut | Detail |
|---|---|
 | Nama Project | Siliwangi Rental |
 | Versi | 1.1.0 |
 | Tanggal | 2026-05-14 |

---

## 1. Laporan Booking

**Lokasi di Admin Panel:** Admin → Laporan → Booking

### Kolom Laporan

 | Kolom | Tipe | Keterangan |
|---|---|---|
 | No. Booking | String | Nomor unik booking |
 | Tanggal Booking | Date | Kapan booking dibuat |
 | Nama Customer | String | Nama pemesan |
 | Kendaraan | String | Nama + plat kendaraan |
 | Cabang | String | Cabang asal kendaraan |
 | Tanggal Mulai | Date | Tanggal mulai sewa |
 | Tanggal Selesai | Date | Tanggal selesai sewa |
 | Durasi | Integer | Jumlah hari |
 | Jenis Sewa | Enum | Harian / Bulanan |
 | Dengan Driver | Boolean | Ya / Tidak |
 | Status Booking | Enum | Pending, Confirmed, Completed, dst |
 | Total Biaya | Decimal | Total biaya booking |
 | Status Pembayaran | Enum | Partial, Paid, dst |

### Filter Tersedia

- Periode (tanggal dari — sampai)
- Status booking
- Cabang
- Jenis sewa (harian/bulanan)
- Kendaraan

---

## 2. Laporan Revenue (Keuangan)

**Lokasi di Admin Panel:** Admin → Laporan → Keuangan

### 2.1 Laporan Penerimaan

 | Kolom | Keterangan |
|---|---|
 | Periode | Tanggal transaksi |
 | No. Transaksi | ID Midtrans / nomor internal |
 | No. Booking | Relasi ke booking |
 | Customer | Nama customer |
 | Jenis Pembayaran | DP / Pelunasan / Denda / Additional |
 | Metode | VA / QRIS / CC / Manual |
 | Jumlah | Nominal yang dibayar |
 | Status | Paid / Failed / Refunded |

### 2.2 Laporan Pengeluaran (Expense)

 | Kolom | Keterangan |
|---|---|
 | Tanggal | Tanggal pengeluaran |
 | Kategori | Maintenance / Operasional / Gaji / BBM / lainnya |
 | Deskripsi | Keterangan detail |
 | Jumlah | Nominal pengeluaran |
 | Cabang | Cabang terkait |
 | Dicatat Oleh | Finance staff |

### 2.3 Laporan Laba

 | Kolom | Keterangan |
|---|---|
 | Periode | Bulan/Tahun |
 | Total Penerimaan | Gross revenue |
 | Total Pengeluaran | Total expense |
 | Laba Kotor | Penerimaan - Pengeluaran |
 | Refund | Total refund yang dikeluarkan |
 | Laba Bersih | Laba Kotor - Refund |

---

## 3. Laporan Kendaraan

**Lokasi di Admin Panel:** Admin → Laporan → Kendaraan

 | Kolom | Keterangan |
|---|---|
 | Kendaraan | Nama + plat nomor |
 | Tipe | Sedan, SUV, MPV, dst |
 | Cabang | Lokasi kendaraan |
 | Total Trip | Jumlah booking selesai |
 | Total Hari On Rent | Kumulatif hari disewa |
 | Occupancy Rate | % penggunaan |
 | Total Revenue | Pendapatan dari kendaraan |
 | Total Maintenance | Berapa kali masuk maintenance |
 | Total Denda | Total denda yang dihasilkan |

---

## 4. Laporan Maintenance

**Lokasi di Admin Panel:** Admin → Laporan → Maintenance

 | Kolom | Keterangan |
|---|---|
 | Kendaraan | Unit yang di-maintenance |
 | Tanggal Mulai | Mulai maintenance |
 | Tanggal Selesai | Selesai maintenance |
 | Durasi | Hari |
 | Jenis Maintenance | Berkala / Kerusakan / Ban / dst |
 | Biaya | Nominal biaya maintenance |
 | Deskripsi | Keterangan pekerjaan |
 | Status | Selesai / Dalam Proses |

---

## 5. Laporan Inspeksi

**Lokasi di Admin Panel:** Admin → Laporan → Inspeksi

 | Kolom | Keterangan |
|---|---|
 | No. Booking | Booking terkait |
 | Kendaraan | Unit yang diinspeksi |
 | Tanggal Inspeksi | Tanggal dilakukan |
 | Tipe | Pre-rental / Post-rental |
 | Kondisi Eksterior | Baik / Lecet / Rusak |
 | Kondisi Interior | Baik / Kotor / Rusak |
 | Km Awal / Akhir | Kilometer odometer |
 | Temuan | Deskripsi kerusakan (jika ada) |
 | Inspektor | Nama admin/driver |

---

## 6. Laporan Denda

**Lokasi di Admin Panel:** Admin → Laporan → Denda

 | Kolom | Keterangan |
|---|---|
 | No. Booking | Relasi booking |
 | Customer | Nama pemesan |
 | Kendaraan | Unit yang disewa |
 | Tanggal Harus Kembali | Tanggal jatuh tempo |
 | Tanggal Kembali Aktual | Tanggal pengembalian nyata |
 | Keterlambatan | Jumlah hari terlambat |
 | Tarif Denda/Hari | Nominal per hari |
 | Total Denda | Total tagihan denda |
 | Status Pembayaran | Paid / Pending |

---

## 7. Laporan Keuangan Bulanan (Monthly Financial Summary)

**Tujuan:** Laporan komprehensif untuk Owner/Manajemen yang merangkum performa finansial dalam satu bulan penuh.

### 7.1 Ringkasan Eksekutif

- **Total Revenue (Gross):** Total seluruh pembayaran settlement.
- **Total Operational Expense:** Biaya maintenance, BBM, gaji driver.
- **Total Refund:** Pengembalian dana booking batal.
- **Net Profit:** Revenue - (Expense + Refund).

### 7.2 Breakdown Pendapatan

- **Rental Revenue:** Pendapatan dari sewa unit.
- **Driver Revenue:** Pendapatan dari driver fee.
- **Penalty Revenue:** Pendapatan dari denda keterlambatan.
- **Promo Discount:** Total potongan harga yang diberikan (mengurangi gross).

---

## 8. Laporan Performa Cabang (Branch Performance)

**Tujuan:** Membandingkan produktivitas antar cabang.

 | Cabang | Total Booking | Revenue | Occupancy Rate | Rating Rata-rata |
|---|---|---|---|---|
 | Cabang A | 45 | Rp 45jt | 82% | 4.8 |
 | Cabang B | 30 | Rp 28jt | 65% | 4.5 |

---

## 9. Laporan Driver

**Lokasi di Admin Panel:** Admin → Laporan → Driver

| Kolom | Keterangan |
|---|---|
| Nama Driver | Identitas driver |
| Total Trip | Jumlah booking yang ditangani |
| Total Hari Bertugas | Kumulatif |
| Utilization Rate | % hari aktif vs kalender |
| Avg Rating | Rata-rata rating customer |
| Total Pendapatan Driver Fee | Revenue dari trip dengan driver |

---

## 10. Laporan Customer

**Lokasi di Admin Panel:** Admin → Laporan → Customer

| Kolom | Keterangan |
|---|---|
| Nama Customer | Identitas |
| Email | Kontak |
| Tanggal Daftar | Registrasi akun |
| Total Booking | Jumlah booking seluruh waktu |
| Total Spending | Total nilai pembayaran |
| Booking Terakhir | Tanggal booking paling baru |
| Status Akun | Aktif / Diblokir |

---

## 11. Laporan KPI

**Lokasi di Admin Panel:** Admin → Laporan → KPI Dashboard

| KPI | Formula | Target |
|---|---|---|
| Occupancy Rate | On Rent / Total Armada × 100% | ≥ 70% |
| Booking Completion Rate | Completed / Total Booking × 100% | ≥ 90% |
| Revenue per Kendaraan | Total Revenue / Jumlah Armada | Belum ditentukan |
| Customer Satisfaction | Avg Rating Driver | ≥ 4.0 / 5.0 |
| Payment Success Rate | Paid / Total Transaksi × 100% | ≥ 95% |
| Cancellation Rate | Cancelled / Total Booking × 100% | ≤ 10% |

---

## 12. Format Export

### 12.1 Export PDF (Branded)

- **Header:** Logo Siliwangi Rental, Alamat Pusat, Periode Laporan.
- **Body:** Ringkasan Eksekutif (Cards) + Tabel Detail.
- **Footer:** Tanggal Generate, Nama Staff yang mengunduh.

### 12.2 Export Excel (Raw Data)

- **Sheet 1:** Summary (Dashboard-like summary).
- **Sheet 2:** Detailed Transactions (Semua baris data).
- **Sheet 3:** Analytics (Pivot-ready data).

---

Versi: 1.1.0 | Tanggal: 2026-05-14
