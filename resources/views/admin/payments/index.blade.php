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
        <button class="btn btn-outline" style="margin-right: 8px;">
            <i class="fas fa-download"></i> Export Laporan
        </button>
        <button class="btn btn-gold">
            <i class="fas fa-sync-alt"></i> Sync Midtrans
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
        <div class="finance-amount">Rp {{ number_format($stats['total_income_month'] / 1000000, 1) }}M</div>
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
        <div class="finance-amount">Rp {{ number_format($stats['pending_payment_amount'] / 1000000, 1) }}M</div>
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
        <div class="finance-amount">Rp {{ number_format($stats['refund_amount'] / 1000000, 1) }}M</div>
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
        <div class="finance-amount">Rp {{ number_format($stats['midtrans_balance'] / 1000000, 1) }}M</div>
        <div class="finance-trend" style="color: var(--text-muted)">
            <span>Siap ditarik (Withdraw)</span>
        </div>
    </div>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 16px;">
        <div class="search-box" style="display: block; width: 350px;">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Cari ID Invoice, ID Pesanan, atau Nama Pelanggan...">
        </div>
        <div style="display: flex; gap: 12px;">
            <input type="date" class="btn btn-outline" style="padding: 8px 12px; border-radius: 6px; font-size: 13px; color: var(--text-main);">
            <select class="btn btn-outline" style="padding: 8px 12px; border-radius: 6px; font-size: 13px;">
                <option value="">Semua Metode</option>
                <option value="bca_va">BCA Virtual Account</option>
                <option value="mandiri_va">Mandiri Virtual Account</option>
                <option value="qris">QRIS</option>
                <option value="cc">Credit Card</option>
            </select>
        </div>
    </div>

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
                @foreach($payments as $payment)
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
                            <span style="font-weight: 600; font-size: 12px;">{{ strtoupper($payment->payment_method ?? 'Midtrans') }}</span>
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
                @endforeach
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
                    <h3>Rp 3.750.000</h3>
                    <span class="badge badge-success" style="margin-top: 8px;">Pelunasan (Berhasil)</span>
                </div>
                
                <div class="receipt-row">
                    <span class="lbl">ID Invoice</span>
                    <span class="val inv-pill" style="color: var(--text-main);">INV-2605-0891</span>
                </div>
                <div class="receipt-row">
                    <span class="lbl">ID Pesanan / Pemesanan</span>
                    <span class="val">BK-2605-002</span>
                </div>
                <div class="receipt-row">
                    <span class="lbl">Pelanggan</span>
                    <span class="val">Andi Wijaya</span>
                </div>
                <div class="receipt-row">
                    <span class="lbl">Waktu Transaksi</span>
                    <span class="val">04 Mei 2026, 14:35:12 WIB</span>
                </div>
                <div class="receipt-row" style="margin-top: 16px; padding-top: 16px; border-top: 1px dashed var(--card-border);">
                    <span class="lbl">Gerbang Pembayaran</span>
                    <span class="val" style="color: var(--secondary);">Midtrans</span>
                </div>
                <div class="receipt-row">
                    <span class="lbl">Metode</span>
                    <span class="val">BCA Virtual Account</span>
                </div>
                <div class="receipt-row">
                    <span class="lbl">Nomor VA</span>
                    <span class="val" style="font-family: monospace; font-size: 14px;">700123456789</span>
                </div>
            </div>

            <div id="payment-logs-container" style="margin-top: 20px;">
                <div style="margin-bottom: 8px; font-size: 13px; font-weight: 600;">Riwayat Status (Log):</div>
                <div id="payment-logs-list" style="max-height: 200px; overflow-y: auto; border: 1px solid var(--card-border); border-radius: var(--radius-sm); font-size: 11px;">
                    <!-- Logs will be injected here -->
                </div>
            </div>

            <div style="margin-top: 20px; margin-bottom: 8px; font-size: 13px; font-weight: 600;">Log Respon Gerbang:</div>
            <div class="json-box">
{
  <span class="json-key">"transaction_id"</span>: <span class="json-string">"f8a9d1b2-c3e4-4d5f-b6a7-09876543210f"</span>,
  <span class="json-key">"order_id"</span>: <span class="json-string">"INV-2605-0891"</span>,
  <span class="json-key">"gross_amount"</span>: <span class="json-string">"3750000.00"</span>,
  <span class="json-key">"payment_type"</span>: <span class="json-string">"bank_transfer"</span>,
  <span class="json-key">"transaction_time"</span>: <span class="json-string">"2026-05-04 14:35:12"</span>,
  <span class="json-key">"transaction_status"</span>: <span class="json-string">"settlement"</span>,
  <span class="json-key">"fraud_status"</span>: <span class="json-string">"accept"</span>,
  <span class="json-key">"bank"</span>: <span class="json-string">"bca"</span>,
  <span class="json-key">"va_number"</span>: <span class="json-string">"700123456789"</span>
}
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('paymentDetailModal')">Tutup</button>
            <button class="btn btn-gold"><i class="fas fa-print"></i> Cetak Struk</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    async function openModal(id, paymentId) {
        if (id === 'paymentDetailModal' && paymentId) {
            try {
                const response = await fetch(`/dashboard/finance/payments/${paymentId}`);
                const data = await response.json();
                
                // Update modal elements
                document.querySelector('#paymentDetailModal h3').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.paid_amount);
                document.querySelector('#paymentDetailModal .inv-pill').innerText = data.payment_code;
                document.querySelector('#paymentDetailModal .receipt-row:nth-child(3) .val').innerText = data.booking?.booking_code || '-';
                document.querySelector('#paymentDetailModal .receipt-row:nth-child(4) .val').innerText = data.booking?.customer?.name || '-';
                document.querySelector('#paymentDetailModal .receipt-row:nth-child(5) .val').innerText = data.payment_date || '-';
                document.querySelector('#paymentDetailModal .receipt-row:nth-child(7) .val').innerText = data.payment_method?.toUpperCase() || '-';
                document.querySelector('#paymentDetailModal .receipt-row:nth-child(8) .val').innerText = data.transaction_id || '-';
                
                // Logs
                const logsList = document.querySelector('#payment-logs-list');
                logsList.innerHTML = '';
                if (data.logs && data.logs.length > 0) {
                    data.logs.forEach(log => {
                        const date = new Date(log.created_at).toLocaleString('id-ID');
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
                badge.className = 'badge badge-' + (data.payment_status === 'settlement' || data.payment_status === 'success' ? 'success' : 'warning');
                badge.innerText = data.payment_status.toUpperCase();
                
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
</script>
@endsection
