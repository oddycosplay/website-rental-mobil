@extends('layouts.admin')

@section('title', 'Pembayaran & Keuangan - Siliwangi Admin')

@section('styles')
<style>
    /* Fintech Summary Cards */
    .finance-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    .finance-card {
        padding: 24px;
        border-radius: var(--radius-lg);
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        position: relative;
        overflow: hidden;
    }
    .finance-card::after {
        content: '';
        position: absolute;
        top: 0; right: 0;
        width: 100px; height: 100px;
        background: linear-gradient(135deg, transparent, rgba(212, 175, 55, 0.05));
        border-bottom-left-radius: 100%;
    }
    .finance-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    .finance-title {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .finance-icon {
        width: 36px; height: 36px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px;
    }
    .finance-amount {
        font-size: 28px;
        font-weight: 800;
        font-family: 'Poppins', sans-serif;
        margin-bottom: 8px;
        color: var(--text-main);
    }
    .finance-trend {
        font-size: 12px;
        font-weight: 600;
        display: flex; align-items: center; gap: 4px;
    }
    .trend-up { color: var(--success); }
    .trend-down { color: var(--danger); }

    /* Table Styles */
    .table-container { overflow-x: auto; }
    .admin-table { width: 100%; border-collapse: collapse; text-align: left; }
    .admin-table th {
        background: var(--bg-color); padding: 12px 16px; font-size: 12px;
        text-transform: uppercase; letter-spacing: 1px; font-weight: 600;
        color: var(--text-muted); border-bottom: 1px solid var(--card-border);
    }
    .admin-table td { padding: 16px; border-bottom: 1px solid var(--card-border); font-size: 13px; vertical-align: middle; }
    .admin-table tbody tr { transition: background 0.2s; }
    .admin-table tbody tr:hover { background: var(--bg-color); }
    
    .inv-pill {
        font-family: monospace; font-weight: 600; font-size: 13px;
        color: var(--secondary);
    }
    
    .payment-method {
        display: flex; align-items: center; gap: 8px;
    }
    .payment-logo {
        height: 20px; width: auto; max-width: 60px;
        object-fit: contain;
        background: #fff; padding: 2px 6px; border-radius: 4px; border: 1px solid #e2e8f0;
    }

    .amount-text { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 14px; }
    .amount-in { color: var(--success); }
    .amount-out { color: var(--danger); }

    /* Modal Detail Payment */
    .modal-overlay {
        display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 100;
        align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s;
    }
    .modal-overlay.show { display: flex; opacity: 1; }
    .modal-content {
        background: var(--card-bg); border: 1px solid var(--card-border);
        border-radius: var(--radius-lg); width: 100%; max-width: 600px;
        transform: translateY(20px); transition: transform 0.3s;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3); overflow: hidden;
    }
    .modal-overlay.show .modal-content { transform: translateY(0); }
    
    .modal-header {
        padding: 20px 24px; border-bottom: 1px solid var(--card-border);
        display: flex; justify-content: space-between; align-items: center; background: var(--bg-color);
    }
    .modal-title { font-size: 1.1rem; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .close-modal { font-size: 20px; color: var(--text-muted); cursor: pointer; }
    .close-modal:hover { color: var(--danger); }
    
    .modal-body { padding: 24px; }
    
    .receipt-box {
        background: var(--bg-color); border: 1px dashed var(--card-border);
        border-radius: var(--radius-md); padding: 20px; margin-bottom: 20px;
        position: relative;
    }
    .receipt-header { text-align: center; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--card-border); }
    .receipt-header h3 { font-size: 24px; font-weight: 800; color: var(--secondary); font-family: 'Poppins', sans-serif; }
    .receipt-header p { font-size: 13px; color: var(--text-muted); }
    
    .receipt-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 13px; }
    .receipt-row .lbl { color: var(--text-muted); }
    .receipt-row .val { font-weight: 600; text-align: right; }
    
    /* JSON Viewer Mockup */
    .json-box {
        background: #1E293B; color: #A7F3D0; font-family: monospace; font-size: 11px;
        padding: 16px; border-radius: var(--radius-sm); overflow-x: auto; margin-top: 16px;
        border: 1px solid #334155;
    }
    .json-key { color: #93C5FD; }
    .json-string { color: #FCD34D; }

    .modal-footer {
        padding: 16px 24px; border-top: 1px solid var(--card-border);
        display: flex; justify-content: flex-end; gap: 12px; background: var(--bg-color);
    }
    .chart-center-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
    .dot {
        height: 10px;
        width: 10px;
        border-radius: 50%;
        display: inline-block;
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Pembayaran & Keuangan</h1>
        <div class="breadcrumb">
            <span>Siliwangi Admin</span>
            <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
            <span style="color: var(--secondary)">Pembayaran</span>
        </div>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.payments.export', request()->all()) }}" class="btn btn-outline" style="margin-right: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-download"></i> Export Laporan
        </a>
        <button class="btn btn-gold" id="syncMidtransBtn" onclick="triggerSync()">
            <i class="fas fa-sync-alt" id="syncIcon"></i> Sync Midtrans
        </button>
    </div>
</div>

<!-- Fintech Summary Cards -->
<div class="finance-grid">
    <div class="finance-card">
        <div class="finance-header">
            <span class="finance-title">Total Pendapatan (Bulan Ini)</span>
            <div class="finance-icon" style="background: var(--success-bg); color: var(--success-text);">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
        <div class="finance-amount">Rp {{ number_format($stats['total_income_month'] / 1000000, 1, ',', '.') }} Jt</div>
        <div class="finance-trend trend-up">
            <i class="fas fa-arrow-up"></i> 12.5% vs bulan lalu
        </div>
    </div>
    
    <div class="finance-card">
        <div class="finance-header">
            <span class="finance-title">Pembayaran Menunggu</span>
            <div class="finance-icon" style="background: var(--warning-bg); color: var(--warning-text);">
                <i class="fas fa-hourglass-half"></i>
            </div>
        </div>
        <div class="finance-amount">Rp {{ number_format($stats['pending_payment_amount'] / 1000000, 1, ',', '.') }} Jt</div>
        <div class="finance-trend" style="color: var(--text-muted)">
            <span>{{ $stats['pending_payment_count'] }} transaksi menunggu</span>
        </div>
    </div>

    <div class="finance-card">
        <div class="finance-header">
            <span class="finance-title">Refund / Batal</span>
            <div class="finance-icon" style="background: var(--danger-bg); color: var(--danger-text);">
                <i class="fas fa-undo-alt"></i>
            </div>
        </div>
        <div class="finance-amount">Rp {{ number_format($stats['refund_amount'] / 1000000, 1, ',', '.') }} Jt</div>
        <div class="finance-trend trend-down">
            <i class="fas fa-arrow-down"></i> {{ $stats['refund_count'] }} transaksi direfund
        </div>
    </div>

    <div class="finance-card">
        <div class="finance-header">
            <span class="finance-title">Total Transaksi Selesai</span>
            <div class="finance-icon" style="background: var(--info-bg); color: var(--info-text);">
                <i class="fas fa-university"></i>
            </div>
        </div>
        <div class="finance-amount">Rp {{ number_format($stats['midtrans_balance'] / 1000000, 1, ',', '.') }} Jt</div>
        <div class="finance-trend" style="color: var(--text-muted)">
            <span>Siap ditarik (Withdraw)</span>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 24px;">
    <!-- Line Chart: Monthly performance -->
    <div class="finance-card" style="min-height: 380px; display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                <div>
                    <h5 style="margin: 0 0 4px 0; font-size: 15px; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-chart-line" style="color: var(--secondary);"></i> Tren Kinerja Keuangan
                    </h5>
                    <span style="font-size: 12px; color: var(--text-muted);">Analisis Pendapatan Bulanan (Inflow) vs Pengeluaran Operasional (Outflow)</span>
                </div>
                <span class="badge" style="background: rgba(214, 175, 55, 0.1); color: var(--secondary); font-size: 11px; padding: 6px 12px; border-radius: 30px; font-weight: 600;">
                    <i class="far fa-calendar-alt"></i> 6 Bulan Terakhir
                </span>
            </div>
            <div style="position: relative; height: 260px; width: 100%;">
                <canvas id="financeDetailChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Doughnut Chart: Payment status distribution -->
    <div class="finance-card" style="min-height: 380px; display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <div style="margin-bottom: 20px;">
                <h5 style="margin: 0 0 4px 0; font-size: 15px; font-weight: 700; color: var(--text-main);">Status Pembayaran</h5>
                <span style="font-size: 12px; color: var(--text-muted);">Distribusi transaksi real-time berdasarkan status</span>
            </div>
            
            <div style="position: relative; height: 180px; width: 100%; margin-bottom: 20px;">
                <canvas id="financeStatusChart"></canvas>
                <div class="chart-center-content">
                    <div style="font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); font-weight: 600; margin-bottom: 2px;">SUCCESS</div>
                    <div id="successRateVal" style="font-size: 24px; font-weight: 800; color: var(--text-main); font-family: 'Poppins', sans-serif;">0%</div>
                </div>
            </div>

            <div style="display: flex; justify-content: space-around; font-size: 12px; font-weight: 600;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span class="dot" style="background: #10B981;"></span>
                    <span style="color: var(--text-main);">Success ({{ $paymentStats['success'] ?? 0 }})</span>
                </div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span class="dot" style="background: #F59E0B;"></span>
                    <span style="color: var(--text-main);">Pending ({{ $paymentStats['pending'] ?? 0 }})</span>
                </div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span class="dot" style="background: #EF4444;"></span>
                    <span style="color: var(--text-main);">Failed ({{ $paymentStats['failed'] ?? 0 }})</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <form action="{{ route('admin.finance.payments') }}" method="GET" id="filterForm">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 16px;">
            <div class="search-box" style="display: block; width: 350px;">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID Invoice, ID Pesanan, atau Nama Pelanggan..." onkeypress="if(event.key === 'Enter') this.form.submit();">
            </div>
            <div style="display: flex; gap: 12px;">
                <input type="date" name="date" value="{{ request('date') }}" class="btn btn-outline" style="padding: 8px 12px; border-radius: 6px; font-size: 13px; color: var(--text-main);" onchange="this.form.submit()">
                <select name="method" class="btn btn-outline" style="padding: 8px 12px; border-radius: 6px; font-size: 13px;" onchange="this.form.submit()">
                    <option value="">Semua Metode</option>
                    <option value="bank_transfer" {{ request('method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="qris" {{ request('method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="gopay" {{ request('method') === 'gopay' ? 'selected' : '' }}>GoPay</option>
                    <option value="shopeepay" {{ request('method') === 'shopeepay' ? 'selected' : '' }}>ShopeePay</option>
                    <option value="credit_card" {{ request('method') === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                </select>
            </div>
        </div>
    </form>

    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Invoice / Waktu</th>
                    <th>Pelanggan / Pemesanan</th>
                    <th>Metode Pembayaran</th>
                    <th>Nominal</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td>
                        <div class="inv-pill">{{ $payment->payment_code }}</div>
                        <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">{{ $payment->payment_date ? $payment->payment_date->translatedFormat('d M Y, H:i') : '-' }} WIB</div>
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ $payment->booking->customer->name ?? '-' }}</div>
                        <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;"><i class="fas fa-car" style="font-size: 10px;"></i> {{ $payment->booking->booking_code ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="payment-method">
                            <span style="font-weight: 600; font-size: 12px;">{{ strtoupper(str_replace('_', ' ', $payment->payment_method ?? 'Midtrans')) }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="amount-text amount-in">+ Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</div>
                    </td>
                    <td>
                        @php
                            $statusClass = [
                                'success' => 'success',
                                'settlement' => 'success',
                                'pending' => 'warning',
                                'failed' => 'danger',
                                'expire' => 'danger',
                                'refund' => 'info',
                            ][$payment->payment_status] ?? 'secondary';
                        @endphp
                        <span class="badge badge-{{ $statusClass }}">{{ strtoupper($payment->payment_status) }}</span>
                    </td>
                    <td style="text-align: right;">
                        <button class="btn btn-outline" style="padding: 6px 12px;" onclick="openModal('paymentDetailModal', '{{ $payment->id }}')">Detail</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <i class="fas fa-receipt" style="font-size: 32px; margin-bottom: 12px; display: block; opacity: 0.3;"></i>
                        Tidak ada transaksi pembayaran yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 20px;">
        {{ $payments->links() }}
    </div>
</div>

<!-- PAYMENT DETAIL MODAL -->
<div class="modal-overlay" id="paymentDetailModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title"><i class="fas fa-receipt" style="color: var(--secondary)"></i> Detail Transaksi</h2>
            <i class="fas fa-times close-modal" onclick="closeModal('paymentDetailModal')"></i>
        </div>
        <div class="modal-body">
            
            <div class="receipt-box">
                <div class="receipt-header">
                    <p>Total Pembayaran</p>
                    <h3 id="detail-paid-amount">Rp 0</h3>
                    <span class="badge badge-success" style="margin-top: 8px;">Pelunasan (Berhasil)</span>
                </div>
                
                <div class="receipt-row">
                    <span class="lbl">ID Invoice</span>
                    <span class="val inv-pill" id="detail-invoice-code" style="color: var(--text-main);">-</span>
                </div>
                <div class="receipt-row">
                    <span class="lbl">ID Pesanan / Pemesanan</span>
                    <span class="val" id="detail-booking-code">-</span>
                </div>
                <div class="receipt-row">
                    <span class="lbl">Pelanggan</span>
                    <span class="val" id="detail-customer-name">-</span>
                </div>
                <div class="receipt-row">
                    <span class="lbl">Waktu Transaksi</span>
                    <span class="val" id="detail-payment-date">-</span>
                </div>
                <div class="receipt-row" style="margin-top: 16px; padding-top: 16px; border-top: 1px dashed var(--card-border);">
                    <span class="lbl">Gerbang Pembayaran</span>
                    <span class="val" id="detail-gateway-name" style="color: var(--secondary);">Midtrans</span>
                </div>
                <div class="receipt-row">
                    <span class="lbl">Metode</span>
                    <span class="val" id="detail-payment-method">-</span>
                </div>
                <div class="receipt-row">
                    <span class="lbl">ID Transaksi / VA</span>
                    <span class="val" id="detail-va-number" style="font-family: monospace; font-size: 14px;">-</span>
                </div>
            </div>

            <div id="payment-logs-container" style="margin-top: 20px;">
                <div style="margin-bottom: 8px; font-size: 13px; font-weight: 600;">Riwayat Status (Log):</div>
                <div id="payment-logs-list" style="max-height: 200px; overflow-y: auto; border: 1px solid var(--card-border); border-radius: var(--radius-sm); font-size: 11px;">
                    <!-- Logs will be injected here -->
                </div>
            </div>

            <div style="margin-top: 20px; margin-bottom: 8px; font-size: 13px; font-weight: 600;">Log Respon Gerbang:</div>
            <pre class="json-box" id="json-response-box" style="margin: 0; padding: 16px; border-radius: 6px; font-size: 11px; overflow-x: auto; background: var(--card-bg-hover); border: 1px solid var(--card-border); color: var(--text-main); font-family: monospace; max-height: 250px;"></pre>

        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('paymentDetailModal')">Tutup</button>
            <button class="btn btn-gold" id="printReceiptBtn"><i class="fas fa-print"></i> Cetak Struk</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Financial Detail Chart
    const ctxFin = document.getElementById("financeDetailChart");
    if(ctxFin) {
        new Chart(ctxFin, {
            type: 'line',
            data: {
                labels: {!! json_encode($months ?? []) !!},
                datasets: [{
                    label: "Pendapatan (Inflow)",
                    borderColor: "#10B981",
                    backgroundColor: "rgba(16, 185, 129, 0.05)",
                    borderWidth: 3,
                    pointBackgroundColor: "#10B981",
                    pointBorderColor: "#fff",
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: true,
                    data: {!! json_encode($revenueData ?? []) !!},
                }, {
                    label: "Pengeluaran (Outflow)",
                    borderColor: "#EF4444",
                    backgroundColor: "rgba(239, 68, 68, 0.05)",
                    borderWidth: 3,
                    pointBackgroundColor: "#EF4444",
                    pointBorderColor: "#fff",
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: true,
                    data: {!! json_encode($expenseData ?? []) !!},
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' }
                        }
                    },
                    tooltip: {
                        mode: 'index', intersect: false, backgroundColor: '#0F172A', padding: 12, cornerRadius: 8,
                        titleFont: { family: 'Poppins', size: 13 }, bodyFont: { family: 'Inter', size: 12 }
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5], color: 'rgba(0,0,0,0.05)' }, ticks: { font: { family: 'Inter' } } },
                    x: { grid: { display: false }, ticks: { font: { family: 'Inter' } } }
                }
            }
        });
    }

    // Payment Status Chart (Doughnut)
    const ctxStatus = document.getElementById("financeStatusChart");
    if(ctxStatus) {
        const statsData = {!! json_encode($paymentStats['data'] ?? []) !!};
        const total = statsData.reduce((a, b) => a + b, 0);
        const successRate = total > 0 ? Math.round((statsData[0] / total) * 100) : 0;
        
        document.getElementById('successRateVal').innerText = successRate + '%';

        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($paymentStats['labels'] ?? []) !!},
                datasets: [{
                    data: statsData,
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                    borderWidth: 0, hoverOffset: 15
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '80%',
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#0F172A', padding: 12, cornerRadius: 8, titleFont: { family: 'Poppins', size: 13 }, bodyFont: { family: 'Inter', size: 12 } }
                }
            }
        });
    }
});

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.style.position = 'fixed';
        toast.style.top = '24px';
        toast.style.right = '24px';
        toast.style.padding = '16px 24px';
        toast.style.borderRadius = '8px';
        toast.style.background = type === 'success' ? '#10B981' : '#EF4444';
        toast.style.color = '#FFF';
        toast.style.fontWeight = 'bold';
        toast.style.fontSize = '14px';
        toast.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';
        toast.style.zIndex = '9999';
        toast.style.display = 'flex';
        toast.style.alignItems = 'center';
        toast.style.gap = '10px';
        toast.style.transition = 'all 0.3s ease';
        toast.style.transform = 'translateY(-20px)';
        toast.style.opacity = '0';
        
        toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> <span>${message}</span>`;
        document.body.appendChild(toast);
        
        toast.offsetHeight;
        
        toast.style.transform = 'translateY(0)';
        toast.style.opacity = '1';
        
        setTimeout(() => {
            toast.style.transform = 'translateY(-20px)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function syntaxHighlightJson(json) {
        if (typeof json !== 'string') {
            json = JSON.stringify(json, undefined, 2);
        }
        json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+-]?\d+)?)/g, function (match) {
            var cls = 'number';
            if (/^"/.test(match)) {
                if (/:$/.test(match)) {
                    cls = 'key';
                } else {
                    cls = 'string';
                }
            } else if (/true|false/.test(match)) {
                cls = 'boolean';
            } else if (/null/.test(match)) {
                cls = 'null';
            }
            if (cls === 'key') {
                return '<span class="json-key" style="color: var(--secondary); font-weight: bold;">' + match + '</span>';
            } else {
                return '<span class="json-string" style="color: #10B981;">' + match + '</span>';
            }
        });
    }

    async function openModal(id, paymentId) {
        if (id === 'paymentDetailModal' && paymentId) {
            try {
                const response = await fetch(`/dashboard/finance/payments/${paymentId}`);
                const data = await response.json();
                
                // Update modal elements
                document.getElementById('detail-paid-amount').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.paid_amount);
                document.getElementById('detail-invoice-code').innerText = data.payment_code;
                document.getElementById('detail-booking-code').innerText = data.booking?.booking_code || '-';
                document.getElementById('detail-customer-name').innerText = data.booking?.customer?.name || '-';
                
                let formattedDate = '-';
                if (data.payment_date) {
                    const dateObj = new Date(data.payment_date);
                    formattedDate = dateObj.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    }) + ', ' + dateObj.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit'
                    }) + ' WIB';
                }
                document.getElementById('detail-payment-date').innerText = formattedDate;
                document.getElementById('detail-payment-method').innerText = data.payment_method ? data.payment_method.toUpperCase().replace('_', ' ') : 'MIDTRANS';
                document.getElementById('detail-va-number').innerText = data.transaction_id || '-';
                
                // Logs
                const logsList = document.getElementById('payment-logs-list');
                logsList.innerHTML = '';
                if (data.payment_logs && data.payment_logs.length > 0) {
                    data.payment_logs.forEach(log => {
                        const date = log.created_at ? new Date(log.created_at).toLocaleString('id-ID') : '-';
                        logsList.innerHTML += `
                            <div style="padding: 10px; border-bottom: 1px solid var(--card-border); display: flex; justify-content: space-between;">
                                <span style="font-weight: 600; color: var(--secondary);">${log.status.toUpperCase()}</span>
                                <span style="color: var(--text-muted);">${date}</span>
                            </div>
                        `;
                    });
                } else {
                    logsList.innerHTML = '<div style="padding: 20px; text-align: center; color: var(--text-muted);">Tidak ada data log.</div>';
                }

                const badge = document.querySelector('#paymentDetailModal .badge');
                const isPaid = data.payment_status === 'settlement' || data.payment_status === 'success';
                badge.className = 'badge badge-' + (isPaid ? 'success' : (data.payment_status === 'pending' ? 'warning' : 'danger'));
                badge.innerText = data.payment_status.toUpperCase();
                
                // Gateway Response Box
                const jsonBox = document.getElementById('json-response-box');
                if (data.midtrans_response) {
                    jsonBox.innerHTML = syntaxHighlightJson(data.midtrans_response);
                } else {
                    jsonBox.innerHTML = '<span style="color: var(--text-muted);">Tidak ada log respon (pembayaran pending/manual).</span>';
                }
                
            } catch (e) {
                console.error('Error fetching payment details:', e);
            }
        }
        document.getElementById(id).classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
        document.body.style.overflow = '';
    }

    // Close modal when clicking outside
    document.getElementById('paymentDetailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal('paymentDetailModal');
        }
    });

    // Print Receipt Action
    document.getElementById('printReceiptBtn').addEventListener('click', function() {
        const receiptContent = document.querySelector('#paymentDetailModal .receipt-box').innerHTML;
        const printWindow = window.open('', '_blank', 'width=600,height=600');
        printWindow.document.write(
            '<' + 'html>' +
            '<' + 'head>' +
            '    <' + 'title>Cetak Struk Pembayaran</' + 'title>' +
            '    <' + 'style>' +
            '        body { font-family: \'Poppins\', sans-serif; padding: 40px; color: #333; line-height: 1.5; }' +
            '        .receipt-box { border: 1px dashed #ccc; padding: 30px; border-radius: 8px; background: #fafafa; }' +
            '        .receipt-header { text-align: center; margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px; }' +
            '        .receipt-header h3 { font-size: 28px; margin: 10px 0; color: #D4AF37; }' +
            '        .receipt-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 14px; }' +
            '        .receipt-row .lbl { color: #666; }' +
            '        .receipt-row .val { font-weight: bold; text-align: right; }' +
            '        .inv-pill { font-family: monospace; font-weight: bold; }' +
            '        .badge { display: inline-block; padding: 6px 12px; border-radius: 50px; font-size: 11px; font-weight: bold; text-transform: uppercase; }' +
            '        .badge-success { background: #dcfce7; color: #15803d; }' +
            '        .badge-warning { background: #fef9c3; color: #a16207; }' +
            '        .badge-danger { background: #fee2e2; color: #b91c1c; }' +
            '    </' + 'style>' +
            '</' + 'head>' +
            '<' + 'body>' +
            '    <' + 'div class="receipt-box">' +
            receiptContent +
            '    </' + 'div>' +
            '    <' + 'script>' +
            '        window.onload = function() { window.print(); setTimeout(function() { window.close(); }, 500); };' +
            '    </' + 'script>' +
            '</' + 'body>' +
            '</' + 'html>'
        );
        printWindow.document.close();
    });

    // AJAX Midtrans Sync
    async function triggerSync() {
        const btn = document.getElementById('syncMidtransBtn');
        const icon = document.getElementById('syncIcon');
        
        // Add rotating class & disable
        btn.disabled = true;
        icon.classList.add('fa-spin');
        btn.style.opacity = '0.7';
        btn.innerHTML = `<i class="fas fa-sync-alt fa-spin"></i> Syncing...`;
        
        try {
            const response = await fetch('{{ route('admin.payments.sync') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message, 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showToast(result.message || 'Gagal melakukan sinkronisasi.', 'error');
                btn.disabled = false;
                icon.classList.remove('fa-spin');
                btn.style.opacity = '1';
                btn.innerHTML = `<i class="fas fa-sync-alt" id="syncIcon"></i> Sync Midtrans`;
            }
        } catch (error) {
            showToast('Terjadi kesalahan jaringan atau server error.', 'error');
            btn.disabled = false;
            icon.classList.remove('fa-spin');
            btn.style.opacity = '1';
            btn.innerHTML = `<i class="fas fa-sync-alt" id="syncIcon"></i> Sync Midtrans`;
        }
    }
</script>
@endsection
