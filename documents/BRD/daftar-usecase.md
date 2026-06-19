# Daftar Use Case — Siliwangi Rental

Dokumen ini berisi daftar lengkap Use Case sistem Siliwangi Rental yang telah disederhanakan dan diselaraskan dengan diagram Use Case terbaru (`usecase-diagram.drawio`).

| Kode Use Case | Nama Use Case | Aktor | Deskripsi |
| :--- | :--- | :--- | :--- |
| **UC1** | Registrasi & Autentikasi | Customer | Melakukan pendaftaran akun baru (registrasi) dan masuk ke dalam sistem (login). |
| **UC2** | Browse Katalog Mobil | Guest, Customer | Melihat daftar armada mobil yang tersedia beserta spesifikasi, fitur, dan tarif sewa harian. |
| **UC3** | Proses Booking & Checkout | Customer | Memilih kendaraan, menentukan opsi supir/lepas kunci, dan menyelesaikan checkout pemesanan. |
| **UC4** | Riwayat & Pembatalan Booking | Customer | Memantau status transaksi aktif, melihat riwayat rental, dan mengajukan pembatalan booking. |
| **UC5** | Kelola Pembayaran & Invoice | Customer | Mengunggah bukti pembayaran transfer bank, melihat status konfirmasi, dan mengunduh invoice. |
| **UC6** | Edit Profil Akun | Customer | Memperbarui data pribadi seperti nama, nomor kontak, foto profil, dan kata sandi. |
| **UC7** | Verifikasi Kelayakan Booking & Survei Lokasi | Admin, Operasional | Memvalidasi kelengkapan berkas jaminan (KTP/SIM/KK) serta melakukan survei lokasi jika diperlukan. |
| **UC8** | Assign Driver | Admin | Menugaskan supir yang tersedia ke pesanan rental yang menggunakan layanan supir. |
| **UC10** | Serah Terima & Inspeksi Armada | Driver, Operasional, Admin | Melakukan audit fisik kendaraan (check-in/check-out) dan mencatat kondisi mobil saat penyerahan/pengembalian. |
| **UC11** | Bayar Denda | Customer | Melakukan pembayaran biaya denda jika terjadi keterlambatan pengembalian atau kerusakan unit. |
| **UC12** | Manajemen Pemesanan & Jadwal | Admin | Mengatur jadwal pemanfaatan armada mobil dan memantau status sewa secara keseluruhan. |
| **UC13** | Kelola Jadwal & Status Driver | Driver | Memperbarui status ketersediaan kerja (aktif/tidak aktif) dan melihat instruksi penugasan jadwal. |
| **UC14** | Kelola Data Master | Admin, Owner | Mengelola konfigurasi data dasar sistem seperti data cabang (branch), voucher promo, dan tarif dasar. |
| **UC15** | Dashboard Analytics | Admin, Owner | Memantau visualisasi performa penjualan, grafik transaksi rental, dan indikator bisnis utama (KPI). |
| **UC16** | Kelola Laporan & Ekspor | Finance, Admin, Owner | Menyusun dan mengunduh laporan keuangan, laporan operasional, serta statistik armada. |
| **UC17** | Kelola User & Hak Akses | Admin, Owner | Mengatur data akun staf internal dan memetakan hak akses (Role-Based Access Control / RBAC). |
| **UC19** | Manajemen Keuangan & Transaksi Finansial | Finance, Admin | Mengaudit verifikasi pembayaran masuk, menyetujui pengembalian dana (refund), dan mencatat pengeluaran operasional. |
| **UC20** | Beri Review & Rating | Customer | Memberikan ulasan tekstual dan nilai rating bintang untuk armada mobil yang telah selesai disewa. |
| **UC22** | Manajemen Mobil | Admin, Owner | Mengelola data armada kendaraan (tambah mobil baru, edit deskripsi/foto/tarif, update status mobil). |
| **UC23** | Manajemen Supir | Admin, Owner | Mengelola data pengemudi internal (tambah supir baru, edit informasi, serta penonaktifan supir). |
| **UC24** | Logout | Customer, Driver, Admin, Finance, Operasional, Owner | Menutup sesi aktif (clear session) pengguna pada aplikasi untuk alasan keamanan. |
