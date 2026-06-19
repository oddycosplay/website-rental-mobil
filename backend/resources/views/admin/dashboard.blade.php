@extends('layouts.admin')

@section('title', 'Management Operational – Siliwangi Rental')

@section('styles')
<style>
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .chart-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        margin-bottom: 32px;
    }

    @media (max-width: 1200px) {
        .chart-grid {
            grid-template-columns: 1fr;
        }
    }

    .chart-container {
        position: relative;
        height: 320px;
        width: 100%;
    }

    .chart-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        padding: 28px;
        box-shadow: var(--card-shadow);
    }

    .chart-label {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--secondary);
        margin-bottom: 4px;
        display: block;
    }

    .chart-main-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-main);
        font-family: 'Poppins', sans-serif;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .table-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }

    .table-card-head {
        padding: 24px 32px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(0, 0, 0, 0.02);
    }

    [data-theme="dark"] .table-card-head {
        background: rgba(255, 255, 255, 0.02);
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }

    .admin-table th {
        padding: 14px 24px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-muted);
        background: rgba(0, 0, 0, 0.02);
        border-bottom: 1px solid var(--card-border);
        text-align: left;
    }

    [data-theme="dark"] .admin-table th {
        background: rgba(255, 255, 255, 0.02);
    }

    .admin-table td {
        padding: 18px 24px;
        border-bottom: 1px solid var(--card-border);
        font-size: 13px;
        vertical-align: middle;
    }

    .admin-table tbody tr {
        transition: background 0.2s;
    }

    .admin-table tbody tr:hover {
        background: rgba(0, 0, 0, 0.02);
    }

    [data-theme="dark"] .admin-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.02);
    }

    .stat-card-premium {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        padding: 24px;
        box-shadow: var(--card-shadow);
        display: flex;
        align-items: flex-start;
        gap: 16px;
        transition: all 0.3s;
    }

    .stat-card-premium:hover {
        box-shadow: var(--card-shadow-hover);
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        margin-bottom: 6px;
    }

    .stat-value {
        font-size: 26px;
        font-weight: 800;
        font-family: 'Poppins', sans-serif;
        color: var(--text-main);
        line-height: 1;
        margin-bottom: 8px;
    }

    .stat-footer {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
    }

    .trend-up {
        color: var(--success);
        font-weight: 700;
    }

    .trend-label {
        color: var(--text-muted);
        font-weight: 500;
    }

    .progress-bar-wrap {
        width: 100%;
        background: rgba(0, 0, 0, 0.08);
        border-radius: 99px;
        height: 6px;
        overflow: hidden;
        margin-top: 8px;
    }

    [data-theme="dark"] .progress-bar-wrap {
        background: rgba(255, 255, 255, 0.08);
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 99px;
        background: var(--success);
    }

    .avatar-chip {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid var(--card-border);
    }

    .booking-code {
        font-family: monospace;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-muted);
    }

    .booking-code:hover {
        color: var(--secondary);
    }

    .plate-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 6px;
        background: rgba(0, 0, 0, 0.05);
        border: 1px solid var(--card-border);
        font-family: monospace;
        font-size: 10px;
        font-weight: 700;
        color: var(--text-muted);
        margin-top: 4px;
    }

    [data-theme="dark"] .plate-badge {
        background: rgba(255, 255, 255, 0.05);
    }

    .pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .pulse-dot.success {
        background: var(--success);
        animation: pulse 2s infinite;
    }

    .pulse-dot.warning {
        background: var(--warning);
    }

    .pulse-dot.danger {
        background: var(--danger);
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.4;
        }
    }

    .Car-legend {
        display: flex;
        justify-content: center;
        gap: 24px;
        margin-top: 16px;
    }

    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
    }

    .action-icon-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.04);
        border: 1px solid var(--card-border);
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .action-icon-btn:hover {
        background: rgba(212, 175, 55, 0.1);
        color: var(--secondary);
        border-color: var(--secondary);
    }

    [data-theme="dark"] .action-icon-btn {
        background: rgba(255, 255, 255, 0.04);
    }

    .empty-state {
        padding: 60px 24px;
        text-align: center;
        opacity: 0.3;
    }

    .empty-state i {
        font-size: 48px;
        display: block;
        margin-bottom: 12px;
    }

    .empty-state p {
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .btn-glass {
        background: rgba(0, 0, 0, 0.04);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-glass:hover {
        color: var(--secondary);
        border-color: var(--secondary);
    }

    [data-theme="dark"] .btn-glass {
        background: rgba(255, 255, 255, 0.04);
    }

    .btn-gold-sm {
        background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
        border: none;
        border-radius: 10px;
        padding: 9px 20px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #0F172A;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 style="font-size: 24px; font-weight: 800; font-family: 'Poppins', sans-serif; color: var(--text-main); margin-bottom: 4px;">Operational Intelligence</h1>
        <div style="font-size: 12px; color: var(--text-muted);">
            <span>Siliwangi System</span>
            <i class="fas fa-chevron-right" style="font-size: 8px; margin: 0 8px; opacity: 0.4;"></i>
            <span style="color: var(--secondary); font-weight: 700;">Admin Dashboard</span>
        </div>
    </div>
    <div class="page-header-actions">
        <button class="btn-glass"><i class="fas fa-calendar-alt" style="color: var(--secondary);"></i> Last 30 Days</button>
        <button class="btn-gold-sm"><i class="fas fa-file-invoice-dollar"></i> Generate Report</button>
    </div>
</div>

{{-- KPI CARDS --}}
<div class="kpi-grid">
    <div class="stat-card-premium">
        <div class="stat-icon" style="background: var(--info-bg); color: var(--info-text);"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-info">
            <div class="stat-label">Booking Volume</div>
            <div class="stat-value">{{ number_format($totalBooking) }} <span style="font-size:13px; color: var(--text-muted); font-weight:500;">Bookings</span></div>
            <div class="stat-footer">
                <span class="trend-up"><i class="fas fa-arrow-up"></i> 12.5%</span>
                <span class="trend-label">Growth Rate</span>
            </div>
        </div>
    </div>

    <div class="stat-card-premium">
        <div class="stat-icon" style="background: var(--secondary-glow); color: var(--secondary);"><i class="fas fa-vault"></i></div>
        <div class="stat-info">
            <div class="stat-label">Gross Revenue</div>
            <div class="stat-value">Rp {{ number_format($totalRevenue/1000000, 1, ',', '.') }}<span style="font-size:14px; color:var(--text-muted); font-weight:500;">M</span></div>
            <div class="stat-footer">
                <span class="trend-up"><i class="fas fa-arrow-up"></i> 8.2%</span>
                <span class="trend-label">vs Last Quarter</span>
            </div>
        </div>
    </div>

    <div class="stat-card-premium">
        <div class="stat-icon" style="background: var(--success-bg); color: var(--success-text);"><i class="fas fa-car-rear"></i></div>
        <div class="stat-info">
            <div class="stat-label">Car Readiness</div>
            <div class="stat-value">{{ $availableCars }} <span style="font-size:13px; color:var(--text-muted); font-weight:500;">/ {{ $totalCars }} Units</span></div>
            <div class="stat-footer" style="display:block;">
                <div class="progress-bar-wrap">
                    <div class="progress-bar-fill" style="--w: {{ $totalCars > 0 ? ($availableCars/$totalCars)*100 : 0 }}%; width: var(--w);"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card-premium">
        <div class="stat-icon" style="background: rgba(139,92,246,0.1); color: #7C3AED;"><i class="fas fa-key"></i></div>
        <div class="stat-info">
            <div class="stat-label">Active Mobility</div>
            <div class="stat-value">{{ number_format($activeRentals) }} <span style="font-size:13px; color:var(--text-muted); font-weight:500;">Rentals</span></div>
            <div class="stat-footer">
                <span class="trend-up"><i class="fas fa-plus"></i> 3</span>
                <span class="trend-label">Departures Today</span>
            </div>
        </div>
    </div>
</div>

{{-- CHARTS --}}
<div class="chart-grid">
    <div class="chart-card">
        <div class="chart-header">
            <div>
                <span class="chart-label">Financial Pulse</span>
                <h3 class="chart-main-title">Revenue Performance</h3>
            </div>
            <div style="display:flex; gap:8px; align-items:center;">
                <select class="btn-glass" style="padding: 6px 12px; font-size:10px;">
                    <option>FY 2026</option>
                    <option>FY 2025</option>
                </select>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div>
                <span class="chart-label">Asset Monitoring</span>
                <h3 class="chart-main-title">Car Allocation</h3>
            </div>
        </div>
        <div class="chart-container" style="height:240px; display:flex; align-items:center; justify-content:center;">
            <canvas id="statusChart"></canvas>
        </div>
        <div class="Car-legend">
            <div class="legend-item"><span class="legend-dot" style="background:var(--success);"></span>Available</div>
            <div class="legend-item"><span class="legend-dot" style="background:#8B5CF6;"></span>Rented</div>
            <div class="legend-item"><span class="legend-dot" style="background:var(--warning);"></span>Service</div>
        </div>
    </div>
</div>

{{-- RECENT BOOKINGS TABLE --}}
<div class="table-card">
    <div class="table-card-head">
        <div>
            <h3 style="font-size:18px; font-weight:800; color:var(--text-main); margin-bottom:4px;">Recent Mobility Activity</h3>
            <p style="font-size:12px; color:var(--text-muted);">Real-time updates from the booking system</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="btn-glass">
            View All <i class="fas fa-arrow-right" style="font-size:9px;"></i>
        </a>
    </div>
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Customer</th>
                    <th>Vehicle</th>
                    <th>Schedule</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings as $booking)
                <tr>
                    <td><span class="booking-code">#{{ $booking->booking_code }}</span></td>
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($booking->customer_name) }}&background=0F172A&color=D4AF37&bold=true" class="avatar-chip" alt="">
                            <div>
                                <div style="font-weight:700; font-size:13px; color:var(--text-main);">{{ $booking->customer_name }}</div>
                                <div style="font-size:11px; color:var(--text-muted);">{{ $booking->customer_email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:700; color:var(--text-main);">{{ $booking->car_name }}</div>
                        <span class="plate-badge">{{ $booking->plate_number }}</span>
                    </td>
                    <td>
                        <div style="font-weight:700; color:var(--text-main);">{{ \Carbon\Carbon::parse($booking->pickup_date)->translatedFormat('d M Y') }}</div>
                        <div style="font-size:11px; font-weight:700; color:var(--secondary);">Duration {{ $booking->total_day }} Days</div>
                    </td>
                    <td>
                        @if($booking->payment_status == 'paid')
                        <div style="display:flex; align-items:center; gap:6px;"><span class="pulse-dot success"></span><span style="font-size:11px; font-weight:700; color:var(--success);">PAID</span></div>
                        @elseif($booking->payment_status == 'pending')
                        <div style="display:flex; align-items:center; gap:6px;"><span class="pulse-dot warning"></span><span style="font-size:11px; font-weight:700; color:var(--warning-text);">PENDING</span></div>
                        @else
                        <div style="display:flex; align-items:center; gap:6px;"><span class="pulse-dot danger"></span><span style="font-size:11px; font-weight:700; color:var(--danger-text);">FAILED</span></div>
                        @endif
                    </td>
                    <td>
                        @php
                        $statusMap = [
                        'pending' => ['var(--warning-bg)', 'var(--warning-text)', 'Pending'],
                        'confirmed' => ['var(--info-bg)', 'var(--info-text)', 'Confirmed'],
                        'on_going' => ['rgba(139,92,246,0.1)', '#7C3AED', 'Rented'],
                        'completed' => ['var(--success-bg)', 'var(--success-text)', 'Completed'],
                        'cancelled' => ['var(--danger-bg)', 'var(--danger-text)', 'Cancelled'],
                        ];
                        $s = $statusMap[$booking->booking_status] ?? ['rgba(0,0,0,0.05)', 'var(--text-muted)', 'Unknown'];
                        @endphp
                        <span style="--bg: {{ $s[0] }}; --color: {{ $s[1] }}; background: var(--bg); color: var(--color); padding: 4px 12px; border-radius: 99px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; white-space:nowrap;">{{ $s[2] }}</span>
                    </td>
                    <td style="text-align:right;">
                        <div style="display:flex; gap:6px; justify-content:flex-end;">
                            <a href="#" class="action-icon-btn"><i class="fas fa-eye" style="font-size:12px;"></i></a>
                            <a href="{{ route('admin.bookings.index') }}" class="action-icon-btn"><i class="fas fa-pen-to-square" style="font-size:12px;"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-folder-open"></i>
                            <p>No Recent Transactions</p>
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
    const getChartTheme = () => {
        const style = getComputedStyle(document.documentElement);
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        return {
            primary: style.getPropertyValue('--secondary').trim() || '#D4AF37',
            primaryGlow: style.getPropertyValue('--secondary-glow').trim() || 'rgba(212,175,55,0.3)',
            text: style.getPropertyValue('--text-muted').trim() || '#64748B',
            grid: isDark ? 'rgba(255,255,255,0.04)' : 'rgba(0,0,0,0.04)',
            success: style.getPropertyValue('--success').trim() || '#10B981',
            warning: style.getPropertyValue('--warning').trim() || '#F59E0B',
            bg: style.getPropertyValue('--card-bg').trim() || '#FFFFFF'
        };
    };

    let revenueChart, statusChart;

    function initCharts() {
        const c = getChartTheme();
        const ctxRev = document.getElementById('revenueChart').getContext('2d');
        const grad = ctxRev.createLinearGradient(0, 0, 0, 320);
        grad.addColorStop(0, c.primaryGlow);
        grad.addColorStop(1, 'rgba(212,175,55,0)');

        revenueChart = new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Revenue',
                    data: [120, 150, 180, 140, 210, 190, 250, 220, 280, 260, 310, 350],
                    borderColor: c.primary,
                    backgroundColor: grad,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.45,
                    pointBackgroundColor: c.bg,
                    pointBorderColor: c.primary,
                    pointBorderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        padding: 14,
                        cornerRadius: 12,
                        titleFont: {
                            family: 'Poppins',
                            size: 11,
                            weight: '800'
                        },
                        bodyFont: {
                            family: 'Inter',
                            size: 13,
                            weight: '700'
                        },
                        displayColors: false,
                        callbacks: {
                            label: c => 'IDR ' + c.parsed.y + '.000.000'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: c.text,
                            font: {
                                family: 'Inter',
                                weight: '600',
                                size: 11
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: c.grid,
                            borderDash: [4, 4]
                        },
                        ticks: {
                            color: c.text,
                            font: {
                                family: 'Inter',
                                weight: '600',
                                size: 11
                            },
                            callback: v => 'Rp ' + v + 'M'
                        },
                        border: {
                            display: false
                        }
                    }
                }
            }
        });

        const ctxPie = document.getElementById('statusChart').getContext('2d');
        statusChart = new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Available', 'Rented', 'Service'],
                datasets: [{
                    data: [24, 18, 3],
                    backgroundColor: [c.success, '#8B5CF6', c.warning],
                    borderWidth: 0,
                    hoverOffset: 12,
                    borderRadius: 8,
                    spacing: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '80%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        padding: 14,
                        cornerRadius: 12,
                        titleFont: {
                            family: 'Poppins',
                            size: 12,
                            weight: '800'
                        }
                    }
                }
            }
        });
    }

    function updateChartsTheme() {
        if (!revenueChart || !statusChart) return;
        setTimeout(() => {
            const c = getChartTheme();
            const grad = revenueChart.ctx.createLinearGradient(0, 0, 0, 320);
            grad.addColorStop(0, c.primaryGlow);
            grad.addColorStop(1, 'rgba(212,175,55,0)');
            revenueChart.data.datasets[0].backgroundColor = grad;
            revenueChart.data.datasets[0].borderColor = c.primary;
            revenueChart.data.datasets[0].pointBackgroundColor = c.bg;
            revenueChart.options.scales.x.ticks.color = c.text;
            revenueChart.options.scales.y.ticks.color = c.text;
            revenueChart.options.scales.y.grid.color = c.grid;
            revenueChart.update('none');
            statusChart.update('none');
        }, 100);
    }

    document.addEventListener('DOMContentLoaded', initCharts);
</script>
@endsection