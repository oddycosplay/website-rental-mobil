@extends('layouts.app')
@section('title', 'Booking Berhasil – Siliwangi Rental')

@section('styles')
<style>
.invoice-page{padding:120px 0 80px;background:#0F172A;min-height:100vh;}
.invoice-card{max-width:600px;margin:0 auto;background:rgba(30,41,59,.95);border:1px solid rgba(212,175,55,.2);border-radius:24px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.5);}
.inv-header{background:linear-gradient(135deg,#D4AF37,#A88B1D);padding:40px 30px;text-align:center;}
.inv-header i{font-size:3rem;color:#0F172A;margin-bottom:16px;}
.inv-header h2{font-family:'Poppins',sans-serif;font-weight:800;color:#0F172A;margin-bottom:4px;}
.inv-header p{color:rgba(15,23,42,.8);font-size:.9rem;font-weight:600;}
.inv-body{padding:30px;}
.inv-code{text-align:center;margin-bottom:30px;}
.inv-code-label{font-size:.75rem;color:#94A3B8;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;}
.inv-code-val{font-family:'Poppins',sans-serif;font-size:1.6rem;font-weight:800;color:#D4AF37;}
.inv-details{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:16px;padding:20px;margin-bottom:24px;}
.inv-row{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px dashed rgba(255,255,255,.05);}
.inv-row:last-child{border-bottom:none;}
.inv-row span:first-child{color:#94A3B8;font-size:.85rem;}
.inv-row span:last-child{color:#fff;font-weight:600;font-size:.9rem;text-align:right;}
.inv-total{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;background:rgba(212,175,55,.1);border:1px solid rgba(212,175,55,.3);border-radius:12px;}
.inv-total-label{font-weight:700;color:#D4AF37;}
.inv-total-val{font-family:'Poppins',sans-serif;font-size:1.4rem;font-weight:800;color:#D4AF37;}
.payment-instruction{margin-top:30px;text-align:center;}
.payment-instruction h4{color:#fff;font-weight:700;margin-bottom:10px;font-size:1rem;}
.payment-instruction p{color:#94A3B8;font-size:.85rem;line-height:1.6;}
.btn-wa{display:inline-flex;align-items:center;justify-content:center;gap:8px;background:#25D366;color:#fff;padding:14px 24px;border-radius:12px;font-weight:700;text-decoration:none;transition:.3s;width:100%;margin-top:20px;}
.btn-wa:hover{background:#20BA56;transform:translateY(-2px);}
.btn-home{display:inline-flex;align-items:center;justify-content:center;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);color:#fff;padding:14px 24px;border-radius:12px;font-weight:600;text-decoration:none;transition:.3s;width:100%;margin-top:12px;}
.btn-home:hover{background:rgba(255,255,255,.1);}
</style>
@endsection

@section('content')
<div class="invoice-page">
    <div class="container">
        <div class="invoice-card">
            <div class="inv-header">
                <i class="fas fa-check-circle"></i>
                <h2>Booking Berhasil!</h2>
                <p>Terima kasih telah mempercayakan Siliwangi Rental</p>
            </div>
            <div class="inv-body">
                <div class="inv-code">
                    <div class="inv-code-label">Kode Booking Anda</div>
                    <div class="inv-code-val">{{ $booking->booking_code }}</div>
                </div>

                <div class="inv-details">
                    <div class="inv-row">
                        <span>Pemesan</span>
                        <span>{{ $booking->customer->name }}</span>
                    </div>
                    <div class="inv-row">
                        <span>Kendaraan</span>
                        <span>{{ $booking->car->car_name }} ({{ $booking->car->plate_number }})</span>
                    </div>
                    <div class="inv-row">
                        <span>Jadwal Sewa</span>
                        <span>{{ $booking->pickup_date->format('d M Y') }} - {{ $booking->return_date->format('d M Y') }} ({{ $booking->total_day }} Hari)</span>
                    </div>
                    <div class="inv-row">
                        <span>Layanan Sopir</span>
                        <span>{{ $booking->driver_price > 0 ? 'Ya (Dengan Sopir)' : 'Tidak (Lepas Kunci)' }}</span>
                    </div>
                    <div class="inv-row">
                        <span>Status Pembayaran</span>
                        <span style="color:#F59E0B;background:rgba(245,158,11,.15);padding:2px 8px;border-radius:4px;">Menunggu Pembayaran</span>
                    </div>
                </div>

                <div class="inv-total">
                    <div class="inv-total-label">TOTAL TAGIHAN</div>
                    <div class="inv-total-val">Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</div>
                </div>

                <div class="payment-instruction">
                    <h4><i class="fas fa-info-circle" style="color:#D4AF37;"></i> Informasi Pembayaran</h4>
                    <p>Pesanan Anda telah kami terima. Untuk melanjutkan proses konfirmasi, silakan hubungi admin kami melalui WhatsApp dengan melampirkan Kode Booking Anda.</p>
                </div>

                @php
                    $waMsg = "Halo Siliwangi Rental, saya ingin konfirmasi pembayaran untuk pesanan: \n*Kode Booking:* " . $booking->booking_code . "\n*Kendaraan:* " . $booking->car->car_name . "\n*Total:* Rp " . number_format($booking->grand_total, 0, ',', '.');
                @endphp
                <a href="https://wa.me/6281234567890?text={{ urlencode($waMsg) }}" target="_blank" class="btn-wa">
                    <i class="fab fa-whatsapp fa-lg"></i> Konfirmasi via WhatsApp
                </a>
                
                <a href="{{ url('/') }}" class="btn-home">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</div>
@endsection
