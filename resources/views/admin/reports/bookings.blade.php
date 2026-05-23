@extends('layouts.admin')

@section('title', 'Laporan Booking - Siliwangi Admin')

@section('styles')
<style>
    .report-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
    .report-header {
        padding: 24px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .report-title { font-size: 18px; font-weight: 700; color: var(--text-main); }
    
    .filter-section {
        background: var(--bg-color);
        padding: 20px;
        border-bottom: 1px solid var(--card-border);
    }
    
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
        <h1 class="page-title">Laporan Booking</h1>
        <div class="breadcrumb">
            <span>Siliwangi Admin</span>
            <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
            <span>Reports</span>
            <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
            <span style="color: var(--secondary)">Bookings</span>
        </div>
    </div>
    <div class="header-actions">
        <button onclick="window.print()" class="btn btn-outline">
            <i class="fas fa-print"></i> Cetak Laporan
        </button>
    </div>
</div>

<div class="report-card">
    <div class="filter-section d-print-none">
        <form action="{{ route('admin.reports.bookings') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate->toDateString() }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate->toDateString() }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Status Booking</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-gold w-100">
                    <i class="fas fa-filter"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <div class="report-header text-center" style="flex-direction: column; gap: 8px;">
        <h2 class="report-title">LAPORAN PENYEWAAN UNIT</h2>
        <p style="color: var(--text-muted); font-size: 13px;">Periode: {{ $startDate->format('d F Y') }} - {{ $endDate->format('d F Y') }}</p>
    </div>

    <div class="table-responsive">
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Kode Booking</th>
                    <th>Tanggal</th>
                    <th>Customer</th>
                    <th>Unit Mobil</th>
                    <th>Durasi</th>
                    <th style="text-align: right;">Total Transaksi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @forelse($bookings as $booking)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="font-weight: 700; font-family: monospace;">{{ $booking->booking_code }}</td>
                    <td>{{ $booking->created_at->format('d/m/Y') }}</td>
                    <td>{{ $booking->customer->name ?? '-' }}</td>
                    <td>{{ $booking->car->car_name ?? '-' }}</td>
                    <td>{{ $booking->total_day }} Hari</td>
                    <td style="text-align: right; font-weight: 600;">Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $statusClass = [
                                'completed' => 'success',
                                'confirmed' => 'info',
                                'ongoing' => 'warning',
                                'cancelled' => 'danger',
                                'pending' => 'secondary'
                            ][$booking->booking_status] ?? 'secondary';
                        @endphp
                        <span class="badge badge-{{ $statusClass }}">{{ ucfirst($booking->booking_status) }}</span>
                    </td>
                </tr>
                @php $grandTotal += $booking->grand_total; @endphp
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <i class="fas fa-folder-open" style="font-size: 24px; display: block; margin-bottom: 10px;"></i>
                        Tidak ada data ditemukan untuk periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($bookings->count() > 0)
            <tfoot>
                <tr class="total-row">
                    <td colspan="6" style="text-align: right; font-size: 14px; text-transform: uppercase;">Total Keseluruhan</td>
                    <td style="text-align: right; font-size: 16px; color: var(--secondary);">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection
