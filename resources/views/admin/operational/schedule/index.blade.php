@extends('layouts.admin')

@section('title', 'Fleet Scheduling – Siliwangi Admin')

@section('styles')
<style>
    .schedule-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }
    .stat-card-mini {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        padding: 24px;
        box-shadow: var(--card-shadow);
    }
    .stat-mini-label {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-muted);
        margin-bottom: 8px;
    }
    .stat-mini-val {
        font-size: 28px;
        font-weight: 800;
        font-family: 'Poppins', sans-serif;
        color: var(--text-main);
    }
    .schedule-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        box-shadow: var(--card-shadow);
        overflow: hidden;
        margin-bottom: 32px;
    }
    .schedule-card-head {
        padding: 20px 28px;
        border-bottom: 1px solid var(--card-border);
        background: rgba(0, 0, 0, 0.02);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    [data-theme="dark"] .schedule-card-head {
        background: rgba(255, 255, 255, 0.02);
    }
    .timeline-table {
        width: 100%;
        border-collapse: collapse;
    }
    .timeline-table th {
        padding: 16px 12px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        background: rgba(0, 0, 0, 0.02);
        border-bottom: 1px solid var(--card-border);
        border-right: 1px solid var(--card-border);
        text-align: center;
    }
    [data-theme="dark"] .timeline-table th {
        background: rgba(255, 255, 255, 0.02);
    }
    .timeline-table td {
        padding: 12px;
        border-bottom: 1px solid var(--card-border);
        border-right: 1px solid var(--card-border);
        vertical-align: middle;
    }
    .car-info-cell {
        min-width: 200px;
        text-align: left !important;
    }
    .car-name-sm {
        font-weight: 800;
        font-size: 13px;
        color: var(--text-main);
    }
    .plate-badge-sm {
        font-family: monospace;
        font-size: 10px;
        font-weight: 700;
        color: var(--text-muted);
        background: rgba(0, 0, 0, 0.05);
        border: 1px solid var(--card-border);
        padding: 1px 6px;
        border-radius: 4px;
        margin-top: 4px;
        display: inline-block;
    }
    [data-theme="dark"] .plate-badge-sm {
        background: rgba(255, 255, 255, 0.05);
    }
    .status-block {
        padding: 6px;
        border-radius: 8px;
        font-size: 9px;
        font-weight: 800;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: help;
        transition: transform 0.2s;
    }
    .status-block:hover {
        transform: scale(1.05);
    }
    .status-rented { background: var(--info-bg); color: var(--info-text); border: 1px solid var(--info); }
    .status-maintenance { background: var(--danger-bg); color: var(--danger-text); border: 1px solid var(--danger); }
    .status-free { background: var(--success-bg); color: var(--success-text); border: 1px solid var(--success); opacity: 0.4; }

    .list-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    @media (max-width: 992px) {
        .list-grid { grid-template-columns: 1fr; }
    }
    .mini-table {
        width: 100%;
        border-collapse: collapse;
    }
    .mini-table th {
        padding: 12px 16px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--text-muted);
        border-bottom: 1px solid var(--card-border);
        text-align: left;
    }
    .mini-table td {
        padding: 12px 16px;
        border-bottom: 1px solid var(--card-border);
        font-size: 12px;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 style="font-size: 24px; font-weight: 800; font-family: 'Poppins', sans-serif; color: var(--text-main); margin-bottom: 4px;">Fleet Scheduling</h1>
        <div style="font-size: 12px; color: var(--text-muted);">
            <span>Operations Center</span>
            <i class="fas fa-chevron-right" style="font-size: 8px; margin: 0 8px; opacity: 0.4;"></i>
            <span style="color: var(--secondary); font-weight: 700;">Vehicle Availability Timeline</span>
        </div>
    </div>
</div>

{{-- Quick Stats --}}
<div class="schedule-stats">
    <div class="stat-card-mini" style="border-left: 4px solid var(--success);">
        <div class="stat-mini-label">Ready for Service</div>
        <div class="stat-mini-val">{{ $cars->where('status', 'available')->count() }} <span style="font-size:14px; color:var(--text-muted); font-weight:500;">Units</span></div>
    </div>
    <div class="stat-card-mini" style="border-left: 4px solid var(--info);">
        <div class="stat-mini-label">On the Road</div>
        <div class="stat-mini-val">{{ $cars->where('status', 'rented')->count() }} <span style="font-size:14px; color:var(--text-muted); font-weight:500;">Units</span></div>
    </div>
    <div class="stat-card-mini" style="border-left: 4px solid var(--danger);">
        <div class="stat-mini-label">In Maintenance</div>
        <div class="stat-mini-val">{{ $cars->where('status', 'maintenance')->count() }} <span style="font-size:14px; color:var(--text-muted); font-weight:500;">Units</span></div>
    </div>
</div>

{{-- Timeline Card --}}
<div class="schedule-card">
    <div class="schedule-card-head">
        <div style="font-size: 14px; font-weight: 800; color: var(--text-main); display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-calendar-day" style="color: var(--secondary);"></i>
            Next 7 Days Availability Matrix
        </div>
        <div style="display: flex; gap: 16px;">
            <div style="display: flex; align-items: center; gap: 6px; font-size: 10px; font-weight: 700; color: var(--text-muted);">
                <span style="width: 8px; height: 8px; border-radius: 2px; background: var(--success); opacity: 0.5;"></span> AVAILABLE
            </div>
            <div style="display: flex; align-items: center; gap: 6px; font-size: 10px; font-weight: 700; color: var(--text-muted);">
                <span style="width: 8px; height: 8px; border-radius: 2px; background: var(--info);"></span> RENTED
            </div>
            <div style="display: flex; align-items: center; gap: 6px; font-size: 10px; font-weight: 700; color: var(--text-muted);">
                <span style="width: 8px; height: 8px; border-radius: 2px; background: var(--danger);"></span> SERVICE
            </div>
        </div>
    </div>
    <div style="overflow-x: auto;">
        <table class="timeline-table">
            <thead>
                <tr>
                    <th class="car-info-cell">Fleet Unit</th>
                    @for($i = 0; $i < 7; $i++)
                    <th>
                        <div style="color: var(--text-main);">{{ now()->addDays($i)->format('D') }}</div>
                        <div style="opacity: 0.6;">{{ now()->addDays($i)->format('d/m') }}</div>
                    </th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach($cars as $car)
                <tr>
                    <td class="car-info-cell">
                        <div class="car-name-sm">{{ $car->car_name }}</div>
                        <span class="plate-badge-sm">{{ $car->plate_number }}</span>
                    </td>
                    @for($i = 0; $i < 7; $i++)
                    @php
                        $date = now()->addDays($i)->format('Y-m-d');
                        $isRented = $bookings->where('car_id', $car->id)->filter(function($b) use ($date) {
                            return $date >= $b->pickup_date->format('Y-m-d') && $date <= $b->return_date->format('Y-m-d');
                        })->first();
                        $isMaint = $maintenances->where('car_id', $car->id)->filter(function($m) use ($date) {
                            $end = $m->end_date ? $m->end_date->format('Y-m-d') : $date;
                            return $date >= $m->start_date->format('Y-m-d') && $date <= $end;
                        })->first();
                    @endphp
                    <td>
                        @if($isRented)
                        <div class="status-block status-rented" title="Rented: {{ $isRented->booking_code }}">RENTED</div>
                        @elseif($isMaint)
                        <div class="status-block status-maintenance" title="Maintenance: {{ $isMaint->maintenance_type }}">SERVICE</div>
                        @else
                        <div class="status-block status-free">FREE</div>
                        @endif
                    </td>
                    @endfor
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="list-grid">
    {{-- Active Bookings --}}
    <div class="schedule-card">
        <div class="schedule-card-head" style="background: var(--info-bg); border-bottom-color: var(--info);">
            <div style="font-size: 13px; font-weight: 800; color: var(--info-text);"><i class="fas fa-route mr-2"></i> Current & Upcoming Bookings</div>
        </div>
        <div style="overflow-x: auto;">
            <table class="mini-table">
                <thead>
                    <tr>
                        <th>Vehicle</th>
                        <th>Customer</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings->take(10) as $booking)
                    <tr>
                        <td style="font-weight: 700;">{{ $booking->car->car_name }}</td>
                        <td style="color: var(--text-muted);">{{ $booking->customer->name }}</td>
                        <td>
                            <span style="background: var(--info-bg); color: var(--info-text); padding: 2px 8px; border-radius: 99px; font-size: 9px; font-weight: 800; text-transform: uppercase;">{{ $booking->booking_status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 32px; color: var(--text-muted); opacity: 0.5;">No active bookings found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Active Maintenance --}}
    <div class="schedule-card">
        <div class="schedule-card-head" style="background: var(--danger-bg); border-bottom-color: var(--danger);">
            <div style="font-size: 13px; font-weight: 800; color: var(--danger-text);"><i class="fas fa-tools mr-2"></i> Active Maintenance</div>
        </div>
        <div style="overflow-x: auto;">
            <table class="mini-table">
                <thead>
                    <tr>
                        <th>Vehicle</th>
                        <th>Type</th>
                        <th>Est. Completion</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($maintenances->take(10) as $m)
                    <tr>
                        <td style="font-weight: 700;">{{ $m->car->car_name }}</td>
                        <td style="color: var(--text-muted);">{{ $m->maintenance_type }}</td>
                        <td style="font-weight: 700; color: var(--danger-text);">
                            {{ $m->end_date ? $m->end_date->format('d/M') : 'Unscheduled' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 32px; color: var(--text-muted); opacity: 0.5;">No active maintenance sessions.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
