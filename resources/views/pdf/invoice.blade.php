<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $booking->booking_code }}</title>
    <!-- FontAwesome for Premium Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            color: #334155;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .invoice-container {
            max-width: 800px;
            margin: auto;
            padding: 40px;
            background: #fff;
            box-sizing: border-box;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #D4AF37;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo-text {
            font-size: 28px;
            font-weight: 800;
            color: #0F172A;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .logo-text span {
            color: #D4AF37;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h1 {
            margin: 0;
            font-size: 32px;
            color: #D4AF37;
        }
        .info-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .info-block h4 {
            margin: 0 0 10px 0;
            text-transform: uppercase;
            font-size: 12px;
            color: #64748b;
            letter-spacing: 1px;
        }
        .info-block p {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table th {
            background: #0F172A;
            color: #fff;
            text-align: left;
            padding: 12px 15px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .table td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        .totals {
            margin-left: auto;
            width: 320px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
        }
        .total-row.grand {
            border-top: 2px solid #D4AF37;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 18px;
            font-weight: 800;
            color: #0F172A;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 11px;
            color: #94a3b8;
            text-align: center;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            background: #f1f5f9;
            letter-spacing: 0.5px;
        }
        .status-paid { background: #dcfce7; color: #15803d; }
        .status-pending { background: #fef9c3; color: #a16207; }

        /* Elegant Button Hover Transition */
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(212,175,55,0.4) !important;
        }

        /* Premium Screen Presentation styling */
        @media screen {
            body {
                background: #0B0F19;
                background-image: radial-gradient(circle at 10% 20%, rgba(212, 175, 55, 0.05) 0%, transparent 40%), radial-gradient(circle at 90% 80%, rgba(212, 175, 55, 0.05) 0%, transparent 40%);
                padding: 50px 20px;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .invoice-container {
                border-radius: 24px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
                border: 1px solid rgba(255, 255, 255, 0.05);
                animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Print styling to completely hide buttons and formatting */
        @media print {
            .no-print, button, a {
                display: none !important;
            }
            body {
                background: #fff !important;
                color: #334155 !important;
            }
            .invoice-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="logo">
                <div class="logo-text">Siliwangi<span>Rental</span></div>
                <div style="font-size: 12px; color: #64748b; margin-top: 5px;">Premium Car Rental Services</div>
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <p style="font-size: 14px; color: #64748b;">#{{ $booking->booking_code }}</p>
                <div class="status-badge {{ $booking->payment_status == 'paid' ? 'status-paid' : 'status-pending' }}">
                    {{ strtoupper($booking->payment_status) }}
                </div>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-block">
                <h4>Ditagihkan Ke:</h4>
                <p>{{ $booking->customer->name }}</p>
                <p>{{ $booking->customer->email }}</p>
                <p>{{ $booking->customer->phone }}</p>
            </div>
            <div class="info-block" style="text-align: right;">
                <h4>Tanggal Sewa:</h4>
                <p>{{ $booking->pickup_date->format('d M Y') }} - {{ $booking->return_date->format('d M Y') }}</p>
                <h4 style="margin-top: 15px;">Metode Pembayaran:</h4>
                <p>{{ strtoupper($booking->payment_method ?? 'Transfer Bank / Midtrans') }}</p>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Deskripsi Layanan</th>
                    <th>Durasi</th>
                    <th style="text-align: right;">Harga Satuan</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Sewa Mobil: {{ $booking->car->car_name }}</strong><br>
                        <span style="font-size: 11px; color: #64748b;">{{ $booking->car->plate_number }}</span>
                    </td>
                    <td>{{ $booking->pickup_date->diffInDays($booking->return_date) }} Hari</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->car->price_per_day, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->price * $booking->total_day, 0, ',', '.') }}</td>
                </tr>
                @if($booking->with_driver)
                <tr>
                    <td><strong>Layanan Sopir</strong></td>
                    <td>{{ $booking->total_day }} Hari</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->driver_price / $booking->total_day, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->driver_price, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($booking->delivery_type && $booking->delivery_type !== 'none')
                <tr>
                    <td>
                        <strong>Layanan Pengantaran Mobil</strong><br>
                        <span style="font-size: 11px; color: #64748b;">Tipe Pengantaran: {{ ucfirst($booking->delivery_type) }}</span>
                    </td>
                    <td>-</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->delivery_fee, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->delivery_fee, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($booking->pickup_type && $booking->pickup_type !== 'none')
                <tr>
                    <td>
                        <strong>Layanan Penjemputan Mobil</strong><br>
                        <span style="font-size: 11px; color: #64748b;">Tipe Penjemputan: {{ ucfirst($booking->pickup_type) }}</span>
                    </td>
                    <td>-</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->pickup_fee, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->pickup_fee, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($booking->ojol_fee > 0)
                <tr>
                    <td>
                        <strong>Biaya Ojek Online (Ojol)</strong><br>
                        <span style="font-size: 11px; color: #64748b;">Biaya transportasi penjemputan/pengantaran driver</span>
                    </td>
                    <td>-</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->ojol_fee, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->ojol_fee, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($booking->extra_price > 0)
                <tr>
                    <td><strong>Layanan Operasional & Admin</strong></td>
                    <td>-</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->extra_price, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->extra_price, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($booking->tax > 0)
                <tr>
                    <td><strong>Pajak PPN (12%)</strong></td>
                    <td>-</td>
                    <td style="text-align: right;">-</td>
                    <td style="text-align: right;">Rp {{ number_format($booking->tax, 0, ',', '.') }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        <div class="totals">
            <div class="total-row">
                <span>Sewa Mobil <br><small style="font-size: 10px; color: #64748b;">({{ $booking->car->car_name }})</small></span>
                <span>Rp {{ number_format($booking->price * $booking->total_day, 0, ',', '.') }}</span>
            </div>
            @if($booking->with_driver)
            <div class="total-row">
                <span>Layanan Sopir</span>
                <span>Rp {{ number_format($booking->driver_price, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($booking->delivery_type && $booking->delivery_type !== 'none')
            <div class="total-row">
                <span>Biaya Pengantaran <br><small style="font-size: 10px; color: #64748b;">({{ ucfirst($booking->delivery_type) }})</small></span>
                <span>Rp {{ number_format($booking->delivery_fee, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($booking->pickup_type && $booking->pickup_type !== 'none')
            <div class="total-row">
                <span>Biaya Penjemputan <br><small style="font-size: 10px; color: #64748b;">({{ ucfirst($booking->pickup_type) }})</small></span>
                <span>Rp {{ number_format($booking->pickup_fee, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($booking->ojol_fee > 0)
            <div class="total-row">
                <span>Biaya Ojol</span>
                <span>Rp {{ number_format($booking->ojol_fee, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($booking->extra_price > 0)
            <div class="total-row">
                <span>Layanan Operasional & Admin</span>
                <span>Rp {{ number_format($booking->extra_price, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($booking->discount > 0)
            <div class="total-row" style="color: #ef4444;">
                <span>Potongan Promo {!! $booking->promo ? '<br><small style="font-size: 10px;">(' . $booking->promo->code . ')</small>' : '' !!}</span>
                <span>- Rp {{ number_format($booking->discount, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($booking->tax > 0)
            <div class="total-row" style="color: #64748b;">
                <span>Pajak PPN (12%)</span>
                <span>+ Rp {{ number_format($booking->tax, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="total-row grand">
                <span>TOTAL AKHIR</span>
                <span>Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih telah mempercayakan perjalanan Anda kepada Siliwangi Rental.</p>
            <p>Invoice ini diterbitkan secara otomatis dan sah tanpa tanda tangan basah.</p>
            
            @if($booking->payment_status == 'pending' || $booking->payment_status == 'unpaid')
            <div style="margin-top: 30px;" class="no-print">
                <button id="pay-button" class="btn-hover" style="background: #D4AF37; color: #0F172A; border: none; padding: 15px 40px; border-radius: 12px; font-weight: 800; font-size: 16px; cursor: pointer; box-shadow: 0 10px 20px rgba(212,175,55,0.3); text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                    <i class="fas fa-credit-card" style="margin-right: 10px;"></i> Bayar Sekarang
                </button>
                <p style="font-size: 10px; color: #64748b; margin-top: 15px;">Aman & Terenkripsi via Midtrans</p>
            </div>

            <div style="margin-top: 20px;" class="no-print">
                <a href="{{ url('/') }}" style="color: #64748b; text-decoration: none; font-weight: 600; font-size: 14px; transition: color 0.2s;">
                    <i class="fas fa-arrow-left" style="margin-right: 5px;"></i> Kembali ke Beranda
                </a>
            </div>
            
            <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
            <script>
                const payButton = document.getElementById('pay-button');
                payButton.onclick = function() {
                    snap.pay('{{ $booking->payment->snap_token ?? "" }}', {
                        onSuccess: function(result) {
                            window.location.reload();
                        },
                        onPending: function(result) {
                            window.location.reload();
                        },
                        onError: function(result) {
                            alert("Pembayaran gagal!");
                        }
                    });
                };
            </script>
            @elseif($booking->payment_status == 'paid')
            <div style="margin-top: 30px; display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;" class="no-print">
                <a href="{{ route('invoice', $booking->booking_code) }}?download=1" class="btn-hover" style="display: inline-block; background: #D4AF37; color: #0F172A; text-decoration: none; padding: 15px 40px; border-radius: 12px; font-weight: 800; font-size: 16px; cursor: pointer; box-shadow: 0 10px 20px rgba(212,175,55,0.2); text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                    <i class="fas fa-file-pdf" style="margin-right: 10px;"></i> Unduh PDF
                </a>
                <a href="{{ url('/') }}" style="display: inline-block; background: #0F172A; color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 12px; font-weight: 800; font-size: 16px; cursor: pointer; border: 1px solid rgba(255,255,255,0.1); text-transform: uppercase; letter-spacing: 1px; transition: all 0.2s ease;">
                    <i class="fas fa-home" style="margin-right: 10px;"></i> Beranda
                </a>
            </div>
            @endif
 
            <p style="margin-top: 40px; font-weight: bold;">Siliwangi Rental &copy; 2026</p>
        </div>
    </div>
</body>
</html>
