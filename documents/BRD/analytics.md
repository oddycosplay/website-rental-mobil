# Analytics & Metrics — Siliwangi Rental

**Nama File:** `analytics.md`  
**Lokasi:** `documents/BRD/`  
**Tujuan:** Mendokumentasikan dashboard metrics, revenue analytics, booking analytics, driver analytics, dan operational analytics.

---

## Metadata Dokumen

 | Atribut | Detail |
|---|---|
 | Nama Project | Siliwangi Rental |
 | Versi | 1.0.0 |
 | Tanggal | 2026-05-14 |

---

## 1. Dashboard Metrics (Overview)

Ditampilkan pada halaman utama Admin Panel (Owner/Admin).

 | Metric | Deskripsi | Update |
|---|---|---|
 | Total Booking Hari Ini | Jumlah booking masuk hari ini | Real-time |
 | Total Revenue Bulan Ini | Total pemasukan bulan berjalan | Real-time |
 | Kendaraan Tersedia | Jumlah kendaraan status Available | Real-time |
 | Kendaraan On Rent | Jumlah kendaraan sedang disewa | Real-time |
 | Driver Tersedia | Jumlah driver tidak sedang bertugas | Real-time |
 | Booking Pending | Booking menunggu approval | Real-time |
 | Booking Akan Berakhir Hari Ini | Booking dengan tanggal kembali hari ini | Real-time |
 | Total Customer Aktif | Customer dengan booking aktif | Harian |

---

## 2. Revenue Analytics

### 2.1 Laporan Revenue

 | Metric | Granularitas | Keterangan |
|---|---|---|
 | Total Revenue | Harian, Mingguan, Bulanan, Tahunan | Semua pembayaran settlement |
 | Revenue per Kendaraan | Per unit / per tipe | Kontribusi tiap armada |
 | Revenue per Cabang | Per cabang | Performa cabang |
 | Revenue DP | Bulanan | Total DP yang masuk |
 | Revenue Pelunasan | Bulanan | Total pelunasan yang masuk |
 | Revenue Denda | Bulanan | Total denda yang terbayar |
 | Refund Issued | Bulanan | Total refund yang dikeluarkan |
 | Net Revenue | Bulanan | Revenue - Refund - Expense |

### 2.2 Laporan Laba

 | Metric | Deskripsi |
|---|---|
 | Gross Revenue | Total seluruh penerimaan |
 | Total Expense | Total pengeluaran operasional |
 | Gross Profit | Gross Revenue - Total Expense |
 | Net Profit | Gross Profit - Pajak (jika ada) |

---

## 3. Booking Analytics

 | Metric | Deskripsi | Granularitas |
|---|---|---|
 | Total Booking | Jumlah seluruh booking | Harian/Bulanan |
 | Booking per Status | Pending, Paid, Confirmed, On Rent, Completed, Cancelled, Expired | Bulanan |
 | Booking Rate | Persentase booking masuk vs selesai | Bulanan |
 | Cancellation Rate | Booking dibatalkan / Total booking | Bulanan |
 | Occupancy Rate | Kendaraan On Rent / Total kendaraan × 100% | Harian/Bulanan |
 | Avg Rental Duration | Rata-rata durasi sewa per booking | Bulanan |
 | Booking per Jenis | Harian vs Bulanan | Bulanan |
 | Booking per Kendaraan | Booking per unit kendaraan | Bulanan |
 | Booking per Cabang | Booking per lokasi cabang | Bulanan |
 | Booking dengan Driver | Berapa % booking yang memilih driver | Bulanan |

---

## 4. Driver Analytics

 | Metric | Deskripsi | Granularitas |
|---|---|---|
 | Driver Availability Rate | Driver tersedia / Total driver aktif × 100% | Real-time |
 | Total Trip per Driver | Jumlah booking yang ditangani | Bulanan |
 | Rating per Driver | Rata-rata rating dari customer | Kumulatif |
 | Driver Utilization | Hari aktif bertugas / Hari kalender × 100% | Bulanan |
 | Revenue Kontribusi Driver | Total revenue dari booking with driver | Bulanan |

---

## 5. Vehicle (Operational) Analytics

 | Metric | Deskripsi | Granularitas |
|---|---|---|
 | Vehicle Utilization Rate | Hari On Rent / Total hari kalender | Bulanan |
 | Maintenance Frequency | Frekuensi masuk maintenance per kendaraan | Bulanan |
 | Revenue per Kendaraan | Total pemasukan per unit | Bulanan |
 | Denda per Kendaraan | Total denda yang dihasilkan per unit | Bulanan |
 | Kendaraan Bermasalah | Unit dengan maintenance terbanyak | Bulanan |
 | Inspection Pass Rate | Inspeksi lulus / Total inspeksi × 100% | Bulanan |

---

## 6. Customer Analytics

 | Metric | Deskripsi | Granularitas |
|---|---|---|
 | Total Customer Terdaftar | Jumlah akun customer aktif | Kumulatif |
 | New Customer per Bulan | Customer baru yang mendaftar | Bulanan |
 | Repeat Customer | Customer yang booking > 1 kali | Bulanan |
 | Customer Churn | Customer tidak booking dalam 3 bulan | Bulanan |
 | Avg Order Value per Customer | Rata-rata nilai booking per customer | Bulanan |

---

## 7. Branch Analytics

**Tujuan:** Mengukur dan membandingkan performa operasional antar lokasi cabang.

### 7.1 Key Branch Metrics

- **Revenue per Cabang:** Total penerimaan per lokasi.
- **Booking per Cabang:** Jumlah booking per lokasi.
- **Armada per Cabang:** Jumlah kendaraan aktif per cabang.
- **Occupancy Rate per Cabang:** Tingkat pemakaian kendaraan per cabang.

### 7.2 Inter-branch Comparison

- **Top Performing Branch:** Cabang dengan revenue atau occupancy tertinggi.
- **Underperforming Branch:** Cabang dengan utilisasi armada di bawah 50%.
- **Branch Growth:** Pertumbuhan booking bulan ini vs bulan lalu per cabang.

---

## 8. Implementasi di Admin Panel

- Semua metric ditampilkan dalam widget card di halaman dashboard Filament.
- Chart menggunakan Filament Chart Widget (Bar, Line, Pie).
- Data di-refresh setiap 60 detik untuk real-time metrics.
- Filter by cabang dan periode tersedia untuk semua laporan.
- Export tersedia di halaman Laporan (PDF dan Excel).

---

Versi: 1.1.0 | Tanggal: 2026-05-14
