@extends('layouts.admin')

@section('title', 'Fleet Maintenance – Siliwangi Admin')

@section('styles')
<style>
    .maint-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }
    .maint-stat-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        padding: 20px 24px;
        box-shadow: var(--card-shadow);
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .maint-stat-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .maint-stat-val { font-size: 26px; font-weight: 800; font-family: 'Poppins', sans-serif; color: var(--text-main); line-height: 1; }
    .maint-stat-lbl { font-size: 11px; font-weight: 600; color: var(--text-muted); margin-top: 3px; text-transform: uppercase; letter-spacing: 0.5px; }
    .card-premium {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }
    .card-header-premium {
        padding: 20px 28px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(0,0,0,0.02);
    }
    [data-theme="dark"] .card-header-premium { background: rgba(255,255,255,0.02); }
    .card-header-title { font-size: 16px; font-weight: 800; color: var(--text-main); display: flex; align-items: center; gap: 10px; }
    .admin-table { width: 100%; border-collapse: collapse; }
    .admin-table th {
        padding: 13px 20px;
        font-size: 10px; font-weight: 800; text-transform: uppercase;
        letter-spacing: 1.5px; color: var(--text-muted);
        background: rgba(0,0,0,0.02); border-bottom: 1px solid var(--card-border);
        text-align: left;
    }
    [data-theme="dark"] .admin-table th { background: rgba(255,255,255,0.02); }
    .admin-table td { padding: 16px 20px; border-bottom: 1px solid var(--card-border); font-size: 13px; vertical-align: middle; }
    .admin-table tbody tr { transition: background 0.2s; }
    .admin-table tbody tr:last-child td { border-bottom: none; }
    .admin-table tbody tr:hover { background: rgba(0,0,0,0.02); }
    [data-theme="dark"] .admin-table tbody tr:hover { background: rgba(255,255,255,0.02); }
    .car-cell-name { font-weight: 700; color: var(--text-main); }
    .car-cell-plate {
        display: inline-block; margin-top: 4px;
        padding: 2px 8px; border-radius: 6px;
        background: rgba(0,0,0,0.05); border: 1px solid var(--card-border);
        font-family: monospace; font-size: 10px; font-weight: 700; color: var(--text-muted);
    }
    [data-theme="dark"] .car-cell-plate { background: rgba(255,255,255,0.05); }
    .date-range { font-size: 13px; font-weight: 600; color: var(--text-main); }
    .cost-val { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 13px; color: var(--danger-text); }
    .btn-action {
        width: 34px; height: 34px; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid var(--card-border); background: rgba(0,0,0,0.03);
        color: var(--text-muted); cursor: pointer; transition: all 0.2s;
        text-decoration: none;
    }
    [data-theme="dark"] .btn-action { background: rgba(255,255,255,0.04); }
    .btn-action:hover { transform: translateY(-1px); }
    .btn-action.edit:hover  { background: rgba(59,130,246,0.1);  color: var(--info-text); border-color: var(--info); }
    .btn-action.attach:hover{ background: rgba(16,185,129,0.1); color: var(--success-text); border-color: var(--success); }
    .btn-action.del:hover   { background: var(--danger-bg); color: var(--danger-text); border-color: var(--danger); }
    .empty-state { padding: 60px 24px; text-align: center; opacity: 0.3; }
    .empty-state i { font-size: 48px; display: block; margin-bottom: 12px; }
    .empty-state p { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; }
    .btn-gold-sm {
        background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
        border: none; border-radius: 10px; padding: 9px 18px;
        font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;
        color: #0F172A; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none; box-shadow: 0 4px 12px rgba(212,175,55,0.3);
        transition: all 0.2s;
    }
    .btn-gold-sm:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(212,175,55,0.4); }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px; }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 style="font-size: 24px; font-weight: 800; font-family: 'Poppins', sans-serif; color: var(--text-main); margin-bottom: 4px;">Fleet Maintenance</h1>
        <div style="font-size: 12px; color: var(--text-muted);">
            <span>Siliwangi Admin</span>
            <i class="fas fa-chevron-right" style="font-size: 8px; margin: 0 8px; opacity: 0.4;"></i>
            <span style="color: var(--secondary); font-weight: 700;">History & Service Schedule</span>
        </div>
    </div>
    <a href="{{ route('admin.maintenances.create') }}" class="btn-gold-sm">
        <i class="fas fa-plus"></i> Record Maintenance
    </a>
</div>

{{-- Stats --}}
<div class="maint-stats">
    <div class="maint-stat-card">
        <div class="maint-stat-icon" style="background: var(--info-bg); color: var(--info-text);">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div>
            <div class="maint-stat-val">{{ $maintenances->where('status','scheduled')->count() }}</div>
            <div class="maint-stat-lbl">Scheduled</div>
        </div>
    </div>
    <div class="maint-stat-card">
        <div class="maint-stat-icon" style="background: var(--warning-bg); color: var(--warning-text);">
            <i class="fas fa-tools"></i>
        </div>
        <div>
            <div class="maint-stat-val">{{ $maintenances->where('status','in_progress')->count() }}</div>
            <div class="maint-stat-lbl">In Progress</div>
        </div>
    </div>
    <div class="maint-stat-card">
        <div class="maint-stat-icon" style="background: var(--success-bg); color: var(--success-text);">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <div class="maint-stat-val">{{ $maintenances->where('status','completed')->count() }}</div>
            <div class="maint-stat-lbl">Completed</div>
        </div>
    </div>
    <div class="maint-stat-card">
        <div class="maint-stat-icon" style="background: var(--danger-bg); color: var(--danger-text);">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div>
            <div class="maint-stat-val" style="font-size:18px;">Rp {{ number_format($maintenances->sum('cost')/1000000, 1, ',', '.') }}M</div>
            <div class="maint-stat-lbl">Total Cost</div>
        </div>
    </div>
</div>

@php
    // Get last 5 months dynamically
    $months = collect([]);
    for ($i = 4; $i >= 0; $i--) {
        $months->push(now()->subMonths($i)->format('Y-m'));
    }

    $monthlyData = $months->mapWithKeys(function($month) use ($maintenances) {
        $records = $maintenances->filter(function($m) use ($month) {
            return $m->start_date->format('Y-m') === $month;
        });
        return [$month => [
            'label' => Carbon\Carbon::parse($month . '-01')->translatedFormat('M Y'),
            'cost' => $records->sum('cost'),
            'count' => $records->count()
        ]];
    });

    $chartLabels = $monthlyData->pluck('label')->toArray();
    $chartCosts = $monthlyData->pluck('cost')->toArray();
    $chartCounts = $monthlyData->pluck('count')->toArray();

    // Group by Service Type for Doughnut chart
    $typeGroups = $maintenances->groupBy('maintenance_type');
    $typeLabels = $typeGroups->keys()->toArray();
    $typeCounts = $typeGroups->map->count()->values()->toArray();
@endphp

{{-- Analytics Intelligence Section --}}
<div class="row g-4 mb-4">
    <!-- Cost & Volume Trend -->
    <div class="col-xl-8 col-lg-7">
        <div class="card-premium h-100" style="padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div style="font-size: 14px; font-weight: 800; color: var(--text-main); text-transform: uppercase; letter-spacing: 1px;">
                    <i class="fas fa-chart-line" style="color: var(--secondary); margin-right: 8px;"></i>
                    Maintenance Costs & Frequency Trends
                </div>
                <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); background: rgba(0,0,0,0.03); padding: 4px 8px; border-radius: 6px;">Last 5 Months</span>
            </div>
            <div style="position: relative; height: 300px;">
                <canvas id="maintCostTrendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Category Distribution -->
    <div class="col-xl-4 col-lg-5">
        <div class="card-premium h-100" style="padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div style="font-size: 14px; font-weight: 800; color: var(--text-main); text-transform: uppercase; letter-spacing: 1px;">
                    <i class="fas fa-chart-pie" style="color: var(--secondary); margin-right: 8px;"></i>
                    Service Categories
                </div>
            </div>
            <div style="position: relative; height: 300px; display: flex; align-items: center; justify-content: center;">
                <canvas id="maintTypeShareChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="card-premium">
    <div class="card-header-premium">
        <div class="card-header-title">
            <i class="fas fa-wrench" style="color: var(--secondary);"></i>
            Maintenance History & Schedule
        </div>
        <span style="font-size: 12px; color: var(--text-muted);">{{ $maintenances->count() }} records found</span>
    </div>
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Vehicle</th>
                    <th>Branch</th>
                    <th>Date</th>
                    <th>Service Type</th>
                    <th>Cost</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($maintenances as $maintenance)
                <tr>
                    <td style="color: var(--text-muted); font-weight: 600;">{{ $loop->iteration }}</td>
                    <td>
                        <div class="car-cell-name">{{ $maintenance->car->car_name }}</div>
                        <span class="car-cell-plate">{{ $maintenance->car->plate_number }}</span>
                    </td>
                    <td style="color: var(--text-muted);">{{ $maintenance->branch->name }}</td>
                    <td>
                        <div class="date-range">{{ $maintenance->start_date->format('d M Y') }}</div>
                        @if($maintenance->end_date)
                        <div style="font-size: 11px; color: var(--text-muted);">to {{ $maintenance->end_date->format('d M Y') }}</div>
                        @else
                        <div style="font-size: 11px; color: var(--warning-text); font-weight: 600;">Not Completed</div>
                        @endif
                    </td>
                    <td style="font-weight: 600; color: var(--text-main);">{{ $maintenance->maintenance_type }}</td>
                    <td><span class="cost-val">Rp {{ number_format($maintenance->cost, 0, ',', '.') }}</span></td>
                    <td>
                        @php
                            $statusCfg = [
                                'scheduled'   => ['var(--info-bg)',     'var(--info-text)',    'Scheduled'],
                                'in_progress' => ['var(--warning-bg)',  'var(--warning-text)', 'In Progress'],
                                'completed'   => ['var(--success-bg)',  'var(--success-text)', 'Completed'],
                            ][$maintenance->status] ?? ['rgba(0,0,0,0.05)', 'var(--text-muted)', $maintenance->status];
                        @endphp
                        <span style="--bg: {{ $statusCfg[0] }}; --color: {{ $statusCfg[1] }}; background: var(--bg); color: var(--color); padding: 4px 12px; border-radius: 99px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; white-space: nowrap;">
                            {{ $statusCfg[2] }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px; justify-content: flex-end;">
                            <a href="{{ route('admin.maintenances.edit', $maintenance->id) }}" class="btn-action edit" title="Edit">
                                <i class="fas fa-pen" style="font-size:11px;"></i>
                            </a>
                            @if($maintenance->attachment)
                            <a href="{{ Storage::url($maintenance->attachment) }}" target="_blank" class="btn-action attach" title="View Attachment">
                                <i class="fas fa-paperclip" style="font-size:11px;"></i>
                            </a>
                            @endif
                            <form action="{{ route('admin.maintenances.destroy', $maintenance->id) }}" method="POST" onsubmit="return confirm('Delete this maintenance record?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action del" title="Delete">
                                    <i class="fas fa-trash" style="font-size:11px;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-tools"></i>
                            <p>No Maintenance Records Yet</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let maintCostChartInstance = null;
    let maintTypeChartInstance = null;

    function initMaintCharts(theme = 'light') {
        const textColor = theme === 'dark' ? '#F1F5F9' : '#1E293B';
        const mutedColor = theme === 'dark' ? '#94A3B8' : '#64748B';
        const gridColor = theme === 'dark' ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.04)';
        
        // 1. Costs & Volume Chart
        const ctxCost = document.getElementById('maintCostTrendChart');
        if (ctxCost) {
            const ctx = ctxCost.getContext('2d');
            if (maintCostChartInstance) maintCostChartInstance.destroy();
            
            const costData = @json($chartCosts);
            const countData = @json($chartCounts);
            const labels = @json($chartLabels);
            
            maintCostChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            type: 'line',
                            label: 'Total Cost (IDR)',
                            data: costData,
                            borderColor: '#D4AF37',
                            borderWidth: 3,
                            pointBackgroundColor: theme === 'dark' ? '#0F172A' : '#FFFFFF',
                            pointBorderColor: '#D4AF37',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            tension: 0.4,
                            fill: false,
                            yAxisID: 'y'
                        },
                        {
                            type: 'bar',
                            label: 'Service Count',
                            data: countData,
                            backgroundColor: 'rgba(212, 175, 55, 0.15)',
                            borderColor: 'rgba(212, 175, 55, 0.4)',
                            borderWidth: 1,
                            borderRadius: 6,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: textColor,
                                font: { family: 'Inter', weight: 'bold', size: 11 }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.datasetIndex === 0) {
                                        label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    } else {
                                        label += context.parsed.y + ' Service(s)';
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: gridColor },
                            ticks: { color: mutedColor, font: { family: 'Inter', size: 10 } }
                        },
                        y: {
                            type: 'linear',
                            position: 'left',
                            grid: { color: gridColor },
                            ticks: {
                                color: mutedColor,
                                font: { family: 'Inter', size: 10 },
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value/1000000) + 'M';
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            ticks: {
                                color: mutedColor,
                                font: { family: 'Inter', size: 10 },
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // 2. Service Category Share
        const ctxType = document.getElementById('maintTypeShareChart');
        if (ctxType) {
            const ctx = ctxType.getContext('2d');
            if (maintTypeChartInstance) maintTypeChartInstance.destroy();
            
            const typeLabels = @json($typeLabels);
            const typeCounts = @json($typeCounts);
            
            maintTypeChartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: typeLabels,
                    datasets: [{
                        data: typeCounts,
                        backgroundColor: [
                            '#D4AF37', // Gold
                            '#10B981', // Green
                            '#3B82F6', // Blue
                            '#F59E0B', // Orange
                            '#EF4444', // Red
                            '#6366F1'  // Indigo
                        ],
                        borderWidth: theme === 'dark' ? 2 : 1,
                        borderColor: theme === 'dark' ? '#0F172A' : '#FFFFFF'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: textColor,
                                padding: 16,
                                font: { family: 'Inter', size: 11 }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        }
    }

    // Initialize charts on DOM loaded
    document.addEventListener('DOMContentLoaded', () => {
        const initialTheme = document.documentElement.getAttribute('data-theme') || 'light';
        initMaintCharts(initialTheme);
    });

    // Theme Toggle callback integration
    window.updateChartsTheme = function(newTheme) {
        initMaintCharts(newTheme);
    };
</script>
@endsection
