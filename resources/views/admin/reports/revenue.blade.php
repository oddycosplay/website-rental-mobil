@extends('layouts.admin')

@section('title', 'Laporan Keuangan - Siliwangi Admin')

@section('styles')
<style>
    .metric-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    .metric-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        padding: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .metric-info h4 { font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-bottom: 8px; }
    .metric-info h2 { font-size: 24px; font-weight: 800; color: var(--text-main); }
    .metric-icon { 
        width: 48px; height: 48px; border-radius: 12px; 
        display: flex; align-items: center; justify-content: center; font-size: 20px; 
    }

    .report-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
    .report-header {
        padding: 24px;
        border-bottom: 1px solid var(--card-border);
        text-align: center;
    }
    .report-title { font-size: 18px; font-weight: 700; color: var(--text-main); }
    
    .report-table { width: 100%; border-collapse: collapse; }
    .report-table th { 
        background: var(--bg-color); 
        padding: 14px 20px; 
        font-size: 12px; 
        font-weight: 600; 
        color: var(--text-muted); 
        text-transform: uppercase;
        text-align: left;
    }
    .report-table td { 
        padding: 16px 20px; 
        border-bottom: 1px solid var(--card-border); 
        font-size: 14px; 
    }
    
    .total-row { background: var(--bg-color); font-weight: 700; }
    
    @media print {
        .sidebar, .page-header, .filter-section, .btn-print-hide { display: none !important; }
        .main-content { margin: 0 !important; padding: 0 !important; }
        .report-card { border: none !important; }
    }
</style>
@endsection

@section('content')

<div class="page-header d-print-none">
    <div>
        <h1 class="page-title">Laporan Keuangan</h1>
        <div class="breadcrumb">
            <span>Siliwangi Admin</span>
            <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
            <span>Reports</span>
            <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
            <span style="color: var(--secondary)">Revenue</span>
        </div>
    </div>
    <div class="header-actions">
        <button onclick="window.print()" class="btn btn-outline">
            <i class="fas fa-print"></i> Cetak Laporan
        </button>
    </div>
</div>

<div class="card mb-4 d-print-none shadow-sm" style="background: var(--card-bg); border: 1px solid var(--card-border); border-radius: var(--radius-lg);">
    <div class="card-body p-4">
        <form action="{{ route('admin.reports.revenue') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate->toDateString() }}">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate->toDateString() }}">
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-gold w-100">
                    <i class="fas fa-filter"></i> Filter Periode
                </button>
            </div>
        </form>
    </div>
</div>

<div class="metric-grid">
    <div class="metric-card">
        <div class="metric-info">
            <h4>Total Pemasukan</h4>
            <h2>Rp {{ number_format($incomes, 0, ',', '.') }}</h2>
        </div>
        <div class="metric-icon" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
            <i class="fas fa-arrow-trend-up"></i>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-info">
            <h4>Total Pengeluaran</h4>
            <h2>Rp {{ number_format($expenses, 0, ',', '.') }}</h2>
        </div>
        <div class="metric-icon" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">
            <i class="fas fa-arrow-trend-down"></i>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-info">
            <h4>Laba Bersih</h4>
            <h2 style="color: var(--secondary);">Rp {{ number_format($incomes - $expenses, 0, ',', '.') }}</h2>
        </div>
        <div class="metric-icon" style="background: rgba(212, 175, 55, 0.1); color: #D4AF37;">
            <i class="fas fa-wallet"></i>
        </div>
    </div>
</div>

<div class="report-card">
    <div class="report-header">
        <h2 class="report-title">RINGKASAN ARUS KAS (CASH FLOW)</h2>
        <p style="color: var(--text-muted); font-size: 13px; margin-top: 4px;">Periode: {{ $startDate->format('d F Y') }} - {{ $endDate->format('d F Y') }}</p>
    </div>

    <div class="table-responsive">
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Tanggal</th>
                    <th>Kode Referensi</th>
                    <th>Keterangan Transaksi</th>
                    <th style="text-align: right;">Debet (Masuk)</th>
                    <th style="text-align: right;">Kredit (Keluar)</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($payments as $p)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->payment_date)->format('d/m/Y') }}</td>
                    <td style="font-family: monospace; font-weight: 600;">{{ $p->payment_code }}</td>
                    <td>Pembayaran Booking #{{ $p->booking->booking_code }}</td>
                    <td style="text-align: right; color: #10B981; font-weight: 600;">Rp {{ number_format($p->paid_amount, 0, ',', '.') }}</td>
                    <td style="text-align: right; color: var(--text-muted);">-</td>
                </tr>
                @endforeach
                
                @if($payments->count() == 0)
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">Tidak ada transaksi masuk untuk periode ini.</td>
                </tr>
                @endif
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4" style="text-align: right; font-size: 14px;">TOTAL ARUS KAS</td>
                    <td style="text-align: right; font-size: 16px; color: #10B981;">Rp {{ number_format($incomes, 0, ',', '.') }}</td>
                    <td style="text-align: right; font-size: 16px; color: #EF4444;">Rp {{ number_format($expenses, 0, ',', '.') }}</td>
                </tr>
                <tr style="background: var(--bg-color); font-weight: 800;">
                    <td colspan="4" style="text-align: right; font-size: 15px; color: var(--text-main);">LABA BERSIH OPERASIONAL</td>
                    <td colspan="2" style="text-align: center; font-size: 18px; color: var(--secondary);">
                        Rp {{ number_format($incomes - $expenses, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection
