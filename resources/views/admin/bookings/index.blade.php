@extends('layouts.admin')

@section('title', 'Booking & Schedule – Siliwangi Admin')

@section('styles')
<style>
    /* Layout & Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 900;
        color: var(--text-main);
        font-family: 'Poppins', sans-serif;
        letter-spacing: -0.5px;
        margin: 0;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        font-weight: 600;
        color: var(--text-muted);
        margin-top: 4px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    /* Summary Cards */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .stat-card-premium {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        padding: 24px;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .stat-card-premium:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-shadow-hover);
        border-color: var(--secondary);
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
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-muted);
        margin-bottom: 6px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 900;
        color: var(--text-main);
        font-family: 'Poppins', sans-serif;
    }

    .stat-unit {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-left: 4px;
    }

    /* Table & Cards */
    .table-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--card-shadow);
        margin-bottom: 32px;
    }

    .table-card-head {
        padding: 28px 32px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255, 255, 255, 0.02);
    }

    .table-title-wrap h3 {
        font-size: 18px;
        font-weight: 900;
        color: var(--text-main);
        margin: 0;
        letter-spacing: -0.3px;
    }

    .table-title-wrap p {
        font-size: 11px;
        color: var(--text-muted);
        font-weight: 600;
        margin-top: 4px;
    }

    .search-input-wrap {
        position: relative;
        width: 280px;
    }

    .search-input {
        width: 100%;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 10px 16px 10px 40px;
        font-size: 12px;
        color: var(--text-main);
        transition: all 0.2s;
    }

    .search-input:focus {
        border-color: var(--secondary);
        background: rgba(255, 255, 255, 0.05);
        outline: none;
    }

    .search-input-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 12px;
        color: var(--text-muted);
    }

    /* Admin Table */
    .admin-table-wrap {
        overflow-x: auto;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }

    .admin-table th {
        padding: 16px 32px;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--text-muted);
        background: rgba(255, 255, 255, 0.01);
        border-bottom: 1px solid var(--card-border);
        text-align: left;
    }

    .admin-table td {
        padding: 20px 32px;
        border-bottom: 1px solid var(--card-border);
        vertical-align: middle;
    }

    .admin-table tr {
        transition: background 0.2s;
    }

    .admin-table tr:hover {
        background: rgba(212, 175, 55, 0.03);
    }

    /* Cells Styling */
    .booking-code-chip {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        font-weight: 800;
        color: var(--secondary);
        background: rgba(212, 175, 55, 0.1);
        padding: 4px 10px;
        border-radius: 6px;
        border: 1px solid rgba(212, 175, 55, 0.2);
    }

    .customer-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .customer-avatar {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid var(--card-border);
    }

    .customer-name {
        font-size: 13px;
        font-weight: 800;
        color: var(--text-main);
        line-height: 1.2;
    }

    .customer-phone {
        font-size: 10px;
        color: var(--text-muted);
        font-weight: 700;
        margin-top: 2px;
    }

    .asset-cell {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .car-name {
        font-size: 13px;
        font-weight: 800;
        color: var(--text-main);
    }

    .car-badge-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .plate-badge {
        font-size: 9px;
        font-weight: 900;
        color: var(--secondary);
        background: rgba(212, 175, 55, 0.1);
        padding: 2px 6px;
        border-radius: 4px;
    }

    .service-type {
        font-size: 9px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
    }

    .time-cell {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .time-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-main);
    }

    .time-item i {
        font-size: 10px;
        width: 14px;
        text-align: center;
    }

    /* Action Buttons */
    .action-btn-circle {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--card-border);
        color: var(--text-muted);
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
    }

    .action-btn-circle:hover {
        background: rgba(212, 175, 55, 0.1);
        border-color: var(--secondary);
        color: var(--secondary);
        transform: rotate(15deg);
    }

    /* Modal Styling */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(10px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-content {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 32px;
        width: 100%;
        max-width: 960px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.6);
        animation: modalIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes modalIn {
        from {
            transform: scale(0.9) translateY(40px);
            opacity: 0;
        }

        to {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 32px 40px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255, 255, 255, 0.02);
    }

    .modal-body {
        padding: 40px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 40px;
    }

    @media (max-width: 850px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }

    .info-section {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--card-border);
        border-radius: 24px;
        padding: 28px;
        margin-bottom: 28px;
    }

    .section-title {
        font-size: 11px;
        font-weight: 900;
        color: var(--secondary);
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .data-row {
        display: flex;
        justify-content: space-between;
        padding: 14px 0;
        border-bottom: 1px solid var(--card-border);
    }

    .data-row:last-child {
        border-bottom: none;
    }

    .data-label {
        font-size: 12px;
        color: var(--text-muted);
        font-weight: 600;
    }

    .data-value {
        font-size: 13px;
        color: var(--text-main);
        font-weight: 800;
    }

    /* Timeline & Documents */
    .timeline {
        position: relative;
        padding-left: 32px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 8px;
        bottom: 8px;
        width: 2px;
        background: var(--card-border);
    }

    .timeline-item {
        position: relative;
        padding-bottom: 24px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -26px;
        top: 4px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--card-bg);
        border: 3px solid var(--card-border);
        z-index: 2;
    }

    .timeline-item.active::before {
        border-color: var(--secondary);
        background: var(--secondary);
    }

    .timeline-item.done::before {
        border-color: var(--success);
        background: var(--success);
    }

    .doc-card {
        aspect-ratio: 16/10;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.03);
        border: 2px dashed var(--card-border);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        transition: all 0.3s;
        cursor: pointer;
    }

    .doc-card:hover {
        border-color: var(--secondary);
        color: var(--secondary);
        background: rgba(212, 175, 55, 0.05);
    }

    .doc-card i {
        font-size: 32px;
        margin-bottom: 12px;
        opacity: 0.5;
    }

    /* Buttons Premium */
    .btn-premium-gold {
        background: linear-gradient(135deg, #D4AF37, #F1D18A);
        color: #0F172A;
        border: none;
        padding: 12px 28px;
        border-radius: 14px;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 10px 25px -5px rgba(212, 175, 55, 0.3);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-premium-gold:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px -10px rgba(212, 175, 55, 0.4);
    }

    .btn-premium-glass {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--card-border);
        color: var(--text-main);
        padding: 12px 28px;
        border-radius: 14px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-premium-glass:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: var(--secondary);
    }

    .pagination-wrap {
        padding: 32px;
        border-top: 1px solid var(--card-border);
    }
</style>
@endsection

@section('content')
<div class="page-header" data-aos="fade-down">
    <div>
        <h1 class="page-title">Booking Intelligence</h1>
        <div class="breadcrumb">
            <span>Operational Center</span>
            <i class="fas fa-chevron-right" style="font-size: 7px; opacity: 0.5;"></i>
            <span style="color: var(--secondary);">Reservation Management</span>
        </div>
    </div>
    <div class="header-actions">
        <button class="btn-premium-glass">
            <i class="fas fa-filter" style="color: var(--secondary);"></i> Advanced Filter
        </button>
        <button class="btn-premium-gold">
            <i class="fas fa-plus-circle"></i> Manual Reservation
        </button>
    </div>
</div>

<!-- Summary Stats -->
<div class="summary-grid">
    <div class="stat-card-premium" data-aos="fade-up" data-aos-delay="0">
        <div class="stat-icon" style="background: var(--warning-bg); color: var(--warning-text);">
            <i class="fas fa-hourglass-half"></i>
        </div>
        <div class="stat-info">
            <div class="stat-label">Pending Verification</div>
            <div class="stat-value">{{ number_format($stats['pending']) }} <span class="stat-unit">Tickets</span></div>
        </div>
    </div>
    <div class="stat-card-premium" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-icon" style="background: var(--info-bg); color: var(--info-text);">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-label">Active Reservations</div>
            <div class="stat-value">{{ number_format($stats['total'] - $stats['pending']) }} <span class="stat-unit">Active</span></div>
        </div>
    </div>
    <div class="stat-card-premium" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8B5CF6;">
            <i class="fas fa-car-side"></i>
        </div>
        <div class="stat-info">
            <div class="stat-label">Ongoing Rentals</div>
            <div class="stat-value">{{ number_format($stats['ongoing']) }} <span class="stat-unit">Units</span></div>
        </div>
    </div>
    <div class="stat-card-premium" data-aos="fade-up" data-aos-delay="300">
        <div class="stat-icon" style="background: var(--success-bg); color: var(--success-text);">
            <i class="fas fa-check-double"></i>
        </div>
        <div class="stat-info">
            <div class="stat-label">Completed Cycles</div>
            <div class="stat-value">{{ number_format($stats['completed']) }} <span class="stat-unit">Success</span></div>
        </div>
    </div>
</div>

<!-- Main Data Table -->
<div class="table-card" data-aos="fade-up">
    <div class="table-card-head">
        <div class="table-title-wrap">
            <h3>Reservation Ledger</h3>
            <p>Unified view for all mobility requests</p>
        </div>
        <div class="search-input-wrap">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search Reference, Name..." class="search-input">
        </div>
    </div>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Customer Entity</th>
                    <th>Allocated Asset</th>
                    <th>Service Window</th>
                    <th>Financial</th>
                    <th>Cycle</th>
                    <th style="text-align: right;">Ops</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>
                        <span class="booking-code-chip">{{ $booking->booking_code }}</span>
                    </td>
                    <td>
                        <div class="customer-cell">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($booking->customer->name ?? 'U') }}&background=0F172A&color=D4AF37&bold=true" class="customer-avatar">
                            <div>
                                <div class="customer-name">{{ $booking->customer->name ?? '-' }}</div>
                                <div class="customer-phone">{{ $booking->customer->phone ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="asset-cell">
                            <div class="car-name">{{ $booking->car->car_name ?? '-' }}</div>
                            <div class="car-badge-wrap">
                                <span class="plate-badge">{{ $booking->car->plate_number ?? '-' }}</span>
                                <span class="service-type">{{ $booking->with_driver ? 'Driver' : 'Self-Drive' }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="time-cell">
                            <div class="time-item">
                                <i class="fas fa-arrow-right-to-bracket" style="color: var(--success);"></i>
                                {{ $booking->pickup_date->translatedFormat('d M Y, H:i') }}
                            </div>
                            <div class="time-item">
                                <i class="fas fa-arrow-right-from-bracket" style="color: var(--danger);"></i>
                                {{ $booking->return_date->translatedFormat('d M Y, H:i') }}
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                        $pStatus = [
                        'paid' => ['var(--success-bg)', 'var(--success-text)', 'Paid'],
                        'partial' => ['var(--warning-bg)', 'var(--warning-text)', 'Partial'],
                        'unpaid' => ['var(--danger-bg)', 'var(--danger-text)', 'Unpaid'],
                        'refunded' => ['var(--info-bg)', 'var(--info-text)', 'Refunded'],
                        ][$booking->payment_status] ?? ['rgba(0,0,0,0.05)', 'var(--text-muted)', $booking->payment_status];
                        @endphp
                        <span style="--bg: {{ $pStatus[0] }}; --color: {{ $pStatus[1] }}; background: var(--bg); color: var(--color); padding: 4px 12px; border-radius: 99px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; white-space:nowrap;">
                            {{ $pStatus[2] }}
                        </span>
                    </td>
                    <td>
                        @php
                        $bStatus = [
                        'pending' => ['var(--warning-bg)', 'var(--warning-text)', 'Pending'],
                        'confirmed' => ['var(--info-bg)', 'var(--info-text)', 'Confirmed'],
                        'ongoing' => ['rgba(139, 92, 246, 0.1)', '#8B5CF6', 'Active'],
                        'completed' => ['var(--success-bg)', 'var(--success-text)', 'Completed'],
                        'cancelled' => ['var(--danger-bg)', 'var(--danger-text)', 'Cancelled'],
                        'expired' => ['var(--danger-bg)', 'var(--danger-text)', 'Expired'],
                        ][$booking->booking_status] ?? ['rgba(0,0,0,0.05)', 'var(--text-muted)', $booking->booking_status];
                        @endphp
                        <span style="--bg: {{ $bStatus[0] }}; --color: {{ $bStatus[1] }}; background: var(--bg); color: var(--color); padding: 4px 12px; border-radius: 99px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; white-space:nowrap;">
                            {{ $bStatus[2] }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; justify-content: flex-end;">
                            <a href="javascript:void(0)" onclick="openBookingDetail('{{ $booking->id }}')" class="action-btn-circle">
                                <i class="fas fa-eye" style="font-size: 12px;"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">
        {{ $bookings->links() }}
    </div>
</div>

<!-- BOOKING DETAIL MODAL -->
<div class="modal-overlay" id="bookingModal">
    <div class="modal-content">
        <div class="modal-header">
            <div>
                <h2 class="page-title" style="font-size: 20px;">Intelligence Report</h2>
                <div class="breadcrumb">
                    <span>Comprehensive Data Analysis</span>
                </div>
            </div>
            <button class="action-btn-circle" onclick="closeModal('bookingModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <div class="detail-grid">
                <!-- Left Column -->
                <div>
                    <div class="info-section">
                        <h3 class="section-title"><i class="fas fa-user-shield"></i> Entity Verification</h3>
                        <div class="data-row">
                            <span class="data-label">Customer Name</span>
                            <span class="data-value" id="det-name">Budi Santoso</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Primary Contact</span>
                            <span class="data-value" id="det-phone">08123456789</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Allocated Asset</span>
                            <span class="data-value" style="color: var(--secondary);" id="det-car">Honda Brio RS</span>
                        </div>
                    </div>

                    <div class="info-section">
                        <h3 class="section-title"><i class="fas fa-map-location-dot"></i> Service Logistics</h3>
                        <div class="data-row">
                            <span class="data-label">Departure</span>
                            <span class="data-value" id="det-pickup">16 May 2026, 00:00</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Arrival</span>
                            <span class="data-value" id="det-return">17 May 2026, 00:00</span>
                        </div>
                    </div>

                    <div class="info-section" style="border: 1px solid var(--secondary); background: rgba(212, 175, 55, 0.05);">
                        <h3 class="section-title"><i class="fas fa-wallet"></i> Financial Details</h3>
                        <div class="data-row">
                            <span class="data-label">Base Rate</span>
                            <span class="data-value">Rp 350.000</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Service Fee</span>
                            <span class="data-value">Rp 50.000</span>
                        </div>
                        <div style="margin-top: 16px; padding-top: 16px; border-top: 1px dashed var(--card-border); display: flex; justify-content: space-between; align-items: flex-end;">
                            <div>
                                <div class="data-label" style="margin-bottom: 4px;">Total Assessment</div>
                                <div class="page-title" style="font-size: 24px;">Rp 400.000</div>
                            </div>
                            <span class="booking-code-chip">PAID</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="info-section">
                        <h3 class="section-title"><i class="fas fa-id-card"></i> Identity Assets</h3>
                        <div class="doc-card">
                            <i class="fas fa-cloud-arrow-up"></i>
                            <span style="font-size: 11px; font-weight: 800;">View ID Document</span>
                        </div>
                        <p style="font-size: 10px; color: var(--text-muted); text-align: center; margin-top: 12px; font-weight: 600;">Document verified by system</p>
                    </div>

                    <div class="info-section">
                        <h3 class="section-title"><i class="fas fa-clock-rotate-left"></i> Cycle Status</h3>
                        <div class="timeline">
                            <div class="timeline-item done">
                                <div class="data-value" style="font-size: 12px;">Booking Received</div>
                                <div class="data-label" style="font-size: 10px;">15 May 2026, 10:00</div>
                            </div>
                            <div class="timeline-item active">
                                <div class="data-value" style="font-size: 12px;">Payment Verification</div>
                                <div class="data-label" style="font-size: 10px;">Awaiting Bank Confirmation</div>
                            </div>
                            <div class="timeline-item">
                                <div class="data-value" style="font-size: 12px;">Fleet Preparation</div>
                                <div class="data-label" style="font-size: 10px;">Not Started</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-header" style="border-top: 1px solid var(--card-border); border-bottom: none; background: rgba(0,0,0,0.02);">
            <button class="btn-premium-glass" style="color: var(--danger); border-color: rgba(239, 68, 68, 0.2);">
                <i class="fas fa-ban"></i> Cancel Reservation
            </button>
            <div style="display: flex; gap: 12px;">
                <button class="btn-premium-glass">Export PDF</button>
                <button class="btn-premium-gold">Approve & Issue Invoice</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openBookingDetail(id) {
        document.getElementById('bookingModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal('bookingModal');
    });

    document.getElementById('bookingModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal('bookingModal');
    });
</script>
@endsection