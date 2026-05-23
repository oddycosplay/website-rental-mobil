<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $booking->booking_code }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #334155;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .invoice-container {
            max-width: 800px;
            margin: auto;
            padding: 40px;
            background: #fff;
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
            width: 300px;
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
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            background: #f1f5f9;
        }
        .status-paid { background: #dcfce7; color: #15803d; }
        .status-pending { background: #fef9c3; color: #a16207; }
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
                <span>Subtotal <br><small style="font-size: 10px; color: #64748b;">(Sewa mobil + Operasional + Admin)</small></span>
                <span>Rp {{ number_format(($booking->price * $booking->total_day) + $booking->driver_price + $booking->extra_price, 0, ',', '.') }}</span>
            </div>
            <div class="total-row" style="color: {{ $booking->discount > 0 ? '#ef4444' : '#64748b' }};">
                <span>Potongan Promo {!! $booking->promo ? '<br><small style="font-size: 10px;">(' . $booking->promo->code . ')</small>' : '' !!}</span>
                <span>- Rp {{ number_format($booking->discount, 0, ',', '.') }}</span>
            </div>
            
            <div class="total-row" style="color: #64748b;">
                <span>Pajak PPN (12%)</span>
                <span>+ Rp {{ number_format($booking->tax, 0, ',', '.') }}</span>
            </div>
            <div class="total-row grand">
                <span>TOTAL AKHIR</span>
                <span>Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih telah mempercayakan perjalanan Anda kepada Siliwangi Rental.</p>
            <p>Invoice ini diterbitkan secara otomatis dan sah tanpa tanda tangan basah.</p>
            
            @if($booking->payment_status == 'pending')
            <div style="margin-top: 30px;">
                <button id="pay-button" style="background: #D4AF37; color: #0F172A; border: none; padding: 15px 40px; border-radius: 12px; font-weight: 800; font-size: 16px; cursor: pointer; box-shadow: 0 10px 20px rgba(212,175,55,0.3); text-transform: uppercase; letter-spacing: 1px;">
                    <i class="fas fa-credit-card" style="margin-right: 10px;"></i> Bayar Sekarang
                </button>
                <p style="font-size: 10px; color: #64748b; margin-top: 15px;">Aman & Terenkripsi via Midtrans</p>
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
            @endif

            <p style="margin-top: 30px; font-weight: bold;">Siliwangi Rental &copy; 2026</p>
        </div>
    </div>
</body>
</html>
