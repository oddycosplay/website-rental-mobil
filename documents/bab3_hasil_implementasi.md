# 3.4.2 Hasil Implementasi Sistem

Berdasarkan use case diagram Sistem Booking Rental Mobil Siliwangi Rental, berikut adalah hasil implementasi antarmuka (_user interface_) dari seluruh fitur yang terdapat pada sistem.

## a. Tampilan Customer

### 1. Implementasi Website Halaman Beranda (Home)

Menampilkan informasi utama perusahaan, kendaraan unggulan, promo, serta tombol pemesanan.

![Halaman Beranda Customer](testing/customer_1_home.png)

### 2. Implementasi Website Halaman Daftar Kendaraan (Car)

Menampilkan daftar kendaraan yang tersedia lengkap dengan spesifikasi, harga sewa, dan status ketersediaan.

![Halaman Daftar Kendaraan](testing/customer_2_Car.png)

### 3. Implementasi Website Halaman FAQ

Menampilkan daftar pertanyaan dan jawaban yang sering diajukan oleh pelanggan.

![Halaman FAQ](testing/customer_3_faq.png)

### 4. Implementasi Website Halaman Kontak (Contact)

Menampilkan informasi kontak perusahaan, alamat, email, nomor telepon, dan formulir kontak.

![Halaman Kontak](testing/customer_4_contact.png)

### 5. Implementasi Website Halaman Tentang Kami (About Us)

Menampilkan profil perusahaan, visi misi, serta informasi layanan rental mobil.

![Halaman Tentang Kami](testing/customer_5_about.png)

### 6. Implementasi Registrasi Customer

Fitur pendaftaran akun pelanggan baru dengan validasi data pengguna.

![Registrasi Customer](testing/customer_6_register.png)

### 7. Implementasi Login Customer

Fitur autentikasi pengguna untuk mengakses layanan booking dan pengelolaan akun.

![Login Customer](testing/customer_7_login.png)

### 8. Implementasi Kelola Profil Customer

Fitur untuk mengubah data profil pelanggan seperti nama, nomor telepon, alamat, dan kata sandi.

![Kelola Profil Customer](testing/customer_8_profile.png)

### 9. Implementasi Booking Kendaraan

Fitur pemesanan kendaraan dengan memilih kendaraan, tanggal sewa, lokasi penjemputan, dan durasi rental.

![Booking Kendaraan](testing/customer_9_booking.png)

### 10. Implementasi Checkout Booking

Fitur konfirmasi data pemesanan sebelum melakukan pembayaran.

![Checkout Booking](testing/customer_10_checkout.png)

### 11. Implementasi Pembayaran Booking

Fitur pembayaran transaksi rental melalui payment gateway.

![Pembayaran Booking](testing/customer_11_payment.png)

### 12. Implementasi Invoice Pembayaran

Menampilkan detail tagihan dan bukti pembayaran pelanggan.

![Invoice Pembayaran](testing/customer_12_invoice.png)

### 13. Implementasi Riwayat Booking Customer

Menampilkan daftar transaksi pemesanan yang pernah dilakukan pelanggan.

![Riwayat Booking](testing/customer_13_booking_history.png)

### 14. Implementasi Detail Booking Customer

Menampilkan informasi detail pemesanan termasuk status booking dan pembayaran.

![Detail Booking](testing/customer_14_booking_detail.png)

### 15. Implementasi Pembatalan Booking

Fitur untuk membatalkan pesanan sesuai ketentuan yang berlaku.

![Pembatalan Booking](testing/customer_15_cancel_booking.png)

### 16. Implementasi Pengajuan Refund Dana

Fitur pengajuan pengembalian dana apabila terjadi pembatalan transaksi.

![Pengajuan Refund Dana](testing/customer_16_refund.png)

### 17. Implementasi Pembayaran Denda

Fitur pembayaran denda keterlambatan atau pelanggaran selama masa sewa.

![Pembayaran Denda](testing/customer_17_fine_payment.png)

### 18. Implementasi Review dan Rating

Fitur pemberian ulasan dan penilaian terhadap layanan rental yang telah digunakan.

![Review dan Rating](testing/customer_18_review.png)

---

## b. Tampilan Admin

### 1. Implementasi Dashboard Admin

Menampilkan ringkasan data booking, kendaraan, pembayaran, dan statistik penyewaan.

![Dashboard Admin](testing/admin_1_dashboard.png)

### 2. Implementasi Kelola Data Kendaraan

Fitur pengelolaan data kendaraan yang tersedia pada sistem.

![Kelola Data Kendaraan](testing/admin_2_manage_cars.png)

### 3. Implementasi Tambah Kendaraan

Fitur menambahkan data kendaraan baru.

![Tambah Kendaraan](testing/admin_3_add_car.png)

### 4. Implementasi Edit dan Hapus Kendaraan

Fitur memperbarui dan menghapus data kendaraan.

![Edit dan Hapus Kendaraan](testing/admin_4_edit_delete_car.png)

### 5. Implementasi Kelola Data Customer

Fitur pengelolaan data pelanggan yang terdaftar pada sistem.

![Kelola Data Customer](testing/admin_5_manage_customers.png)

### 6. Implementasi Kelola Booking

Fitur verifikasi, persetujuan, dan pengelolaan data booking pelanggan.

![Kelola Booking](testing/admin_6_manage_bookings.png)

### 7. Implementasi Booking Manual Customer

Fitur pencatatan booking yang dilakukan melalui WhatsApp atau datang langsung ke lokasi.

![Booking Manual Customer](testing/admin_7_manual_booking.png)

### 8. Implementasi Kelola Pembayaran

Fitur monitoring dan pengelolaan seluruh transaksi pembayaran.

![Kelola Pembayaran](testing/admin_8_manage_payments.png)

### 9. Implementasi Konfirmasi Pembayaran

Fitur verifikasi status pembayaran dari pelanggan.

![Konfirmasi Pembayaran](testing/admin_9_confirm_payment.png)

### 10. Implementasi Kelola Promo

Fitur pengelolaan kode promo dan diskon penyewaan.

![Kelola Promo](testing/admin_10_manage_promos.png)

### 11. Implementasi Kelola Driver

Fitur pengelolaan data driver serta penugasan driver kepada pelanggan.

![Kelola Driver](testing/admin_11_manage_drivers.png)

### 12. Implementasi Kelola Jadwal Driver

Fitur pengaturan jadwal dan status ketersediaan driver.

![Kelola Jadwal Driver](testing/admin_12_driver_schedule.png)

### 13. Implementasi Kelola Cabang/Toko

Fitur pengelolaan data cabang atau lokasi operasional perusahaan.

![Kelola Cabang](testing/admin_13_manage_branches.png)

### 14. Implementasi Kelola User dan Hak Akses

Fitur pengaturan akun pengguna sistem beserta level aksesnya.

![Kelola User dan Hak Akses](testing/admin_14_manage_users.png)

### 15. Implementasi Input Hasil Survei Lokasi

Fitur pencatatan hasil survei lokasi pelanggan sebelum proses penyewaan.

![Input Hasil Survei Lokasi](testing/admin_15_location_survey.png)

### 16. Implementasi Return Processing Kendaraan

Fitur pencatatan pengembalian kendaraan dan pemeriksaan kondisi kendaraan.

![Return Processing Kendaraan](testing/admin_16_return_processing.png)

### 17. Implementasi Kelola Maintenance Kendaraan

Fitur pengelolaan jadwal servis dan perawatan armada.

![Kelola Maintenance](testing/admin_17_manage_maintenance.png)

### 18. Implementasi Kelola Refund Dana

Fitur persetujuan dan proses pengembalian dana kepada pelanggan.

![Kelola Refund Dana](testing/admin_18_manage_refunds.png)

### 19. Implementasi Kelola Pengeluaran Operasional

Fitur pencatatan biaya operasional perusahaan.

![Kelola Pengeluaran Operasional](testing/admin_19_manage_expenses.png)

### 20. Implementasi Dashboard Analytics

Fitur visualisasi statistik transaksi, pendapatan, dan penggunaan armada.

![Dashboard Analytics](testing/admin_20_analytics.png)

### 21. Implementasi Laporan Penyewaan

Menampilkan laporan data penyewaan kendaraan berdasarkan periode tertentu.

![Laporan Penyewaan](testing/admin_21_rental_report.png)

### 22. Implementasi Laporan Pembayaran

Menampilkan laporan transaksi pembayaran pelanggan.

![Laporan Pembayaran](testing/admin_22_payment_report.png)

### 23. Implementasi Laporan Kendaraan

Menampilkan laporan penggunaan dan ketersediaan armada.

![Laporan Kendaraan](testing/admin_23_car_report.png)

### 24. Implementasi Ekspor Laporan

Fitur ekspor laporan ke format PDF atau Excel.

![Ekspor Laporan](testing/admin_24_export_report.png)

---

## c. Tampilan Driver

### 1. Implementasi Login Driver

Fitur autentikasi akun driver.

![Login Driver](testing/driver_1_login.png)

### 2. Implementasi Kelola Jadwal Driver

Menampilkan jadwal tugas dan status ketersediaan driver.

![Kelola Jadwal Driver](testing/driver_2_schedule.png)

### 3. Implementasi Detail Penugasan Driver

Menampilkan informasi pelanggan, kendaraan, dan jadwal perjalanan.

![Detail Penugasan Driver](testing/driver_3_assignment_detail.png)

### 4. Implementasi Input Status Perjalanan

Fitur pembaruan status perjalanan selama proses rental berlangsung.

![Input Status Perjalanan](testing/driver_4_update_status.png)

### 5. Implementasi Konfirmasi Penyelesaian Tugas

Fitur konfirmasi bahwa tugas pengantaran atau penjemputan telah selesai dilakukan.

![Konfirmasi Penyelesaian Tugas](testing/driver_5_complete_task.png)
