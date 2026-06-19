@extends('layouts.admin')

@section('title', 'Laporan & Analitik - Siliwangi Admin')

@section('styles')
<style>
    /* Filter Bar */
    .report-filter {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        padding: 20px;
        margin-bottom: 24px;
        display: flex;
        gap: 16px;
        align-items: flex-end;
        flex-wrap: wrap;
    }
    .filter-group { display: flex; flex-direction: column; gap: 8px; flex: 1; min-width: 200px; }
    .filter-group label { font-size: 13px; font-weight: 600; color: var(--text-muted); }
    .filter-control {
        padding: 10px 14px; background: var(--bg-color); color: var(--text-main);
        border: 1px solid var(--card-border); border-radius: 6px; font-size: 14px;
    }

    /* Metric Cards */
    .metric-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    .metric-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        padding: 20px;
        text-align: center;
    }
    .metric-val { font-size: 28px; font-weight: 800; color: var(--text-main); font-family: 'Poppins', sans-serif; margin-bottom: 4px; }
    .metric-lbl { font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; }

    /* Chart Layout */
    .chart-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }
    @media (max-width: 992px) {
        .chart-grid { grid-template-columns: 1fr; }
    }
    .chart-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        padding: 24px;
    }
    .chart-header { margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
    .chart-title { font-size: 16px; font-weight: 700; }

    /* Report Export List */
    .export-list {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
    .export-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: 16px 24px; border-bottom: 1px solid var(--card-border);
        transition: background 0.2s;
    }
    .export-item:last-child { border-bottom: none; }
    .export-item:hover { background: var(--bg-color); }
    .exp-info h4 { font-size: 14px; font-weight: 600; margin-bottom: 4px; }
    .exp-info p { font-size: 12px; color: var(--text-muted); }
    .exp-actions { display: flex; gap: 8px; }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Laporan & Analitik</h1>
        <div class="breadcrumb">
            <span>Siliwangi Admin</span>
            <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
            <span style="color: var(--secondary)">Reports</span>
        </div>
    </div>
    <div class="header-actions">
        <button class="btn btn-gold">
            <i class="fas fa-magic"></i> Generate Custom Report
        </button>
    </div>
</div>

<div class="report-filter">
    <div class="filter-group">
        <label>Periode Waktu</label>
        <select class="filter-control" id="periodSelect">
            <option value="this_month">Bulan Ini (Mei 2026)</option>
            <option value="last_month">Bulan Lalu (April 2026)</option>
            <option value="this_year">Tahun Ini (2026)</option>
            <option value="custom">Custom Range...</option>
        </select>
    </div>
    <div class="filter-group">
        <label>Tipe Layanan</label>
        <select class="filter-control">
            <option value="all">Semua Layanan</option>
            <option value="lepas_kunci">Lepas Kunci</option>
            <option value="dengan_supir">Dengan Supir</option>
        </select>
    </div>
    <div class="filter-group" style="flex: 0 0 auto;">
        <button class="btn btn-outline" style="height: 42px; width: 100%;"><i class="fas fa-sync-alt"></i> Update Data</button>
    </div>
</div>

<div class="metric-grid">
    <div class="metric-card">
        <div class="metric-val">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
        <div class="metric-lbl">Total Pendapatan</div>
        <div style="color: var(--success); font-size: 12px; margin-top: 8px; font-weight: 600;"><i class="fas fa-calendar-alt"></i> Periode Terpilih</div>
    </div>
    <div class="metric-card">
        <div class="metric-val">{{ $stats['total_bookings'] }}</div>
        <div class="metric-lbl">Total Booking</div>
        <div style="color: var(--success); font-size: 12px; margin-top: 8px; font-weight: 600;"><i class="fas fa-check-circle"></i> Transaksi Berhasil</div>
    </div>
    <div class="metric-card">
        <div class="metric-val">{{ number_format($stats['avg_duration'], 1) }} Hari</div>
        <div class="metric-lbl">Rata-rata Durasi Sewa</div>
        <div style="color: var(--text-muted); font-size: 12px; margin-top: 8px; font-weight: 600;"><i class="fas fa-clock"></i> Per Transaksi</div>
    </div>
    <div class="metric-card">
        <div class="metric-val">{{ $stats['total_refunds'] }}</div>
        <div class="metric-lbl">Refund / Batal</div>
        <div style="color: var(--danger); font-size: 12px; margin-top: 8px; font-weight: 600;"><i class="fas fa-undo"></i> Transaksi Refund</div>
    </div>
</div>

<div class="chart-grid">
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Pendapatan Harian (Mei 2026)</h3>
            <button class="btn btn-outline" style="padding: 4px 8px; font-size: 12px;"><i class="fas fa-download"></i> CSV</button>
        </div>
        <canvas id="revenueBarChart" height="100"></canvas>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Booking Berdasarkan Kendaraan</h3>
        </div>
        <canvas id="carTypeChart" height="200"></canvas>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Pendapatan Berdasarkan Segmentasi</h3>
        </div>
        <canvas id="segmentRevenueChart" height="200"></canvas>
    </div>
</div>

<h3 style="font-size: 16px; margin-bottom: 16px; font-weight: 700;">Riwayat Export Laporan Terakhir</h3>
<div class="export-list">
    <div class="export-item">
        <div class="exp-info">
            <h4>Laporan Keuangan Bulanan - April 2026</h4>
            <p>Dibuat oleh: System Auto-gen • 01 Mei 2026, 00:05 WIB</p>
        </div>
        <div class="exp-actions">
            <button class="btn btn-outline" style="color: var(--danger); border-color: var(--card-border);"><i class="fas fa-file-pdf"></i> PDF</button>
            <button class="btn btn-outline" style="color: var(--success); border-color: var(--card-border);"><i class="fas fa-file-excel"></i> Excel</button>
        </div>
    </div>
    <div class="export-item">
        <div class="exp-info">
            <h4>Kinerja Kendaraan (Q1 2026)</h4>
            <p>Dibuat oleh: Superadmin • 15 Apr 2026, 14:20 WIB</p>
        </div>
        <div class="exp-actions">
            <button class="btn btn-outline" style="color: var(--danger); border-color: var(--card-border);"><i class="fas fa-file-pdf"></i> PDF</button>
            <button class="btn btn-outline" style="color: var(--success); border-color: var(--card-border);"><i class="fas fa-file-excel"></i> Excel</button>
        </div>
    </div>
    <div class="export-item">
        <div class="exp-info">
            <h4>Laporan Pajak / PPN 11% (Maret 2026)</h4>
            <p>Dibuat oleh: Finance Admin • 05 Apr 2026, 10:15 WIB</p>
        </div>
        <div class="exp-actions">
            <button class="btn btn-outline" style="color: var(--success); border-color: var(--card-border);"><i class="fas fa-file-excel"></i> Excel</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script id="revenue-data" type="application/json">@json($monthlyRevenue->pluck('total'))</script>
<script id="revenue-labels" type="application/json">@json($monthlyRevenue->pluck('date')->map(fn($d) => date('d M', strtotime($d))))</script>
<script id="car-type-data" type="application/json">@json($carTypeDistribution->pluck('total'))</script>
<script id="car-type-labels" type="application/json">@json($carTypeDistribution->pluck('name'))</script>
<script id="segment-data" type="application/json">@json($segmentRevenue->pluck('total'))</script>
<script id="segment-labels" type="application/json">@json($segmentRevenue->pluck('category')->map(fn($c) => ucfirst($c)))</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const parseJSON = (id) => JSON.parse(document.getElementById(id).textContent);
        
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const textColor = isDark ? '#9ca3af' : '#6b7280';
        const gridColor = isDark ? '#334155' : '#e2e8f0';

        // Bar Chart (Revenue)
        const ctxBar = document.getElementById('revenueBarChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: parseJSON('revenue-labels'),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: parseJSON('revenue-data'),
                    backgroundColor: '#D4AF37',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { 
                            color: textColor,
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor }
                    }
                }
            }
        });

        // Doughnut Chart (Car Types)
        const ctxPie = document.getElementById('carTypeChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: parseJSON('car-type-labels'),
                datasets: [{
                    data: parseJSON('car-type-data'),
                    backgroundColor: [
                        '#D4AF37', '#0F172A', '#10B981', '#8B5CF6', '#F59E0B', '#EF4444'
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: textColor,
                            padding: 20,
                            font: { size: 11 }
                        }
                    }
                }
            }
        });

        // Segment Revenue Pie Chart
        const ctxSegment = document.getElementById('segmentRevenueChart').getContext('2d');
        new Chart(ctxSegment, {
            type: 'pie',
            data: {
                labels: parseJSON('segment-labels'),
                datasets: [{
                    data: parseJSON('segment-data'),
                    backgroundColor: ['#10B981', '#3B82F6', '#64748B'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: textColor,
                            padding: 20,
                            font: { size: 11 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
